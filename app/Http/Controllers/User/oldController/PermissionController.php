<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(){
        $rows=Permission::get();
        return view('admin.permissions.index',compact('rows'))
          ;
    }
     public function store(Request $request){
        if(isset($request['permission'])){
             foreach ($request['permission'] as $key=> $permission){
                 Permission::where('id',$key)->update([
                     "description"=>$permission,
                     'display_name'=>$request['display_name'][$key]
                 ]);


             }
        }
         return redirect()->back()->with('success','Success update');
     }
}
