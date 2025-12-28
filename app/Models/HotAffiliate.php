<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotAffiliate extends Model
{
    use HasFactory;

    protected $table = 'hot_affiliates';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'count',
        'user_ip',
    ];
}
