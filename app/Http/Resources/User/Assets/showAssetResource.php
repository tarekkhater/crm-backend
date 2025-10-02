<?php

namespace App\Http\Resources\User\Assets;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class showAssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

       $value = parent::toArray($request);
$base_url=URL::to('/').'/';
$results = [
            'id'=>$value["id"],
            'name'=>$value["name"],
            'leverage'=>$value["leverage"],
            'lots'=>1,
            'sym'=>$value["sym"],
            'image'=>$base_url.$value["image"],
            'base'=>$value["base"],
             "cur"=> 'USD',
            'type'=>$value["type"],
            "ex_sym"=> $value["sym"],
            "com"=> $value["com"],
            "rate"=> $value["rate"],
            "buy_spread"=> $value["buy_spread"],
            "sell_spread"=> $value["sell_spread"],
            "open_at"=> $value["open_at"],
            "close_at"=> $value["close_at"],
            "sy"=> $value["sy"],
            "current_price"=>0,
            "buy_p"=>$value["buy_spread"],
            "sell_p"=>$value["sell_spread"],
        ];

        // $this->calcsellspreed($value["sell_spread"],$value["sym"],$value["base"],$value["type"])
        // $this->calcbuyspreed($value["buy_spread"],$value["sym"],$value["base"],$value["type"])
        return $results;
    }

    public function calcsellspreed($sell_spreads,$sym,$base,$type){
        // $currentPrice = $this->getCurRate($sym, $base, $type);
        $currentPrice =0;
        $sell_spread = floatval(($sell_spreads * $currentPrice) / 100);
        $s_price = floatval($currentPrice) - floatval($sell_spread);
        return $s_price;
    }

     public function calcbuyspreed($buy_spreads){
         // $currentPrice = $this->getCurRate($sym, $base, $type);
        $currentPrice = 0;
        $buy_spread = floatval(($buy_spreads * $currentPrice) / 100);
        $b_price = floatval($currentPrice) - floatval($buy_spread);
        return $b_price;
    }
}
