<?php

namespace App\Http\Middleware;

use App\Support\SanitaryAccess;
use Closure;

class SanitaryMaterialAccess
{
    public function handle($request, Closure $next)
    {
        if (!SanitaryAccess::canView($request->user())) {
            return redirect()->route('access.restricted');
        }

        return $next($request);
    }
}
