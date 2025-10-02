<?php

namespace App\Http\Controllers\admin\CRM\Api\Settings\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
class IndexController extends Controller
{
    public function index(Request $request){
        $tradingHourses = Currency::paginate(20);
        $this->setMessage("success");
        $this->setData($tradingHourses);
        return $this->sendApiResonse();
    }

    public function all(){
        $tradingHourses = Currency::get();
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

    public function store(Request $request) {
        // Validate the input fields
        $request->validate([
            'name' => 'required|string',
            'sign' => 'required|string',
            'code' => 'required|string|unique:currencies,code',
        ]);
    
        // Create a new currency
        $currency = Currency::create([
            'name' => $request->name,
            'sign' => $request->sign,
            'code' => $request->code,
        ]);
    
        // Set success message and return the newly created currency
        $this->setMessage("Currency created successfully");
        $this->setData($currency); // Returning the created currency
    
        return $this->sendApiResonse();
    }
    
}
