<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CheckRoleAndPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // if (!auth()->user()->hasRole($role)) {
        //     abort(403, 'You do not have the required role.');
        // }

        // if (!auth()->user()->hasPermissionTo("$permission")) {
        //     abort(403, 'You do not have the required permission.');
        // }

        return $next($request);
    }
}
