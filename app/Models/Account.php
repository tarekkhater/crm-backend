<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type','wallet','address','wire_id'];

    protected $appends = ['name'];

    public function wire(){
        return $this->belongsTo(WireAccount::class);
    }

    public function getAddressAttribute($value){
        if(!$value){
            return 'Not applicable';
        }else{
            return $value;
        }
    }
    public function getNameAttribute(){
        if($this->type == 'crypto'){
            return "[ $this->wallet => $this->address ]";
        }
        if($this->type == 'wire'){
            $wire = WireAccount::select('account_name','account_number','id')->whereId($this->wire_id)->first();
            if($wire){
                return "$wire->account_name [ $wire->account_number ]";
            }else{
                return 'no wire account';
            }
        }
    }
}
