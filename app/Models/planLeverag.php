<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class planLeverag extends Model
{
protected $table = 'plan_leverag';

    
  

   
    protected $fillable = [
                  'type',
                  'plan_id',
                  'leverag',
              ];


}
