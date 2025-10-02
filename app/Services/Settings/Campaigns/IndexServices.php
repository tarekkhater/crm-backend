<?php
namespace App\Services\Settings\Campaigns;
use App\Models\Campaign;
use Illuminate\Http\Request;
class IndexServices{
    public function all(Request $request){
        $campaigns = Campaign::all();
        return $campaigns;
    }

    public function show($id){
        $campaigns = Campaign::find($id);
        return $campaigns;
    }

    public function store($request){
        $campaigns = Campaign::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status
        ]);
        return $campaigns;
    }
    public function update($request,$id){
        $campaigns = Campaign::find($id);
        $campaigns->update([
            'name' => $request->name,
            'description' => $request->description,

            'status' => $request->status
        ]);
        $campaigns->syncPermissions($request->permission);

    }
    public function destroy($request){
		$campaigns = Campaign::findOrFail($request->id);
		$campaigns->delete();
		return true;
	}
}
