<?php

namespace App\Models;

use App\Models\Scopes\InfoUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoTradeUser extends Model
{
    use HasFactory;
    protected $fillable =['user_id','plan_id','branch_id','status_id','campaign_id','source_id','balance','money','pnl','bonus','dob','profit','fee','withdrawable','cur'];





    protected static function booted(): void
    {
        static::addGlobalScope(new InfoUserScope);
    }

    protected $with= ['source'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function plan(){
        return $this->belongsTo(Plan::class,'plan_id');
    }

    public function branch(){
        return $this->belongsTo(Branche::class,'branch_id');
    }

    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }

    public function source(){
        return $this->belongsTo(Source::class,'source_id');
    }
    
    public function campaign(){
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
    
     public function currency(){
        return $this->belongsTo(Currency::class,'cur');
    }



}
