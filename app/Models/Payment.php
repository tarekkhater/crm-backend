<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table= "payment_users";
    protected $fillable=['user_id','name','iban','code','date','cvc','amount','zib_code','status'];

}
