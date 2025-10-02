<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    use HasFactory;

    protected $with = ['certificate','history'];

    protected $appends = ['status_text'];

    protected $fillable = ['user_id','certificates_id','amount','start_date','end_date','status','profit','duration','period'];
    public function certificate(){
        return $this->belongsTo(Certificate::class,'certificates_id');
    }
    
    public function history(){
        
         return $this->hasMany(PercentageCertificates::class,'certificate_id');
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
        if($status === 3){
            return 'Rejected';
        }
    }
}
