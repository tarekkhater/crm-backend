<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = 'user';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    protected $appends = ['status'];

    public function getStatusAttribute(){
        if($this->type == 'deposit'){
            return 1;
        }else{
            return 0;
        }
    }
    
}
