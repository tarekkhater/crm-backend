<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $users = parent::toArray($request);
        $data = [];
        foreach ($users as $user) {
            $codep = $user['countries']['phonecode']??'-';
            $data[] = [
                'id' => $user['id'],
                'name' => $user['name'].' '.$user['surname'],
                'surname'=>$user['name'].' '.$user['surname'],
                'email' => $user['email'],
                'source' => $user['userInfo']['source']['name']??'-',
                'phone' => '(+'.$codep.')'.$user['phone'],
                "avatar"=> asset($user['avatar'],),
                'verified'=>$user['email_verified_at'] != null?true:false,
                'withdraw'=>$user['can_withdraw'] == '1'?true:false,
                'allow_trade'=>$user['allow_trade'] == '1'?true:false,
                'allow_trade_after_hours'=>$user['allow_trade_after_hours'] == '1'?true:false,
                'online'=>$user['no_of_logins']== '1'?1:0,
                'last_comment'=>$user['last_agent_note_date'],
                'last_comment_content'=>$user['last_agent_note_content'],
                'plan'=> $user['userInfo']['plan']['name']??0,
                'broker' => [
                    'name' => ($user['broker']['name']??'none').($user['broker']['surname']??''),
                    'surname' => $user['broker']['surname']??'none',
                    'email' => $user['broker']['email']??'none',
                ],
                'phone_code' => $user['phone_code'],
                'countries' => $user['countries'],
                'country' => $user['countries']['name']??'-',
                'created_at' => $user['created_at'],
                'user_info'=>[
                    "balance"=> (($user['userInfo']['money']??0) + ($user['userInfo']['balance']??0))."$",
                    "plan_id"=> $user['userInfo']['plan_id']??0,
                    "branch_id"=> $user['userInfo']['branch_id']??0,
                    "status_id"=> $user['userInfo']['status_id']??0,
                    "source_id"=> $user['userInfo']['source_id']??0,
                ],
                'manager' => $user['Manager']?? null,    
                'category' => $this->HandleType($user['Manager']['manager'] ?? null),    
                'agent' => $user['Manager']['manager']['name']?? null,    
            ];
        }
        return $data;
    }
    
    private function HandleType($data){
        
        $title = "not assign";
        if($data){
          if(in_array($data->type_id,[7,8])){
              $title = $data->type_id == 7?"convertiion":"retenetioin";
          }elseif(in_array($data->sub_type_id,[7,8])){
                $title = $data->sub_type_id == 7?"convertiion":"retenetioin";
          }
        }
        return $title;
    }
}
