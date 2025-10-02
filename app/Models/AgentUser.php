<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentUser extends Model
{
    use HasFactory;

    protected $fillable=['agent_id','user_id','status_id','agent_type','status'];

    public function agent(){
       return  $this->belongsTo(Admin::class,'agent_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function notes(){
        return $this->hasMany(AgentNotes::class,'agent_id');
    }
}
