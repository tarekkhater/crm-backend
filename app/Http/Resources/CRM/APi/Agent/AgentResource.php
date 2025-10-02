<?php

namespace App\Http\Resources\CRM\APi\Agent;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Models\Admin;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       $result = [];
       $value = parent::toArray($request);
        $result['id']= $value['id'];
       $result['name']= $value['name'];
       $result['surname']= $value['surname'];
       $result['email']= $value['email'];
       $result['country']=$value['country']?$value['country']:0;
                $result['countries']=$value['countries']?$value['countries']:'No Country';
       $result['image']= $value['image'] != 'faild' && $value['image'] != 'Failed'?asset($value['image']):null;
       $result['phone']='(+'.$value['countries']['phonecode'].') '. $value['phone'];
       $result['type']= $value['type_id'] == '6'?'teamleader':'agent';
       $result['agent']= $this->checkAgent($value['type_id'],$value['sub_type_id']);
        $result['main'] = [
                'email'=>$value['email'],
                'phone'=>'(+'.$value['countries']['phonecode'].') '. $value['phone'],
                'country'=>$value['country']?$value['country']:0,
                'countries'=>$value['countries']?$value['countries']:'No Country',
                'joined'=>date('Y M d',strtotime($value['created_at'] )),
            ]; 

         
        
            return $result;
    }
    
    public function checkAgent($type,$subtype){
        $srttype = "";
        if($subtype == 0){
            $srttype = $type==7?"sales":"retenaion";
        }else{
            $srttype = $subtype==7?"sales":"retenaion";
        }
        return $srttype;
    }
}
