<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProccessPayment extends Model
{
    use HasFactory;
    
    protected $fillable=['user_id',
            'type',
            'name',
            'value',
            'amount'];
}
