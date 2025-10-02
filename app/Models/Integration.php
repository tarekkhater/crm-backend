<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Integration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','number_leads','token','expired_at','expired'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
