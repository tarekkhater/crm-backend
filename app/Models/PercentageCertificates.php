<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageCertificates extends Model
{
    use HasFactory;

    protected $table = "percentage_certificates";
  

    protected $fillable = ['percentage','certificate_id','month','status','profit','total'];
   

}
