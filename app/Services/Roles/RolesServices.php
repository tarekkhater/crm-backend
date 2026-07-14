<?php
namespace App\Services\Roles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesServices
{
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

    public function store($request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api',
            'display_name' => $request->name,
            'description' => $request->name,
        ]);
        if (isset($request->status)) {
            $role->status = 0;
            $role->save();
        }

        $permissionIds = collect($request->permissions)->filter()->values();
        if ($permissionIds->isNotEmpty()) {
            $permissions = Permission::query()->whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return $role;
    }

    public function update($request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return null;
        }

        $permissionIds = collect($request->permissions ?? [])->filter()->values();
        if ($permissionIds->isEmpty()) {
            return $role;
        }

        $permissions = Permission::query()->whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);

        // Agents created via TeamLeader get a dedicated Role row per user; `roles.type` holds the template role name.
        if (Schema::hasColumn('roles', 'type')) {
            $cloneRoles = Role::query()
                ->where('type', $role->name)
                ->where('id', '!=', $role->id)
                ->get();

            foreach ($cloneRoles as $cloneRole) {
                $cloneRole->syncPermissions($permissions);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $role;
    }
    public function destroy($request){
        $role = Role::find($request->id);
        $role->delete();
    }
}