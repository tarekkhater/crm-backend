<?php

namespace App\Http\Resources\User\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $data->load(['myWithdrawals'=>function($q){
            $q->where('status',1)->sum('amount');
        },'deposits'=>function($q){
            $q->where('status',1)->sum('amount');
        },'transactions','accounts','wireAccounts','trades','identity']);
        $resilt = [
            'id'=>$data["id"],
            'email'=>$data["email"],
            'name'=>$data["name"],
            'surname'=>$data["surname"],
            'phone'=> $data["phone"],
            'avatar'=>$data["avatar"],
            'address'=>$data["address"],
            'balance'=>$data["balance"],
            'deposits'=>$data["deposits"],
            'withdrawals'=>$data["myWithdrawals"],
            'currency'=>$data["currency"],
            'country'=>$data["country"],
            'phone_code'=>$data["phone_code"],
            'postal'=>$data["postal"],
            'type'=>$data["type"],
            'join_at'=>date('Y M d',$data["created_at"]),
            'plan'=>'stander',
            'status'=>$data["status"],
            'document'=>count($data["identity"]) > 0 ?true:false,
            'actual_wallet'=>$data['userInfo']["balance"],
            'trading_wallet'=>$data['userInfo']["money"],
        ];
        return $resilt;

    }
}
