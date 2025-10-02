<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\cryptoPayment;
use Illuminate\Http\Request;

class CryptoPaymentController extends Controller
{
    public function index()
    {
        $p_methods = cryptoPayment::all();
        return $this->successResponse('Crypto Payments Fetched', $p_methods);
    }

    public function store(Request $request)
    {
        $data = $this->getData($request);
        $p_method = cryptoPayment::create($data);
        return $this->successResponse('Crypto Payment Added', $p_method, 201);
    }

    public function show($id)
    {
        $pp = cryptoPayment::findOrFail($id);
        return $this->successResponse('Crypto Payment Fetched!', $p_method);
    }

    public function update(Request $request, $id)
    {
        $p_method = cryptoPayment::findOrFail($id);
        $p_method->update($this->getData($request));
        return $this->successResponse('Crypto Payment Method Updated Successfully', $p_method);
    }

    public function destroy($id)
    {
        $p_methods = cryptoPayment::findOrFail($id);
        $p_methods->delete();
        return $this->successResponse('Crypto Payment Method Deleted Successfully');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required',
            'symbol' => 'string|min:1|max:255|required',
            'barcode' => 'required',
            'logo' => 'nullable',
            'wallet' => 'required|unique:crypto_payments',
        ];
        return $request->validate($rules);
    }

}
