<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
class PositionResource extends JsonResource
{
    public function toArray($request)
    {
 
        $profit = $this->net_profit;
       if($this->close_at == null){
            if($this->direction === 'buy'){
                    $priceDiff = (float)($this->currency->rate??0) - $this->opening_price;
                    // الربح الخام
                    $rawProfit = $priceDiff * $this->lot * $this->amount;
                    
                    // تكلفة السبريد (لو في ربح موجب فقط)
                    $spreadCost = $priceDiff > 0 ? ($this->spread / 100) * $this->lot * $this->amount : 0;
                    
                    // صافي الربح
                    $profit = $rawProfit - $spreadCost + $this->com;
                    
                    
                    $this->profit = $profit;
                    $this->net_profit = $profit;
                    $this->save();
            }else{
                    $priceDiff = $this->opening_price - (float)$this->currency->rate;
                    
                    
                    // الربح الخام
                    $rawProfit = $priceDiff * $this->lot * $this->amount;
                    
                    // تكلفة السبريد (لو في ربح موجب فقط)
                    $spreadCost = $priceDiff > 0 ? ($this->spread / 100) * $this->lot * $this->amount : 0;
                    
                    // صافي الربح
                    $profit = $rawProfit - $spreadCost + $this->com;
                    
                    // تنسيق الرقم لو محتاج (اختياري)
                    $profit =$profit;
                    $this->profit = $profit;
                    $this->net_profit = $profit;
                    $this->save();
            }
       }
       
       
    
        return [
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
             'email'            => $this->user->email,
            'symbol'             => $this->symbol,
            'direction'          => $this->direction,
            'opening_price'      => $this->opening_price,
            'lot'                => $this->lot,
            'amount'             => $this->amount,
            'spread'             => $this->spread,
            'com'             => $this->com,
            'leverage'           => $this->leverage,
            'margin'             => $this->margin,
            'margin'             => $this->spread,
            'spread_cost'        => round(($this->spread * 0.0001 * $this->amount), 2), // لحساب تكلفة السبريد
            'stop_loss'          => $this->stop_loss,
            'take_profit'        => $this->take_profit,
            'stop_loss_price'    => $this->stop_loss_price,
            'take_profit_price'  => $this->take_profit_price,
            'profit'             => round($profit,2),
            'net_profit'         => round($profit,2),
            'live_loss'          => $this->live_loss,
            'loss'               => $this->loss,
            'trade_amount'       => $this->trade_amount,
            'current_price'      => round($this->currency->rate??0,4),
            'closed_by'      => $this->closed_by =='user'?$this->user->email:$this->closed_by,
            'created_by'      => $this->created_by =='user'?$this->user->email:$this->created_by,
            'updated_by'      => $this->updated_by,
            'currency'           => [
                'image' => $this->currency->image ?? null,
                'name'  => $this->currency->name ?? null,
                'base'  => $this->currency->base ?? null,
                'sym'   => $this->currency->sym ?? null,
                'type'  => $this->currency->type ?? null,
                'id'  => $this->currency->id ?? null,
            ],
            'close_at'           => $this->close_at,
            'created_at'         => $this->created_at?->toDateTimeString(),
            'open_at'=> $this->created_at?->toDateTimeString(),
            'updated_at'         => $this->updated_at?->toDateTimeString(),
        ];
    }
    
public function truncate_numbert($number, $decimals = 2) {
      $factor = pow(10, $decimals);
    $truncated = ($number >= 0)
        ? floor($number * $factor) / $factor
        : ceil($number * $factor) / $factor;

    return number_format($truncated, $decimals, '.', '');
}
    
    
}
