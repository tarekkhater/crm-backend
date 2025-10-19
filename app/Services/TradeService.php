<?php

namespace App\Services;

use App\Models\Position;
use App\Models\User;
use App\Models\InfoTradeUser;
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
    
    public function createTrade(array $data)
    {
             $direction = $data['direction'];
            $opening_price = $data['opening_price'];
            
            $stop_loss_price = null;
            $take_profit_price = null;
           
            // Stop Loss / Take Profit price calculation using actual price difference
            if (!empty($data['stop_loss'])) {
                $stop_loss_price = $direction === 'buy'
                    ? $opening_price - $data['stop_loss']
                    : $opening_price + $data['stop_loss'];
            }
            
            if (!empty($data['take_profit'])) {
                $take_profit_price = $direction === 'buy'
                    ? $opening_price + $data['take_profit']
                    : $opening_price - $data['take_profit'];
            }
            
            // Pip value and PnL calculation using price difference (no pip_size)
            $contract_size = 100000;
            $lot = $data['lot'];
            $leverage = $data['leverage'] ?? 1;
            
            $price_per_point = $lot * $contract_size / $opening_price; // Optional if needed
            $profit = !empty($data['take_profit']) ? $data['take_profit'] * $data['amount'] : null;
            $loss = !empty($data['stop_loss']) ? $data['stop_loss'] * $data['amount'] : null;
            
            // Margin calculation
            $margin = $data['amount'] / $leverage;
            $data['stop_loss_price'] = $stop_loss_price;
            $data['take_profit_price'] = $take_profit_price;
            // $data['trade_amount'] = $data['total'];
            
            $data['created_by'] = AuthApi()?'user':auth()->user()->email;
            // Create trade
           return Position::create($data);
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
        if (isset($data[$field])) {
            $trade->$field = $data[$field];
        }
    }

    // Recalculate SL/TP prices based on updated values
    $opening_price = $data["opening_price"];
    $direction = strtolower($trade->direction);

    if (!empty($trade->stop_loss)) {
        $trade->stop_loss_price = $direction === 'buy'
            ? $opening_price - $trade->stop_loss
            : $opening_price + $trade->stop_loss;
    }

    if (!empty($trade->take_profit)) {
        $trade->take_profit_price = $direction === 'buy'
            ? $opening_price + $trade->take_profit
            : $opening_price - $trade->take_profit;
    }

    // Optional: update margin
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

    // Get current market price
    $currentPrice = $trade->currency->rate;
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
        $users = InfoTradeUser::where('user_id',$trade->user_id)->first();
        $users->balance += (float)$profit;
        $users->save();
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
