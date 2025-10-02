<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\WireAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function store(Request $request)
    {
            $data = $this->getData($request);
            $account = Account::create($data);
            Session::flash('success', 'Account successfully added!');
            $data['status'] = 1;
            $data['data'] = $account;
            return response()->json($data);
    }

    public function wireAccountStore(Request $request)
    {
            $data = $this->getWData($request);
            $account = WireAccount::create($data);
            Session::flash('success', 'Wire Account successfully added!');
            $data['status'] = 1;
            $data['data'] = $account;
            return response()->json($data);
    }

    protected function getData(Request $request)
    {
        $rules = [
            'type' => 'string|min:1|required',
            'wallet' => 'string|nullable',
            'address' => 'string|nullable',
            'wire_id' => 'integer|nullable',
        ];

        $data = $request->validate($rules);

        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getWData(Request $request)
    {
        $rules = [
            'account_name' => 'string|min:1|max:255|required',
            'account_number' => 'string|min:1|max:1000|required',
            'bank_country' => 'string|min:1|required',
            'bank_currency' => 'string|min:1|required',
            'bank_name' => 'string|min:1|required',
            'bank_branch' => 'string|min:1|required',
            'bank_address' => 'string|min:1|required',
            'sort_code' => 'string|min:1|required',
            'routine_number'=> 'string|min:1|required',
            'swift_code' => 'string|min:1|required',
            'iban_number' => 'string|min:1|required',
            'account_label' => 'string|nullable',
        ];
        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
        return $data;
    }


}
