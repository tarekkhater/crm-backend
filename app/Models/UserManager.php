<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManager extends Model
{
    use HasFactory;
    protected $fillable=['type','admin_id','user_id'];
    protected $hidden = ['updated_at'];
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }

    public function user(){
        return $this->belongsTo(Admin::class,'user_id');
    }
}
