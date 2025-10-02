<?php

namespace App\Http\Resources\CRM\APi\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
        
        $resilt = [
            'id'=>$data["id"],
            'email'=>$data["email"],
            'type'=>$data["type_user"]['name']??'not',
            'name'=>$data["surname"],
            'token'=>$data["token"],
            'block'=>false,
            // 'block'=>$data["block"] == 0?false:true,
            // 'verified'=>$data["email_verified_at"] == null?false:true,
            'verified'=>true,
            'kyc'=>true,

            // 'kyc'=>count($data["identity"]) > 0 ?true:false,
            'role'=>'user'
            // 'role'=>$data["roles"]['role']['display_name'],
            // 'permisssions'=>$data["roles"]['role']['permission_role'],
        ];
        return $resilt;
    }
}
