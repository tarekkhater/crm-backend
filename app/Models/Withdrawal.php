<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

//     protected $appends = ['commission_fee','tax_fee','cot_fee'];
//     protected $fillable = [
//         'user_id','processed','commission','tax','cost_of_transfer','commission_proof','tax_proof','cot_proof',
// 'amount','note',
// 'wallet',
// 'status','account_id',
// 'method',
// 'approved','type'
//     ];

//     public function user()
//     {
//         return $this->belongsTo('App\Models\User','user_id');
//     }
//     public function account()
//     {
//         return $this->belongsTo(Account::class,'account_id');
//     }
//     public function getCommissionFeeAttribute(){
//         $amount = ($this->amount * setting('withdrawal_commission', 20)) / 100;
//         return '$'.$amount;
//     }
//     public function getTaxFeeAttribute(){
//         $amount = ($this->amount * setting('withdrawal_tax', 20)) / 100;
//         return '$'.$amount;
//     }
//     public function getNoteAttribute($value){
//         if(!$value){
//             if(!$this->processed){
//                 return 'processing withdrawal';
//             }elseif (!$this->approved){
//                 return 'awaiting approval';
//             }else{
//                 return 'approved';
//             }
//         }else{
//             return $value;
//         }
//     }
//     public function getCotFeeAttribute(){
//         $amount = ($this->amount * setting('withdrawal_cot', 20)) / 100;
//         return '$'.$amount;
//     }

protected $fillable = [
    'user_id',
    'plan_id',
    'wire_id',
    'type',
    'amount',
    'currency',
    'proof',
    'promo_code',
    'message',
    'status',
    'payment_method',
    'created_at',
    'country'
];

protected $dates = [];

protected $casts = [];
protected $with = ['wire','wallet'];

    public function wire(){
        return $this->belongsTo(WireAccount::class,'wire_id');
    } 
    
    public function wallet(){
        
        return $this->belongsTo(ProccessPayment::class,'wire_id');
    }
    
      public function getProofAttribute($value)
    {
        if($value){
            return asset($value);
        }
        return null;
    }

public function user()
{
return $this->belongsTo('App\Models\User','user_id');
}

public function amount()
{
return $this->amount . ' '. setting('currency');
}

public function plan()
{
return $this->belongsTo(Package::class,'plan_id');
}

public function account()
{
return $this->belongsTo('App\Models\Account','account_id');
}
}
