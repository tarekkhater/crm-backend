<?php

namespace App\Http\Resources\Admin\IB;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Models\Admin;

class IBResource extends JsonResource
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
      
       $result = [
                'id'=>$value['id'],
                'name'=>$value['name'],
                'surname'=>"ds",
                'email'=>$value['email'],
                // 'phone'=>'(+'.$value['countries'] != null?$value['countries']['phonecode']:'00'.') '. $value['phone'],
                'country'=>$value['country'],
                'phone'=>$value['phone'],
                'created_at'=>date('d M Y',strtotime($value['created_at'] )),
                'type'=>$value['type_id'] == '6'?'teamleader':'agent',
                'countries'=>$value['countries'],
                'image'=>asset($value['image']),
                'analytics'=>[
                    'trades'=>0,
                    'sales'=>0,
                    'retention'=>0,
                    'customer'=>0,
                ],
            ]; 

         
        
            return $result;
    }
}
