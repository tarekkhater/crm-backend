<?php

namespace App\Http\Resources\Admin\Permissions;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $result = [];

        if (isset($data)) {
            foreach ($data as $permission) {
                if (!empty($permission['path'])) {
                    $result[] = $permission['path'];
                } elseif (!empty($permission['name'])) {
                    $result[] = $permission['name'];
                }
            }
        }

        return array_values(array_unique($result));
    }

}
