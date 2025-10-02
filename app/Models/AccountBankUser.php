<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBankUser extends Model
{
    use HasFactory;
    protected $fillable=[ 'card_holder',
    'card_number',
    'card_cvv',
    'card_expiry_month',
    'card_expiry_year',
    'billing_address',
    'zip_code',
    'state',];

}
