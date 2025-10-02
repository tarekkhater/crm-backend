<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoProfitLoss extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','status','amount','interval','interval_type','record','fixed','pnl','last_updated'];

    protected $appends = ['inter'];

    public function getInterAttribute(){
        $interval = $this->interval;
        if($this->interval_type == 'month'){
            $interval = $this->interval * 30;
        }
        if($this->interval_type == 'week'){
            $interval = $this->interval * 7;
        }
        if($this->interval_type == 'day'){
            $interval = $this->interval * 1;
        }
        if($this->interval_type == 'hour'){
            $interval = $this->interval * 1;
        }
        return $interval;
    }


}
