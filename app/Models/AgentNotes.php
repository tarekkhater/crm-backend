<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentNotes extends Model
{
    use HasFactory;

    protected $fillable=['agent_id','user_id','content','message_by','status'];



    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function agent(){
        return $this->belongsTo(AgentUser::class,'agent_id');
    }
}
