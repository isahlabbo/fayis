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
        $hasFinancePermission = method_exists($user, 'hasPermission') && collect($financePermissions)->contains(fn ($permission) => $user->hasPermission($permission));

        if($user->status == 'Active' && (in_array($user->role, $allowedRoles, true) || $hasFinancePermission)){
            return $next($request);
        }

        return redirect()->route('access.restricted');
        
    }
}
