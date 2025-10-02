<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $guarded = [];
    protected $table ='role_user';
    protected $fillable =['user_id','role_id','user_type','updated_at','created_at'];

    public function role(){
        return $this->belongsTo(Role::class,'role_id');
    }
}
