<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model
{
    use HasFactory;
    protected $fillable=['email','user_id','type','status','code','code_timer','code_request'];
}
