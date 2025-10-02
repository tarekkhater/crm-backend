<?php

namespace App\Http\Resources\Admin\Permissions;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionAllResource extends JsonResource
{
    public function toArray($request)
    {
         $data = parent::toArray($request);
        
        $result= [];
        if (isset($data)) {
                foreach ($data as $permission) {
                        if (isset($permission['name']) && $permission['name'] !== null) {
                            $result[] = $permission['name'];
                        }
                }
            
        }
       return $result;
       
    }

}
