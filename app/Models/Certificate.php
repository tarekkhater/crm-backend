<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Certificate extends Model
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
    protected $table = 'certificates';


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
                  'icon',
                  'color',
                  'amount',
                  'status',
                  'features'
              ];

    protected $casts = [
                'features' => 'array'
            ];

    public function getFeaturesAttribute() {
        return json_decode($this->attributes['features']) ?? [];
    }
}
