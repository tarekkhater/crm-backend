<?php

namespace App\Http\Resources\CRM\APi\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
class Login_Admin_Resource extends JsonResource
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

  // Retrieve the Admin instance
        $admin = Admin::find($data["id"]);
        
        // If the admin exists, retrieve the role directly
        $role = $admin ? $admin->roles->first() : null;

        // Check the role type and assign sub-type if applicable
        $roleType = $role ? $role->type : 'not';

        // Determine the role-based response
        $roleResponse = $roleType === 'sales'
            ? ($data["sub_type_id"] != null && $data["sub_type_id"] != 0 ? 'teamleader' : 'sales')
            : $roleType;

        return [
            'id' => $data["id"],
            'email' => $data["email"],
            'type' => $data["type"]['name'] ?? 'not',
            'name' => $data["surname"],
            'token' => $data["token"],
            'block' => boolval($data["block"]), // Simplified block check
            'verified' => isset($data["email_verified_at"]), // Simplified verified check
            'role' => $roleResponse,
        ];
        
        // $resilt = [
        //     'id'=>$data["id"],
        //     'email'=>$data["email"],
        //     'type'=>$data["type"]['name']??'not',
        //     'name'=>$data["surname"],
        //     'token'=>$data["token"],
        //     'block'=>$data["block"] == 0?false:true,
        //     'verified'=>$data["email_verified_at"] == null?false:true,
        //     'role'=>Role::find(Admin::find($data["id"])->roles[0])->type == 'sales'?$data["sub_type_id"]!= null && $data["sub_type_id"]!= 0 ?'teamleader':"sales":Role::find(Admin::find($data["id"])->roles[0])->type,
        // ];
        // return $resilt;
    }
    
    
}
