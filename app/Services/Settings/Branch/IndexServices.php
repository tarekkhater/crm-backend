<?php
namespace App\Services\Settings\Branch;
use App\Models\Branche;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $plans =  Branche::all();
        return $plans;
    }

    public function show($id){
        $plans =  Branche::find($id);
        return $plans;
    }

    public function store($request){
        $plans =  Branche::create([
            'name' => $request->name,
            'slug'=>$request->slug,
            'color'=>$request->color,
            'icon'=>$request->icon,
            'amount'=>$request->amount,
            'status' => $request->status
        ]);
    }
    public function update($request,$id){
        $plans =  Branche::find($id);
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
        $plans =  Branche::find($id);
        $plans->delete();
    }
}
