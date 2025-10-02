<?php
namespace App\Services\Permission;
use App\Models\Permission;
use Illuminate\Http\Request;
use DB;
class PermissionServices{
    public function all(Request $request){
        $data = [];
        $types = DB::table('permissions')
        ->groupBy('title')
        ->pluck('title');
        foreach($types as $type){
            if($type != null){
                $data[] = ['title'=>$type,"permission"=>Permission::select()->where('title',$type)->get()];
            }

        }
        // $data[] = ['title'=>"all","permission"=>Permission::get()];
        return $data;
    }
    
    public function allAgent(Request $request){
        $data = [];
        $types = DB::table('permissions')
        ->whereNotIn('title',['Roles','DEV Setting','Assets','Broker Account','Admins'])
        ->groupBy('title')
        ->pluck('title');
        foreach($types as $type){
            if($type != null){
                $data[] = ['title'=>$type,"permission"=>Permission::select()->where('title',$type)->get()];
            }

        }
        // $data[] = ['title'=>"all","permission"=>Permission::get()];
        return $data;
    }

    public function show($id){
        $Permission = Permission::find($id);
        return $Permission;
    }

    public function store($request){
        $Permission = Permission::create([
            'name' => $request->name
        ]);
    }
    public function update($request,$id){
        $Permission = Permission::find($id);
        $Permission->update([
            'name' => $request->name
        ]);
    }
    public function destroy($request){
        $Permission = Permission::find($id);
        $Permission->delete();
    }
}
