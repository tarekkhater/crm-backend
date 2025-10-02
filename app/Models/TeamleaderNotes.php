<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamleaderNotes extends Model
{
    use HasFactory;

    protected $fillable=['agent_id','team_id','message'];



    public function teamleader(){
        return $this->belongsTo(Admin::class,'team_id');
    }

    public function agent(){
        return $this->belongsTo(Admin::class,'agent_id');
    }
}
