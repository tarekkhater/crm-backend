<?php

namespace App\Http\Controllers\User\AccountBank;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AccountBank\StoreAccountBankRequest;
use App\Http\Resources\AccountBankUserResource;
use App\Models\AccountBankUser;

class IndexController extends Controller
{
    public function index()
    {
        $user = AuthApi();

        $cards = AccountBankUser::where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        $this->setData(AccountBankUserResource::collection($cards));
        $this->setMessage('success');

        return $this->sendApiResonse();
    }

    public function store(StoreAccountBankRequest $request)
    {
        $user = AuthApi();

        $card = AccountBankUser::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'card_holder' => $request->card_holder,
            'card_number' => $request->card_number,
            'card_cvv' => $request->card_cvv,
            'card_expiry_month' => $request->card_expiry_month,
            'card_expiry_year' => $request->card_expiry_year,
            'billing_address' => $request->billing_address,
            'zip_code' => $request->zip_code,
            'state' => $request->state,
        ]);

        $this->setData(new AccountBankUserResource($card));
        $this->setMessage('success');

        return $this->sendApiResonse();
    }
}
