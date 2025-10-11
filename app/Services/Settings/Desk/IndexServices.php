<?php
namespace App\Services\Settings\Desk;
use App\Models\Desk;
use Illuminate\Http\Request;
class IndexServices{
    
    public function all(Request $request){
        $Deskss = Desk::all();
        return $Deskss;
    }

    public function show($id){
        $Desks = Desk::find($id);
        return $Desks;
    }

    public function store($request){
        $Desks = Desk::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        return $Desks;
    }
    public function update($request,$id){
        $Desks = Desk::find($id);
        $Desks->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
	return true;
    }
    public function destroy($request){
        return false;
		$Desks = Desk::findOrFail($request->id);
		$Desks->delete();
		return true;
	}
}
