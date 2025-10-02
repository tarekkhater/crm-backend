<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    // Table name (optional if using Laravel naming conventions)
    protected $table = 'leads';

    // Mass assignable fields
    protected $fillable = [
        'lead_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'registration_date',
        'last_comment_date',
        'last_comment',
        'stage_manager',
        'total_deposit',
        'amount',
        'balance',
        'country',
        'account_type',
        'lead_status',
        'reten_status',
        'ftd_date',
        'deposit_count',
        'lead_source',
        'type'
    ];

    // Casts for automatic type conversion
    protected $casts = [
        'registration_date' => 'date',
        'last_comment_date' => 'date',
        'ftd_date' => 'date',
        'total_deposit' => 'decimal:2',
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'deposit_count' => 'integer',
    ];
}
