<?php
namespace App\Services\Settings\AssetType;
use App\Models\AssetType;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $sourcess = AssetType::all();
        return $sourcess;
    }

    public function show($id){
        $sources = AssetType::find($id);
        return $sources;
    }

    public function store($request){
        $sources = AssetType::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status
        ]);
        return $sources;
    }
    public function update($request,$id){
        $sources = AssetType::find($id);
        $sources->update([
            'amount' => $request->amount,
            'status' => $request->status
        ]);
        // $sources->syncPermissions($request->permission);
	return true;
    }
    public function destroy($request){
        return false;
		$sources = AssetType::findOrFail($request->id);
		$sources->delete();
		return true;
	}
}
