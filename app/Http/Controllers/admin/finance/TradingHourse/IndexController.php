<?php

namespace App\Http\Controllers\admin\finance\TradingHourse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CurrencyPair;
class IndexController extends Controller
{
    public function index(Request $request){
        $tradingHourses = CurrencyPair::paginate(20);
        $this->setMessage("success");
        $this->setData($tradingHourses);
        return $this->sendApiResonse();
    }



    public function status(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:CurrencyPair,id'
        ]);
        Deposit::find($request->id)->update([
            'disabled'=>1
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        $request->validate([

        ]);

        Deposit::create([

        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'days' => 'required',
            'open_at' => 'required',
            'close_at' => 'required',
            ]);
        $currency_pair = CurrencyPair::find($id);
        $days=json_encode($request['days']);

        $currency_pair->update([
            "days"=>$days,
            "open_at"=>$request['open_at'],
            "close_at"=>$request['close_at']
        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
