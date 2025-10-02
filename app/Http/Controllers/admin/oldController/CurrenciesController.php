<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrenciesController extends Controller
{
    public function index()
    {

        $currencies = Currency::all();
        return view('admin.currencies.currencies-list', compact('currencies'));
    }

    public function create()
    {
        return view('admin.currencies.currencies-list');
    }


    public function store(Request $request)
    {
        $data = $this->getData($request);
        Currency::create($data);
        return redirect()->route('admin.currencies.index')->with('success', 'Currency was successfully added.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('admin.currencies.currencies-edit', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        $currencies = Currency::findOrFail($id);
        $currencies->update($this->getData($request));
        return redirect()->route('admin.currencies.index');
    }

    public function destroy($id)
    {
        $currencies = Currency::findOrFail($id);
        $currencies->delete();
        return redirect()->back();
    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required',
            'sign' => 'string|min:1|required',
            'code' => 'string|nullable',
        ];
        return $request->validate($rules);
    }
}
