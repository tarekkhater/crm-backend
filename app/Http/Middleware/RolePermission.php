<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class RolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $name)
    {
        $user = AuthApiAdmin();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $typeName = strtolower((string) optional($user->typeUser)->name);
        $isRootAdmin = (int) ($user->id ?? 0) === 1;
        $isAdminType = (int) ($user->type_id ?? 0) === 3 || $typeName === 'admin';
        $isSuperRole = $user->hasRole(['superadmin', 'super-admin', 'Admin', 'admin']);

        if ($isRootAdmin || $isAdminType || $isSuperRole) {
            return $next($request);
        }

        $requestedPermissions = array_filter(explode('_', (string) $name));
        $granted = false;

        foreach ($requestedPermissions as $permission) {
            foreach (array_unique([$permission, strtolower($permission)]) as $candidate) {
                try {
                    if ($user->hasPermissionTo($candidate, 'api')) {
                        $granted = true;
                        break 2;
                    }
                } catch (PermissionDoesNotExist $e) {
                    // Ignore unknown aliases and continue checking others.
                }
            }
        }

        if (!$granted) {
            abort(403, 'You do not have the required permission.');
        }


        return $next($request);
    }
}
