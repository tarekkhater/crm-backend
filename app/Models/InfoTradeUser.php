<?php

namespace App\Models;

use App\Models\Scopes\InfoUserScope;
use App\Services\Users\UserWalletService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoTradeUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_id', 'branch_id', 'status_id', 'campaign_id', 'source_id',
        'balance', 'real_deposit', 'balance_yesterday', 'money', 'pnl', 'bonus', 'mup',
        'awaiting_deposit', 'dob', 'profit', 'fee', 'withdrawable', 'cur',
    ];

    public function getMupAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->attributes['fake'] ?? 0;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new InfoUserScope);

        static::saving(function (InfoTradeUser $model) {
            if ($model->isDirty(['real_deposit', 'bonus', 'mup'])) {
                UserWalletService::syncBalance($model);
            }
        });
    }

    protected $with = ['source'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branche::class, 'branch_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'cur');
    }
}
