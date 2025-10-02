<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    protected $with = ['plan'];

    protected $appends = ['status_text'];

    protected $fillable = ['user_id','plan_id','amount','start_date','end_date','status','units','earned','profit'];

    public function plan(){
        return $this->belongsTo(Package::class,'plan_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getStatusTextAttribute(){
        $status = $this->status;
        if($status === 0){
            return 'pending';
        }
        if($status === 1){
            return 'approved';
        }
        if($status === 2){
            return 'completed';
        }
    }
}
