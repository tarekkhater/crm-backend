<?php

namespace App\Models;
use Laratrust\Models\Role as LaraTruestRole;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
// class Role extends LaraTruestRole
{
    protected $guarded = [];
    public function PermissionRole(){
        return $this->hasMany(PermissionRole::class,'role_id');
    }
}
