<?php

namespace App\Http\Resources\Admin\Permissions;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
         $data = parent::toArray($request);
        
        $result= [];
        if (isset($data)) {
                foreach ($data as $permission) {
                    
                        if (isset($permission['path']) && $permission['path'] !== null) {
                            $result[] = $permission['path'];
                        }
                    
                }
            
        }
       return $result;
       
    }

}
