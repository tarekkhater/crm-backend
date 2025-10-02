<?php
namespace App\Services\Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
class RolesServices{
    public function paginate(Request $request){
        $roles = Role::where('status','1')->paginate(15);
        if(isset($request->search) && $request->search != ''){
            $roles = Role::where('name','LIKE','%'.$request->search.'%')->where('status','1')->paginate(15);
        }
        // /$roles->load(["PermissionRole","PermissionRole.Permission"]);
        $data = [];
        foreach ($roles as $role){
            $getrole = $role;
            $getrole->permissions = $role->permissions;
            $data[] = $getrole;
        }

        return $roles;
    }

    public function all(){
        $roles = Role::where('status',1)->where('status','1')->get();
        return $roles;
    }

    public function show($id){
        $role = Role::find($id);
        $permissions = $role->permissions;
        return $role;
    }

    public function store($request){

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api',
            'display_name' => $request->name,
            'description' => $request->name
        ]);
        if(isset($request->status)){
            $role->status = 0;
            $role->save();
        }
        foreach($request->permissions as $permission){
            if($permission != null){
                // if($permission['id'] != 0  && $permission['value'] != false){
                    // PermissionRole::create([
                    //     'permission_id'=>$permission,
                    //     'role_id'=>$role->id,
                    // ]);
                    $gpermission = Permission::find($permission);
                    $role->givePermissionTo($gpermission->name);
                // }
            }
        }
        return $role;
    }
    public function update($request,$id){
        $role = Role::find($id);
        // $role->update([
        //     'name' => $request->name
        // ]);
        if(count($request->permissions) > 0){
            PermissionRole::where('role_id',$id)->delete();
            foreach($request->permissions as $permission){
                if($permission != null){
                    
                        if($permission != null){
                    $gpermission = Permission::find($permission);
                    $role->givePermissionTo($gpermission->name);
            }
                    }
                // }

            }
        }
        return $role;
    }
    public function destroy($request){
        $role = Role::find($request->id);
        $role->delete();
    }
}