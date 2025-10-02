<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $table="documents";
    protected $guarded = [];
protected $fillable = ['id','title','value','note','status','modified_by','identity_id'];
    // public function getStatusAttribute($value)
    // {
    //     if($value == 1){
    //         return 'DONE';
    //     }else if ($value == 0){
    //         return 'NEW';
    //     } else {
    //         return 'REJECTED';
    //     }
    // }

    public function getValueAttribute($value)
    {
        if($value){
            return asset($value);
        }
        return null;
    }
    
     public function modified(){
        return $this->belongsTo(Admin::class,'modified_by');
    }
}
