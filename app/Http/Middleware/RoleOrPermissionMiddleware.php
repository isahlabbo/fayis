<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleOrPermissionMiddleware
{
    /** Allow a database/legacy role or any supplied database permission. */
    public function handle(Request $request, Closure $next, $role, ...$permissions)
    {
        $user = $request->user();
        if ($user && $user->status === 'Active') {
            if ($user->usesRole($role)) {
                return $next($request);
            }
            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('access.restricted');
    }
}
