<?php
namespace App\Services\Settings\TypeUser;
use App\Models\TypeUser;
use Illuminate\Http\Request;
class TypeUsersServices{
    public function allactive(Request $request){
        $TypeUsers = TypeUser::where('status',1)->get();
        return $TypeUsers;
    }

    public function alldeactive(Request $request){
        $TypeUsers = TypeUser::where('status',0)->get();
        return $TypeUsers;
    }

    public function show($id){
        $TypeUser = TypeUser::find($id);
        return $TypeUser;
    }

    public function store($request){
        $TypeUser = TypeUser::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
    }
    public function update($request,$id){
        $TypeUser = TypeUser::find($id);
        $TypeUser->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
        $TypeUser->syncPermissions($request->permission);

    }
    public function destroy($request){
        $TypeUser = TypeUser::find($id);
        $TypeUser->delete();
    }
}