<?php

namespace App\Services;

use App\Models\CurrencyPair;
use App\Models\Position;
use App\Models\User;
use App\Models\InfoTradeUser;
use Illuminate\Validation\ValidationException;
use App\Services\Users\UserWalletService;
use Carbon\Carbon;

class TradeService
{
    
    public function index($user=null){
        $trades = Position::where('close_at',null)->with('user')->orderByDesc('created_at')->paginate(60);
        if($user != null){
             $trades = Position::whereIn('user_id',$user)->with('user')->where('close_at',null)->orderByDesc('created_at')->paginate(60);
        }
        return $trades;
    }
    
     public function indexClose($user=null){
        $trades = Position::where('close_at',"<>",null)->with('user')->latest()->paginate(60);
        if($user != null){
             $trades = Position::whereIn('user_id',$user)->with('user')->where('close_at',"<>",null)->latest()->paginate(60);
        }
        return $trades;
    }
    
    /**
     * Parse booleans from JSON/API input. Request::boolean() mis-reads JSON true as false.
     */
    public function parseRequestBoolean(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return false;
    }

    public function resolveIsAiTradeForUser(User $user, bool $requested): bool
    {
        $aiEnabled = (int) ($user->ai_trading ?? 0) === 1;

        if (!$aiEnabled) {
            if ($requested) {
                throw ValidationException::withMessages([
                    'is_ai_trade' => ['AI trading is not enabled for this user.'],
                ]);
            }

            return false;
        }

        return $requested;
    }

    public function resolveCurrencyPair(string $symbol): ?CurrencyPair
    {
        return CurrencyPair::query()
            ->where('ex_sym', $symbol)
            ->orWhere('sym', $symbol)
            ->first();
    }

    public function normalizePnLThreshold(mixed $value): float
    {
        if ($value === null || $value === false || $value === '') {
            return 0.0;
        }

        return max(0, (float) $value);
    }

