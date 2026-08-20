<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleOrPermissionMiddleware
{
    /** Allow the legacy role or any supplied database permission during migration. */
    public function handle(Request $request, Closure $next, $legacyRole, ...$permissions)
    {
        $user = $request->user();
        if ($user && $user->status === 'Active') {
            if ($user->role === $legacyRole) {
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
