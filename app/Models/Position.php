<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\Users\UserWalletService;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'symbol',
        'direction',
        'opening_price',
        'lot',
        'amount',
        'spread',
        'leverage',
        'margin',
        'stop_loss',
        'take_profit',
        'stop_loss_price',
        'take_profit_price',
        'profit',
        'loss',
        'close_at',
        'net_profit',
        'live_loss',
        'trade_amount',
        'com',
        'created_by',
        'closed_by',
        'open_at',
        'is_ai_trade',
        'closed_price',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'opening_price' => 'float',
        'lot' => 'float',
        'amount' => 'float',
        'spread' => 'float',
        'leverage' => 'integer',
        'margin' => 'float',
        'stop_loss' => 'float',
        'take_profit' => 'float',
        'stop_loss_price' => 'float',
        'take_profit_price' => 'float',
        'profit' => 'float',
        'loss' => 'float',
        'close_at' => 'datetime',
        'net_profit' => 'float',
        'live_loss' => 'float',
        'trade_amount' => 'float',
        'open_at' => 'datetime',
        'created_at' => 'datetime',
        'is_ai_trade' => 'boolean',
        'closed_price' => 'float',
    ];

    protected $with = ['currency'];

    
    protected static function booted()
    {
        static::retrieved(function (Position $position) {
            if ($position->relationLoaded('currency') && $position->currency === null && $position->symbol) {
                $position->setRelation(
                    'currency',
                    CurrencyPair::query()
                        ->where('ex_sym', $position->symbol)
                        ->orWhere('sym', $position->symbol)
                        ->first()
                );
            }
        });

        static::creating(function ($model) {
            $model->open_at = now(); // sets open_at to current timestamp
        });
        static::saving(function (Position $position) {
                $position->recalculateFields();
        });
        
        static::saved(function (Position $position) {
            
           $position->calculateAndSaveFullTradeData();
           
           
        });
        
        
        
        
    }

    public function currency()
    {
        return $this->belongsTo(CurrencyPair::class, 'symbol', 'ex_sym');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function recalculateFields()
    {
        if ($this->close_at != null) {
            return;
        }
        
        if (!$this->opening_price || !$this->lot || !$this->direction || !$this->leverage) {
            return;
        }

        if ($this->leverage <= 0) {
            return;
        }

        // stop_loss / take_profit = PnL thresholds in account currency (not price distance)
        $this->stop_loss = max(0, (float) ($this->stop_loss ?? 0));
        $this->take_profit = max(0, (float) ($this->take_profit ?? 0));
        $this->stop_loss_price = null;
        $this->take_profit_price = null;

        // الهامش
        $contract_size = $this->amount;
        $this->margin = round($contract_size / $this->leverage, 2);
        
    }

    // public function calculateAndSaveFullTradeData(): void
    // {
    //     if (
    //         !$this->currency ||
    //         !$this->opening_price ||
    //         !$this->amount ||
    //         !$this->direction ||
    //         !$this->leverage ||
    //         !$this->lot
    //     ) {
    //         return;
    //     }

    //     $currentPrice = $this->currency->rate;
    //     $spread = $this->spread ?? 0;
    //     $contractSize = $this->amount;

    //     // الهامش
    //     $this->margin = round(($this->lot * $contractSize / $this->leverage, 2);

    //     // تكلفة السبريد بالدولار
    //     $spreadCost = $spread * 0.01 * $contractSize;

    //     // الربح بالدولار مباشرة
    //     $direction = strtolower($this->direction);
    //     if ($direction === 'buy') {
    //         $priceDiff = $currentPrice - $this->opening_price;
    //     } elseif ($direction === 'sell') {
    //         $priceDiff = $this->opening_price - $currentPrice;
    //     } else {
    //         $priceDiff = 0;
    //     }

    //     $this->profit = round($priceDiff * $contractSize, 2);

    //     // صافي الربح
    //     $this->net_profit = round($this->profit - $spreadCost - $this->margin, 2);

    //     // الخسارة المباشرة إذا كان الربح سالب
    //     $this->live_loss = $this->profit < 0 ? abs($this->profit) : 0;

    //     // قيمة الصفقة الصافية
    //     $this->trade_amount = round($contractSize - $spreadCost, 2);

    //     $this->save();
    // }
    
    public function calculateAndSaveFullTradeData(): void
{
    if (
        !$this->currency ||
        !$this->opening_price ||
        !$this->amount ||
        !$this->direction ||
        !$this->leverage
    ) {
        return;
    }
    
    $midRate = (float) ($this->currency->rate ?? 0);

    $this->margin = round($this->amount / $this->leverage, 2);

    $contractSize = $this->lot * $this->amount;
    $this->trade_amount = ($contractSize * $this->opening_price) / $this->leverage;

    $profit = $this->calculateFloatingProfit($midRate);
    $this->profit = $profit;
    $this->net_profit = $profit;
    $this->live_loss = $profit < 0 ? abs($profit) : 0;

    // SL/TP auto-close is handled only by RunCurrencyBroadcast + trades:recalculate-profit.
    // Never trigger here — saved() runs on every API poll/update and causes mass closes.

    Model::withoutEvents(function () {
        $this->save();
    });
}

    public function isLong(): bool
    {
        return in_array(strtolower((string) $this->direction), ['buy', 'long'], true);
    }

    /**
     * Entry price after position spread (matches frontend calcPnL).
     */
    public function entryPriceWithSpread(): float
    {
        $open = (float) $this->opening_price;
        $spreadInPrice = ((float) ($this->spread ?? 0) / 100) * $open;

        return $this->isLong() ? $open + $spreadInPrice : $open - $spreadInPrice;
    }

    /**
     * Quoted exit price: long → buy_p, short → sell_p (matches CurrencyRateService + frontend).
     */
    public function quotedExitPrice(?float $midRate = null): float
    {
        $midRate = $midRate ?? (float) ($this->currency->rate ?? 0);
        if ($midRate <= 0) {
            return 0.0;
        }

        $buySpread = (float) ($this->currency->buy_spread ?? 0);
        $sellSpread = (float) ($this->currency->sell_spread ?? 0);

        if ($this->isLong()) {
            if ($buySpread > 0) {
                return self::truncateQuotedPrice($midRate - (($buySpread * $midRate) / 100));
            }

            return self::truncateQuotedPrice($midRate);
        }

        if ($sellSpread > 0) {
            return self::truncateQuotedPrice($midRate + (($sellSpread * $midRate) / 100));
        }

        return self::truncateQuotedPrice($midRate);
    }

    private static function truncateQuotedPrice(float $price, int $decimals = 4): float
    {
        $factor = pow(10, $decimals);

        return floor($price * $factor) / $factor;
    }

    /**
     * Floating PnL — same formula as frontend OpenTrades calcPnL.
     */
    public function calculateFloatingProfit(?float $midRate = null): float
    {
        $midRate = $midRate ?? (float) ($this->currency->rate ?? 0);

        if ($midRate <= 0 || !$this->opening_price || !$this->lot || !$this->amount) {
            return 0.0;
        }

        $entry = $this->entryPriceWithSpread();
        $current = $this->quotedExitPrice($midRate);
        $priceDiff = $this->isLong() ? $current - $entry : $entry - $current;
        $rawProfit = $priceDiff * (float) $this->lot * (float) $this->amount;

        return round($rawProfit + (float) ($this->com ?? 0), 2);
    }


    public static function summaryReport(int $userId, ?string $status = 'open'): array
    {
        $query = self::query()->where('user_id', $userId);

        if ($status === 'open') {
            $query->whereNull('close_at');
        } elseif ($status === 'closed') {
            $query->whereNotNull('close_at');
        }

        $positions = $query->get();

        $totalProfit = $positions->sum('profit');
        $totalLoss = $positions->sum('loss');
        $totalTradeAmount = $positions->sum(fn($trade) => $trade->trade_amount);
        $totalNetProfit = $totalProfit - $totalLoss;

        return [
            'total_trades'      => $positions->count(),
            'total_profit'      => round($totalProfit, 2),
            'total_loss'        => round($totalLoss, 2),
            'net_profit'        => round($totalNetProfit, 2),
            'total_trade_value' => round($totalTradeAmount, 2),
        ];
    }
    
    
     public function calculateAmountAndLotFromTradeAmount(float $tradeAmount, float $spread, float $leverage, float $margin): ?array
    {
        if ($leverage <= 0) return null;
    
        // حساب amount
        $amount = $tradeAmount / (1 - ($spread * 0.01));
    
        // حساب lot
        $lot = ($leverage * $margin) / $amount;
        
        return [
            'amount' => round($amount, 2),
            'lot' => round($lot, 4),
        ];
    }
    
    
    /**
     * Auto-close when floating PnL hits TP (>=) or SL (<= -threshold).
     */
    public function checkAndCloseByPnL(?float $profit = null, ?float $midRate = null): bool
    {
        if ($this->close_at !== null) {
            return false;
        }

        if (!$this->currency) {
            return false;
        }

        // Grace period: avoid auto-close in the first 10s after open (price/entry stabilization)
        if ($this->open_at && $this->open_at->diffInSeconds(now()) < 10) {
            return false;
        }

        $midRate = ($midRate !== null && $midRate > 0)
            ? $midRate
            : (float) ($this->currency->rate ?? 0);

        if ($midRate <= 0) {
            return false;
        }

        $profit = $profit ?? $this->calculateFloatingProfit($midRate);

        $hitTP = $this->take_profit > 0 && $profit >= $this->take_profit;
        $hitSL = $this->stop_loss > 0 && $profit <= (-1 * $this->stop_loss);

        if (!$hitTP && !$hitSL) {
            return false;
        }

        $this->profit = $profit;
        $this->net_profit = $profit;
        $this->live_loss = $profit < 0 ? abs($profit) : 0;
        $this->closed_price = $this->quotedExitPrice($midRate);
        $this->close_at = Carbon::now();
        $this->closed_by = function_exists('AuthApi') && AuthApi()
            ? 'user'
            : (auth()->user()->email ?? 'system');

        $this->loadMissing('user.userInfo');

        if ($this->user && $this->user->userInfo) {
            UserWalletService::applyMainWalletDelta($this->user->userInfo, (float) $profit);
            $this->user->userInfo->save();
        }

        $this->saveQuietly();

        return true;
    }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

// class Position extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'user_id',
//         'symbol',
//         'direction',
//         'opening_price',
//         'lot',
//         'amount',
//         'spread',
//         'leverage',
//         'margin',
//         'stop_loss',
//         'take_profit',
//         'stop_loss_price',
//         'take_profit_price',
//         'profit',
//         'loss',
//         'close_at',
//         'net_profit',
//         'live_loss',
//         'trade_amount',
//         'com',
//         'created_by',
//         'closed_by'
//     ];

//     protected $casts = [
//         'user_id' => 'integer',
//         'opening_price' => 'float',
//         'lot' => 'float',
//         'amount' => 'float',
//         'spread' => 'float',
//         'leverage' => 'integer',
//         'margin' => 'float',
//         'stop_loss' => 'float',
//         'take_profit' => 'float',
//         'stop_loss_price' => 'float',
//         'take_profit_price' => 'float',
//         'profit' => 'float',
//         'loss' => 'float',
//         'close_at' => 'datetime',
//         'net_profit' => 'float',
//         'live_loss' => 'float',
//         'trade_amount' => 'float',
//     ];

//     protected $with = ['currency'];


//     protected static function booted()
//     {
//         static::saving(function (Position $position) {
            
//                 $position->recalculateFields();
            
            
//         });
        
//         static::saved(function (Position $position) {
//             $position->calculateAndSaveFullTradeData();
//         });
        
        
        
        
//     }

//     public function currency()
//     {
//         return $this->belongsTo(CurrencyPair::class, 'symbol', 'ex_sym');
//     }


//     public function user()
//     {
//         return $this->belongsTo(User::class, 'user_id');
//     }
    
//     public function recalculateFields()
//     {
//         if ($this->close_at != null) {
//             return;
//         }
        
//         if (!$this->opening_price || !$this->lot || !$this->direction || !$this->leverage) {
//             return;
//         }

//         if ($this->leverage <= 0) {
//             return;
//         }

//         // stop loss/take profit price (اختياري)
//         $pip_size = 1;

//         if ($this->stop_loss) {
//             $this->stop_loss_price = $this->direction === 'buy'
//                 ? $this->opening_price - ($this->stop_loss * $pip_size)
//                 : $this->opening_price + ($this->stop_loss * $pip_size);
//         }

//         if ($this->take_profit) {
//             $this->take_profit_price = $this->direction === 'buy'
//                 ? $this->opening_price + ($this->take_profit * $pip_size)
//                 : $this->opening_price - ($this->take_profit * $pip_size);
//         }
        
//         // الهامش
//         $contract_size = $this->amount;
//         $this->margin = round($contract_size / $this->leverage, 2);
        
//     }

//     // public function calculateAndSaveFullTradeData(): void
//     // {
//     //     if (
//     //         !$this->currency ||
//     //         !$this->opening_price ||
//     //         !$this->amount ||
//     //         !$this->direction ||
//     //         !$this->leverage ||
//     //         !$this->lot
//     //     ) {
//     //         return;
//     //     }

//     //     $currentPrice = $this->currency->rate;
//     //     $spread = $this->spread ?? 0;
//     //     $contractSize = $this->amount;

//     //     // الهامش
//     //     $this->margin = round($this->lot * $contractSize / $this->leverage, 2);

//     //     // تكلفة السبريد بالدولار
//     //     $spreadCost = $spread * 0.01 * $contractSize;

//     //     // الربح بالدولار مباشرة
//     //     $direction = strtolower($this->direction);
//     //     if ($direction === 'buy') {
//     //         $priceDiff = $currentPrice - $this->opening_price;
//     //     } elseif ($direction === 'sell') {
//     //         $priceDiff = $this->opening_price - $currentPrice;
//     //     } else {
//     //         $priceDiff = 0;
//     //     }

//     //     $this->profit = round($priceDiff * $contractSize, 2);

//     //     // صافي الربح
//     //     $this->net_profit = round($this->profit - $spreadCost , 2);

//     //     // الخسارة المباشرة إذا كان الربح سالب
//     //     $this->live_loss = $this->profit < 0 ? abs($this->profit) : 0;

//     //     // قيمة الصفقة الصافية
//     //     $this->trade_amount = round($contractSize - $spreadCost, 2);

//     // // $this->checkAndCloseBySLTP();
//     // Model::withoutEvents(function () {
//     //     $this->save(); // ✅ لن يطلق أي Events
//     // });
//     // }
    
//     public function calculateAndSaveFullTradeData(): void
// {
//     if (
//         !$this->currency ||
//         !$this->opening_price ||
//         !$this->amount ||
//         !$this->direction ||
//         !$this->leverage
//     ) {
//         return;
//     }

//     $currentPrice = $this->currency->rate;
//     $spread = $this->spread ?? 0;

//     // الهامش
//     $this->margin = round($this->amount / $this->leverage, 2);

//     // تكلفة السبريد
//     // $spreadCost = ($this->opening_price
//     // *$spread)/100;
//     //  $spreadCost = $spread * 0.01 * $this->amount;

//     // // الربح
//     // $direction = strtolower($this->direction);
//     // if ($direction == 'buy') {
//     //     $priceDiff = $currentPrice - $this->opening_price;
//     // } elseif ($direction == 'sell') {
//     //     $priceDiff = $this->opening_price - $currentPrice;
//     // } else {
//     //     $priceDiff = 0;
//     // }

//     // $leverage = $this->leverage ?? 1; // default to 1 if leverage is not set
//     // $this->profit = round($priceDiff * $this->amount * $leverage, 5);
//     // $this->net_profit = round($this->profit - $spreadCost, 5);

//     // // الخسارة المباشرة
//     // $this->live_loss = $this->profit < 0 ? abs($this->profit) : 0;

//     // // قيمة الصفقة
//     // $this->trade_amount = $this->amount * ($this->opening_price - $spreadCost) / $this->leverage;
    
    
//     $trade_amount = $this->amount * $this->opening_price;
// $spreadCost = ($spread / 100) * $trade_amount;

// $direction = strtolower($this->direction);
// if ($direction == 'buy') {
//     $adjustedOpeningPrice = $this->opening_price + $spreadCost;
// } elseif ($direction == 'sell') {
//     $adjustedOpeningPrice = $this->opening_price - $spreadCost;
// } else {
//     $adjustedOpeningPrice = $this->opening_price;
// }

// // الآن استخدم $adjustedOpeningPrice بدل $trade->opening_price في حساب الربح
// $priceDiff = 0;
// if ($direction == 'buy') {
//     $priceDiff = $currentPrice - $adjustedOpeningPrice;
// } elseif ($direction == 'sell') {
//     $priceDiff = $adjustedOpeningPrice - $currentPrice;
// }

// $leverage = $this->leverage ?? 1;

// $this->profit = round($priceDiff * $this->amount * $leverage, 5);
// $this->net_profit = round($this->profit - $spreadCost, 5);

// $this->live_loss = $this->profit < 0 ? abs($this->profit) : 0;

// // القيمة المالية للصفقة بدون خصم السبريد
// $this->trade_amount = round(($trade_amount - $spreadCost)/$leverage, 2);

//     // $this->checkAndCloseBySLTP();
//     Model::withoutEvents(function () {
//         $this->save(); // ✅ لن يطلق أي Events
//     });
// }


//     public static function summaryReport(int $userId, ?string $status = 'open'): array
//     {
//         $query = self::query()->where('user_id', $userId);

//         if ($status === 'open') {
//             $query->whereNull('close_at');
//         } elseif ($status === 'closed') {
//             $query->whereNotNull('close_at');
//         }

//         $positions = $query->get();

//         $totalProfit = $positions->sum('profit');
//         $totalLoss = $positions->sum('loss');
//         $totalTradeAmount = $positions->sum(fn($trade) => $trade->trade_amount);
//         $totalNetProfit = $totalProfit - $totalLoss;

//         return [
//             'total_trades'      => $positions->count(),
//             'total_profit'      => round($totalProfit, 2),
//             'total_loss'        => round($totalLoss, 2),
//             'net_profit'        => round($totalNetProfit, 2),
//             'total_trade_value' => round($totalTradeAmount, 2),
//         ];
//     }
    
    
//      public function calculateAmountAndLotFromTradeAmount(float $tradeAmount, float $spread, float $leverage, float $margin): ?array
//     {
//         if ($leverage <= 0) return null;
    
//         // حساب amount
//         $amount = $tradeAmount / (1 - ($spread * 0.01));
    
//         // حساب lot
//         $lot = ($leverage * $margin) / $amount;
        
//         return [
//             'amount' => round($amount, 2),
//             'lot' => round($lot, 4),
//         ];
//     }
    
    
//     public function checkAndCloseBySLTP()
// {
//     // If already closed, skip
//     if ($this->close_at !== null) {
//         return false;
//     }

//     // Get live price
//     $currentPrice = $this->currency->rate ?? null;
//     if (!$currentPrice) return false;

//     $direction = strtolower($this->direction);

//     // Define condition to hit SL or TP
//     $hitSL = false;
//     $hitTP = false;

//     if ($direction === 'buy') {
//         if ($this->stop_loss_price && $currentPrice <= $this->stop_loss_price) {
//             $hitSL = true;
//         }
//         if ($this->take_profit_price && $currentPrice >= $this->take_profit_price) {
//             $hitTP = true;
//         }
//     } elseif ($direction === 'sell') {
//         if ($this->stop_loss_price && $currentPrice >= $this->stop_loss_price) {
//             $hitSL = true;
//         }
//         if ($this->take_profit_price && $currentPrice <= $this->take_profit_price) {
//             $hitTP = true;
//         }
//     }

//     if (!$hitSL && !$hitTP) {
//         return false; // No action needed
//     }

//     // CLOSE THE TRADE
//     $spread = $this->spread ?? 0;
//     $spreadCost = ($this->opening_price * $spread) / 100;

//     $priceDiff = 0;
//     if ($direction === 'buy') {
//         $priceDiff = $currentPrice - $this->opening_price;
//     } elseif ($direction === 'sell') {
//         $priceDiff = $this->opening_price - $currentPrice;
//     }

//     $this->profit = round($priceDiff * $this->amount, 5);
//     $this->net_profit = round($this->profit - $spreadCost, 5);
//     $this->live_loss = $this->profit < 0 ? abs($this->profit) : 0;
//     $this->trade_amount = round($this->amount * ($this->opening_price - $spreadCost) / $this->leverage, 5);
//     $this->close_at = Carbon::now();

//     // Update user balance
//     if ($this->user && property_exists($this->user->userInfo, 'balance')) {
//         $this->user->userInfo->balance += $this->net_profit;
//         $this->user->save();
//     }

//     $this->saveQuietly(); // Avoid recursion

//     return true;
// }
// }
