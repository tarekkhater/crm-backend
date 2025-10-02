<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvProfit extends Model
{
    use HasFactory;

    protected $appends = ['status_text'];

    protected $fillable = ['user_plan_id', 'user_id', 'amount', 'profit', 'status', 'trx', 'start_date', 'end_date','total_profit','period'];


    public function userPlan(){
        return $this->belongsTo(UserPlan::class,'user_plan_id');
    }

    public function getStatusTextAttribute(){
        $status = $this->status;
        if($status === 0){
            return 'pending';
        }
        if($status === 1){
            return 'running';
        }
        if($status === 2){
            return 'completed';
        }
    }

    public function plan(){
        return $this->userPlan()->first()->plan;
    }

}
