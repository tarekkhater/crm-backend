<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "country" => $this->id,
            "phoneNumber" => $this->phone,
            "wallet" => 0,
            "estimatedBalance" =>  $this->balance,
            "language" => "English",
            "currency" =>  $this->cur,
            "typeChart" => "default",
            "img" => $this->avatar,
            "notify" => [],
            "address" => $this->address,

            "verify" => $this->email_verified_at ? true : false,
            "isManager" => $this->hasRole(['manager']),
            "isRetention" => $this->hasRole(['retention']),
            "isAdmin" => $this->hasRole('admin'),
            "reset_token" => "",
            "bankDetails" => [],
            "cryptocurrencyWallet" => [],
            "isPendingVerification" => false,
            "myDeposits" => $this->deposits,
            "isSubscribed" => false,
            "subcriptionPlan" => $this->plans,
            "myWitdrawals" => $this->myWithdrawals,
            "myPaymentCard" => [],

            "notificationsEnabled" => true,
            "autoTrade" => false,
            "isTrading" => $this->can_trade ? true : false,
            "isActive" => $this->isActive,
            "liveTrade" => false,
            "stopUserAutoTrade" => false,
            "equity" => 0,
            "freeMargin" => 0,
            "margin" => 0,
            "bonus" => $this->bonus,
            "deposit" => $this->totalDeposit(),
            "profit" => $this->profit,
            "loss" => 0,
            "isOnline" => $this->online ? true : false,

            "_id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "newPassword" => $this->password,
            "lastname" => $this->lastname,
            "time" => $this->created_at,
            "lastAutoTradeDate" => "2021-11-28T19 =>22 =>53.043Z",
        ];
    }
}
