<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;

class CurrencyPairController extends Controller
{
    public function __construct()
    {
        $this->middleware('RoleMiddleware:view-assets_create-currency-pair_update-currency-pair_delete-currency-pair_update-leverage_update-buy_update-sell')
            ;
        $this->middleware('RoleMiddleware:view-assets')
           ->only('index') ;

    }

    public function types(){
        return [
            'crypto','stocks','forex','indices','commodities'
        ];
    }
    public function index()
    {
       // dd("hello");
        $types = $this->types();
        $currency_pairs = CurrencyPair::all();

        return view('admin.currencies.index', compact('currency_pairs','types'));
    }
     public function leverage_update(Request $request){
         $currency_pairs = CurrencyPair::all();
         foreach ($currency_pairs as $item){
             $item->update([
                 "leverage"=>$request['id']
             ]);
         }
          return back();

     }
     public function sell_update(Request $request){
         $currency_pairs = CurrencyPair::all();
         foreach ($currency_pairs as $item){
             $item->update([
                 "sell_spread"=>$request['id']
             ]);
         }
          return back();

     }
     public function buy_update(Request $request){
         $currency_pairs = CurrencyPair::all();
         foreach ($currency_pairs as $item){
             $item->update([
                 "buy_spread"=>$request['id']
             ]);
         }
          return back();

     }

    public function store(Request $request)
    {
        $data = $this->getData($request);
        CurrencyPair::create($data);
        return redirect()->route('admin.currencies.index')->with('success', 'CurrencyPair was successfully added.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $types = $this->types();
        $currency_pair = CurrencyPair::findOrFail($id);
        return view('admin.currencies.edit', compact('currency_pair','types'));
    }

    public function update(Request $request, $id)
    {
        $currency_pairs = CurrencyPair::findOrFail($id);
        $currency_pairs->update($this->getData($request));
        return redirect()->route('admin.currencies.index');
    }

    public function destroy($id)
    {
        $currency_pairs = CurrencyPair::findOrFail($id);
        $currency_pairs->delete();
        return redirect()->back();
    }



    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required',
            'sym' => 'string|min:1|max:255|required',
            'ex_sym' => 'string|min:1|max:255|required',
            'type' => 'string|min:1|max:255|required',
            'image' => 'nullable',
            'base' => 'nullable',
            'leverage' => 'nullable',
            'com' => 'nullable',
            'buy_spread' => 'nullable',
            'sell_spread' => 'nullable',
            'disabled' => 'nullable',
        ];
        $data = $request->validate($rules);
        $data['rate'] = $this->getCurRate($data['sym'], $data['base'], $data['type']);
        return $data;
    }

}
