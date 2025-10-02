<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IBUser extends Model
{
    use HasFactory;

    protected $fillable= ['user_id','currency','balance',
    'comission_id','parent_id','offer_id'];


    public function user()
    {
        return $this->belongsTO(Admin::class,'user_id');
    }
}
