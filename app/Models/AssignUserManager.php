<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignUserManager extends Model
{
    use HasFactory;
    protected $fillable= ['user_id','admin_id'];



    public function User(){
        return $this->belongsTO(User::class,'user_id');
    }

    public function manager(){
        return $this->belongsTO(Admin::class,'admin_id');
    }
}
