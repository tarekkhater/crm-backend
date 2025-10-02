<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Position;
class CheckBalanceUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = AuthApi();
        $user->load('userInfo');
        $total_trades = Position::where('close_at',null)->whereUserId($user->id)->sum('trade_amount');
        $bal =  $user->userInfo->balance;
        $free = $bal - $total_trades;
        return $free >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Current balance Trade.';
    }
}
