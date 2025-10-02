<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'sign',
                  'code'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];



    public function getNameAttribute($value){
        if($this->code == 'USD'){
            return 'United States Dollar';
        }else {
            return $value;
        }
    }

}
