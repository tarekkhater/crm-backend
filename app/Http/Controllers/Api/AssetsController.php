<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function validAssets(){
        $assets = CurrencyPair::inRandomOrder()->where('rate','!=',0.0000)->get();
        return response()->json($assets);
    }

      public function currentRate($sym, $base, $type){
        $rate = $this->getCurRate($sym,$base,$type);
        return response()->json($rate);
    }

      public function assetCurrentRate($id){
          $coin = (int) $id;
          $currency = CurrencyPair::where('id',$coin)->first();
          if($currency){
              $rate = $this->getCurRate($currency->sym,$currency->base,$currency->type);
              return response()->json($rate);
          }
          return response()->json($coin);
    }



    public function types(){
        $types = ['crypto','stocks','forex','indices','commodities'];
        return response()->json($types);
    }

    public function asset($id){
        $coin = (int) $id;
        $currency = CurrencyPair::where('id',$coin)->first();
        if(!$currency){
            $currency = [];
        }

        return response()->json($currency);
    }
}
