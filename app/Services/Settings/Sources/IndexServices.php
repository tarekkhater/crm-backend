<?php
namespace App\Services\Settings\Sources;
use App\Models\Source;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $sourcess = Source::all();
        return $sourcess;
    }

    public function show($id){
        $sources = Source::find($id);
        return $sources;
    }

    public function store($request){
        $sources = Source::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status
        ]);
        return $sources;
    }
    public function update($request,$id){
        $sources = Source::find($id);
        $sources->update([
            'name' => $request->name,
            'description' => $request->description,

            'status' => $request->status
        ]);
        $sources->syncPermissions($request->permission);

    }
    public function destroy($request){
		$sources = Source::findOrFail($request->id);
		$sources->delete();
		return true;
	}
}
