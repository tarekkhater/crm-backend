<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{

    use Sluggable;
//    use SoftDeletes;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';

    protected $appends = ['full_price','plan_period'];

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
                  'description',
                  'percent_profit',
                  'period',
                  'period_type',
                  'price',
                  'min_unit',
                  'max_unit',
                  'status'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $casts = [];

    public function getPlanPeriodAttribute(){
        return $this->period.' '.$this->period_type;
    }
    public function getFullPriceAttribute(){
        return  $this->min_unit * $this->price.' - '.$this->max_unit * $this->price;
    }

    public function totalReturn(){
        return $this->period * $this->percent_profit;
    }


}