    public function createTrade(array $data)
    {
            $currencyPair = $this->resolveCurrencyPair($data['symbol']);

            if (!$currencyPair) {
                throw ValidationException::withMessages([
                    'symbol' => ['Invalid trading symbol.'],
                ]);
            }

            $data['symbol'] = $currencyPair->ex_sym;
            $data['stop_loss'] = $this->normalizePnLThreshold($data['stop_loss'] ?? null);
            $data['take_profit'] = $this->normalizePnLThreshold($data['take_profit'] ?? null);
            $data['stop_loss_price'] = null;
            $data['take_profit_price'] = null;
            $data['created_by'] = AuthApi() ? 'user' : auth()->user()->email;
            $data['is_ai_trade'] = (bool) ($data['is_ai_trade'] ?? false);

            return Position::create($data)->load('currency');
    }
    
    
    public function updateTrade($tradeId, array $data)
{
    $trade = Position::findOrFail($tradeId);

    // Don't allow update if trade is closed
    if ($trade->close_at !== null) {
        throw new \Exception("Cannot update a closed trade.");
    }

    // Allowed fields to update
    $editableFields = [
        'lot',
        'amount',
        'spread',
        'leverage',
        'stop_loss',
        'take_profit',
        'direction',
        'opening_price',
        'com',
        'open_at'
    ];

    foreach ($editableFields as $field) {
        if (array_key_exists($field, $data)) {
            if (in_array($field, ['stop_loss', 'take_profit'], true)) {
                $trade->$field = $this->normalizePnLThreshold($data[$field]);
            } else {
                $trade->$field = $data[$field];
            }
        }
    }

    $trade->stop_loss_price = null;
    $trade->take_profit_price = null;
    $trade->margin = round($trade->amount / $trade->leverage, 2);
    $trade->updated_by = auth()->user()->email;
    $trade->save();

    return $trade;
}

    


public function closeTrade($tradeId,$profit=0,$addProfit=false)
{
    $trade = Position::with('user')->findOrFail($tradeId);

    if ($trade->close_at !== null) {
        throw new \Exception("Trade already closed.");
    }

    // Get current market price (quoted exit — matches frontend display)
    $midRate = (float) ($trade->currency->rate ?? 0);
    $trade->closed_price = $trade->quotedExitPrice($midRate);
    // $spread = $trade->spread ?? 0;

    // // Calculate spread cost
    // $spreadCost = ($trade->opening_price * $spread) / 100;

    // // Margin
    // $trade->margin = round($trade->amount / $trade->leverage, 2);

    // // Price diff and direction
    // $direction = strtolower($trade->direction);
    // if ($direction === 'buy') {
    //     $priceDiff = $currentPrice - $trade->opening_price;
    // } elseif ($direction === 'sell') {
    //     $priceDiff = $trade->opening_price - $currentPrice;
    // } else {
    //     $priceDiff = 0;
    // }

    // // Profit & Net Profit
    // $trade->profit = round($priceDiff * $trade->amount, 2);
    // $neprofit = round($trade->profit - $spreadCost, 2);
    // $trade->net_profit = $neprofit;
    // // Live Loss
    // $trade->live_loss = $trade->profit < 0 ? abs($trade->profit) : 0;

    // // Trade Amount
    // $trade->trade_amount = round($trade->amount * ($trade->opening_price - $spreadCost) / $trade->leverage, 5);

    // Set close time
    
    
    
    
        
    $trade->close_at = Carbon::now();
    $trade->closed_by = AuthApi()?'user':auth()->user()->email;
   

    // Add net profit to user's balance
    $user = $trade->user;
    // if ($user && property_exists($user, 'balance')) {
        $users = InfoTradeUser::where('user_id', $trade->user_id)->first();
        if ($users) {
            UserWalletService::applyMainWalletDelta($users, (float) $profit);
            $users->save();
        }
    // }
 // Save trade
    $trade->save();
    // if($addProfit){
        $trade->net_profit = $profit;
        $trade->profit = $profit;
    // }else{
    //     $spread = $trade->spread ?? 0;
    
    //     // الهامش
    //     $trade->margin = round($trade->amount / $trade->leverage, 2);
    
    //     // تكلفة السبريد
    //     $spreadCost = $spread * 0.01 * $trade->amount;
    
    //     // الربح
    //     $direction = strtolower($trade->direction);
    //     if ($direction === 'buy') {
    //         $priceDiff = $currentPrice - $trade->opening_price;
    //     } elseif ($direction === 'sell') {
    //         $priceDiff = $trade->opening_price - $currentPrice;
    //     } else {
    //         $priceDiff = 0;
    //     }
    
    //     $trade->profit = round($priceDiff * $trade->amount, 2);
    
    //     // صافي الربح
    //     $trade->net_profit = round($trade->profit - $spreadCost, 2);
    
    //     // الخسارة المباشرة
    //     $trade->live_loss = $trade->profit < 0 ? abs($trade->profit) : 0;
    
    //     // قيمة الصفقة
    //     $trade->trade_amount = round($trade->amount - $spreadCost, 2); 
    // }
    $trade->save();
    return $trade;
}

public function ReopenTrade($tradeId)
{
    $trade = Position::with('user')->findOrFail($tradeId);

    if ($trade->close_at == null) {
        throw new \Exception("Trade already open.");
    }

    
    $trade->close_at = null;
    $trade->updated_by = auth()->user()->email;
    $user = $trade->user;
    $trade->save();
    return $trade;
}

    
    
    public function getCurRate($symbol, $base, $type)
    {
        // Dummy return — you should connect to API or use your logic
        return 71.32;
    }
    
    
    
    private function CalcProfitt($trade){
        
    
        $currentPrice = $trade->currency->rate;
        $spread = $trade->spread ?? 0;
    
        // الهامش
        $trade->margin = round($trade->amount / $trade->leverage, 2);
    
        // تكلفة السبريد
        $spreadCost = $spread * 0.01 * $trade->amount;
    
        // الربح
        $direction = strtolower($trade->direction);
        if ($direction === 'buy') {
            $priceDiff = $currentPrice - $trade->opening_price;
        } elseif ($direction === 'sell') {
            $priceDiff = $trade->opening_price - $currentPrice;
        } else {
            $priceDiff = 0;
        }
    
        $trade->profit = round($priceDiff * $trade->amount, 2);
    
        // صافي الربح
        $trade->net_profit = round($trade->profit - $spreadCost, 2);
    
        // الخسارة المباشرة
        $trade->live_loss = $trade->profit < 0 ? abs($trade->profit) : 0;
    
        // قيمة الصفقة
        $trade->trade_amount = round($trade->amount - $spreadCost, 2);
    
        $trade->save();
        }
    
}
