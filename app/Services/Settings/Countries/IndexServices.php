<?php
namespace App\Services\Settings\Countries;
use App\Models\Country;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $sourcess = Country::all();
        return $sourcess;
    }

    public function show($id){
        $sources = Country::find($id);
        return $sources;
    }

    public function store($request){
        $sources = Country::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }
    public function update($request,$id){
        $sources = Country::find($id);
        $sources->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
        $sources->syncPermissions($request->permission);

    }
    public function destroy($request){
        $sources = Country::find($id);
        $sources->delete();
    }
}
