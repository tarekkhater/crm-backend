<?php
namespace App\Services\Settings\Plans;
use App\Models\Plan;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $plans = Plan::all();
        return $plans;
    }

    public function show($id){
        $plans = Plan::find($id);
        return $plans;
    }

    public function store($request){
        $plans = Plan::create([
            'name' => $request->name,
            'slug'=>$request->slug,
            'color'=>$request->color,
            'icon'=>$request->icon,
            'amount'=>$request->amount,
            'status' => $request->status
        ]);
    }
    public function update($request,$id){
        $plans = Plan::find($id);
        $plans->update([
            'name' => $request->name,
            'slug'=>$request->slug,
            'color'=>$request->color,
            'icon'=>$request->icon,
            'amount'=>$request->amount,
            'status' => $request->status
        ]);

    }
    public function destroy($request){
        $plans = Plan::find($id);
        $plans->delete();
    }
}
