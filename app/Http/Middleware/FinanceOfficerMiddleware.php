<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class FinanceOfficerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $allowedRoles = ['finance_officer', 'patron'];

        $financePermissions = ['manage-inventory', 'manage-payments', 'manage-sales', 'manage-rents'];
        $hasFinancePermission = $user && (method_exists($user, 'hasAnyPermission')
            ? $user->hasAnyPermission($financePermissions)
            : collect($financePermissions)->contains(fn ($permission) => $user->hasPermission($permission)));
        $hasFinanceRole = $user && (method_exists($user, 'hasAnyAccessRole')
            ? $user->hasAnyAccessRole($allowedRoles)
            : in_array($user->role, $allowedRoles, true));

        if($user && $user->status == 'Active' && ($hasFinanceRole || $hasFinancePermission)){
            return $next($request);
        }

        return redirect()->route('access.restricted');
        
    }
}
