<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\cryptoPayment;
use Illuminate\Http\Request;

class CryptoPaymentControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('RoleMiddleware:view-settings_update-settings');
    }
    public function index()
    {
        $p_methods = cryptoPayment::all();
        return view('admin.p_methods.index', compact('p_methods'));
    }

    public function store(Request $request)
    {
        $data = $this->getData($request);
        cryptoPayment::create($data);
        return redirect()->route('admin.p_methods.index')->with('success', 'New Crypto Payment was successfully added.');
    }

    public function edit($id)
    {
        $currency_pair = cryptoPayment::findOrFail($id);
        return view('admin.p_methods.edit', compact('currency_pair'));
    }

    public function update(Request $request, $id)
    {
        $p_methods = cryptoPayment::findOrFail($id);
        $p_methods->update($this->getData($request));
        return redirect()->route('admin.p_methods.index');
    }

    public function destroy($id)
    {
        $p_methods = cryptoPayment::findOrFail($id);
        $p_methods->delete();
        return redirect()->back();
    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required',
            'symbol' => 'string|min:1|max:255|required',
            'barcode' => 'required',
            'logo' => 'nullable',
            'wallet' => 'required',
        ];
        return $request->validate($rules);
    }

}
