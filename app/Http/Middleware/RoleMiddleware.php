<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /** Require at least one supplied database-backed or legacy role. */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if ($user && $user->status === 'Active' && $user->hasAnyAccessRole($roles)) {
            return $next($request);
        }

        return redirect()->route('access.restricted');
    }
}
