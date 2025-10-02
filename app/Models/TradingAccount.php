<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingAccount extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','offer_id','branch_id','status'];
    public function user()
    {
        return $this->belongsTO(User::class,'user_id');
    }

    public function offer()
    {
        return $this->belongsTO(Package::class,'offer_id');
    }

    public function branch()
    {
        return $this->belongsTO(Branche::class,'branch_id');
    }
}
