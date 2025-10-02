<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WireAccount extends Model
{
    use HasFactory;
    protected $fillable = ['user_id',
        'account_name',
        'account_number',
        'bank_country',
        'bank_currency',
        'bank_name',
        'bank_branch',
        'bank_address',
        'sort_code',
        'routine_number',
        'bank_software',
        'swift_code',
        'iban_number',
        'account_label',
    ];
}
