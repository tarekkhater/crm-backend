<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table= "role_has_permissions";
    public $guarded = [];

    public function Permission(){
        return $this->belongsTo(Permission::class,'permission_id');
    }
}