<?php

namespace App\Http\Resources\User\Assets;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Favourite;
use Illuminate\Support\Facades\URL;

class AssetsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

       $data = parent::toArray($request);
       $results = [];
       $base_url=URL::to('/').'/';

       foreach($data as $value){
           $statusfavourite = 0;
           if(AuthApi()){
               if(Favourite::where('user_id', AuthApi()->id)->where('asset_id',$value["id"])->exists()){
                   $statusfavourite =1;
               }
           }
        $results[] = [
            'id'=>$value["id"],
            'value'=>$value["id"],
            'name'=>$value["name"],
             'label'=>$value["name"],
            'sym'=>$value["sym"],
            'image'=>$value["image"],
            'base'=>$value["base"],
            'type'=>$value["type"],
            "ex_sym"=> $value["sym"],
            "com"=> $value["com"],
            "rate"=> $value["rate"],
            "buy_spread"=> $value["buy_spread"],
            "sell_spread"=> $value["sell_spread"],
            "open_at"=> $value["open_at"],
            "close_at"=> $value["close_at"],
            "sy"=> $value["sy"],
            "buy_p"=> $value["buy_p"],
            "sell_p"=> $value["sell_p"],
            'is_favourite'=>$statusfavourite,
        ];
       }

        return $results;
    }
}
