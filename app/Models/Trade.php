<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stephenjude\DefaultModelSorting\Traits\DefaultOrderBy;

class Trade extends Model
{
    use HasFactory, DefaultOrderBy;

    protected static $orderByColumn = 'created_at';
    protected $fillable=['leverage','opening_price'];
    protected $casts = [
        'status' => 'integer',
        'result' => 'integer',
        'traded_amount' => 'float',
        'profit' => 'float',
        'take_profit' => 'float',
        'stop_loss' => 'float',
        'paid_com' => 'float',
        'opening_price' => 'float',
        'closing_price' => 'float',
        'qty' => 'float',
        'is_stop_loss' => 'boolean',
        'by_admin' => 'boolean',
        'is_take_profit' => 'boolean',
        'pending_order'=>'float'
    ];
//    protected $dates = ['close_at'];

    protected $guarded = ['coin_id'];

    protected $with = ['user','currency','asset'];

    protected $appends = ['end_time','qty','open_at','amount'];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function currency()
    {
        return $this->belongsTo(CurrencyPair::class,'currency_pair','ex_sym');
    }

    public function asset()
    {
        return $this->belongsTo(CurrencyPair::class,'currency_pair','ex_sym');
    }


    public function getProfitAttribute($value)
    {
        return $value - $this->paid_com;
    }

    public function getAmountAttribute()
    {
        return $this->traded_amount * $this->leverage;
    }

    public function getOpenAtAttribute()
    {
        return $this->created_at->format('d-m-Y H:i:s');
    }

    // public function getCloseAtAttribute()
    // {
    //     return $this->close_at->format('d-m-Y H:i:s');
    // }


    public function getCloseAtAttribute($value)
    {
        if($value){
            return Carbon::parse($value)->format('d-m-Y H:i:s');
        }else{
            return null;
        }
    }

    public function getQtyAttribute()
    {
        if($this->opening_price > 0){
            return (float)number_format($this->amount / $this->opening_price,3);
        }else {
            return 0.000;
        }
    }

    public function getEndTimeAttribute()
    {
        return $this->created_at->addSeconds($this->duration);
    }

}
