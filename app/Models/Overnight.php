<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overnight extends Model
{
    use HasFactory;

    protected $fillable = ['trade_id','com','fee','user_id','amount','charged_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
