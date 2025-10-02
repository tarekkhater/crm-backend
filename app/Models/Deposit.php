<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'deposits';

 

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
                  'user_id',
                  'plan_id',
                  'amount',
                  'currency',
                  'proof',
                  'promo_code',
                  'message',
                  'status',
                  'payment_method',
                  'created_at',
                  'country',
                  'payment_id',
                  'state',
                  'type'
              ];

    protected $dates = [];

    protected $casts = [];
      protected $with = ['wire','payment','wallet'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function amount()
    {
        return $this->amount . ' '. setting('currency');
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }

    public function plan()
    {
        return $this->belongsTo(Package::class,'plan_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account','account_id');
    }
    
    public function wire(){
        return $this->belongsTo(WireAccount::class,'payment_id');
    } 
    
    public function wallet(){
        
        return $this->belongsTo(ProccessPayment::class,'payment_id');
    }

    public function getProofAttribute($value)
    {
        if($value){
            return asset($value);
        }
        return null;
    }

}
