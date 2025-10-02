<?php

namespace App\Observers;

use App\Models\Position;
use App\Models\Deposit;

use App\Events\TradeUpdated;
use App\Http\Resources\PositionResource;
use App\Models\Currency;
use App\Services\TradeService;

class CurrencyObserver
{
    protected $seviceTrade;
    protected $user;
    public function __construct() {
        $this->seviceTrade = new TradeService();
        if(AuthApi()){
        $this->user = AuthApi();
        $this->user->load('userInfo');
        }

    }
    public function created(Currency $currency)
    {
        $userId = auth()->id();
        $this->broadcastTradeUpdate($userId );
    }

    public function updated(Currency $currency)
    {
        $userId = auth()->id();
        $this->broadcastTradeUpdate($userId );
    }

    public function deleted(Currency $currency)
    {
        $userId = auth()->id();
        $this->broadcastTradeUpdate($userId );
    }

    private function broadcastTradeUpdate($userId)
    {
        $user = \App\Models\User::with('userInfo')->find($userId);
        if (!$user) return;

        $trades = Position::whereNull('close_at')->whereUserId($userId)->get();
        $pnl = $this->calcPnl($trades);
        $total_trades = $trades->sum('trade_amount');
        $bal = $user->userInfo->balance ?? 0;
        $bonus = $user->userInfo->bonus ?? 0;
        $equity = $pnl + $bal;
        $total_deposit = Deposit::where('user_id', $userId)->whereStatus(3)->sum('amount');

        $positions = $this->seviceTrade->index([$userId]);

        $response = PositionResource::collection($positions)->additional([
            "header" => [
                "balance" => $this->truncate_numbert($bal,2),
                "equity" => $this->truncate_numbert($equity,2),
                "pnl" => $pnl,
                "bonus" => $bonus,
                "margin" => $this->truncate_numbert($total_trades,2),
                "free_margin" => $this->truncate_numbert($bal - $total_trades,2),
                "awaiting_deposit" => $total_deposit,
                "free_margin_perc" => $equity != 0 
                    ? $this->truncate_numbert((($bal - $total_trades) / $equity) * 100, 2)
                    : 0,
            ]
        ]);

        $data = $response->response()->getData(true);
        broadcast(new TradeUpdated($userId, $data));
    }


    public function calcPnl($trades){
    $profit = 0;
    foreach($trades as $trade){
        $priceDiff = 0;
       if($trade->direction === 'buy'){

                    $priceDiff = (float)$trade->currency->rate - $trade->opening_price;
            }else{
                    $priceDiff = $trade->opening_price - (float)(float)$trade->currency->rate;
            }
    
        $rawProfit = $priceDiff * $trade->lot * $trade->amount;
        
        $spreadCost = $priceDiff > 0 ? ($trade->spread / 100) * $trade->lot * $trade->amount : 0;
        // صافي الربح
        $profit += $rawProfit - $spreadCost + $trade->com;
    }
    return round($profit,2);
}

function truncate_numbert($number, $decimals = 2) {
      $factor = pow(10, $decimals);
    $truncated = ($number >= 0)
        ? floor($number * $factor) / $factor
        : ceil($number * $factor) / $factor;

    return number_format($truncated, $decimals, '.', '');
}
}
