<?php
namespace App\Services\Settings\Payments;
use App\Models\PaymentGateWay;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $plans = PaymentGateWay::all();
        return $plans;
    }

    public function show($id){
        $plans = PaymentGateWay::find($id);
        return $plans;
    }

    public function store($request){
        $plans = PaymentGateWay::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }
    public function update($request,$id){
        $plans = PaymentGateWay::find($id);
        $plans->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

    }
    public function destroy($request){
        $plans = PaymentGateWay::find($id);
        $plans->delete();
    }
}
