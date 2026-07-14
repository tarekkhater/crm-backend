<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;
class PositionResource extends JsonResource
{
    public function toArray($request)
    {
 
        $profit = $this->net_profit;
        $midRate = (float) ($this->currency->rate ?? 0);
        $displayPrice = $midRate;

        if ($this->close_at == null && $midRate > 0) {
            // Display-only — never save here (save triggers SL/TP on all open trades via saved event)
            $profit = $this->calculateFloatingProfit($midRate);
            $displayPrice = $this->quotedExitPrice($midRate);
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
            'spread_cost'        => round(($this->spread / 100) * ($this->lot ?? 1) * $this->amount, 4),
            'stop_loss'          => $this->stop_loss,
            'take_profit'        => $this->take_profit,
            'stop_loss_price'    => $this->stop_loss_price,
            'take_profit_price'  => $this->take_profit_price,
            'profit'             => round($profit,2),
            'net_profit'         => round($profit,2),
            'live_loss'          => $this->live_loss,
            'loss'               => $this->loss,
            'trade_amount'       => $this->trade_amount,
            'closed_price'       => $this->closed_price ? round($this->closed_price, 8) : null,
            'current_price'      => round($displayPrice, 4),
            'closed_by'      => $this->closed_by =='user'?$this->user->email:$this->closed_by,
            'created_by'      => $this->created_by =='user'?$this->user->email:$this->created_by,
            'is_ai_trade'     => (bool) $this->is_ai_trade,
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
            'created_at'         => $this->open_at?->toDateTimeString(),
            'open_at'=> $this->open_at?->toDateTimeString(),
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
