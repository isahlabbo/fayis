<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class AdmissionOfficerMiddleware
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

        $hasRole = $user && (method_exists($user, 'usesRole') ? $user->usesRole('admission_officer') : $user->role === 'admission_officer');
        $hasPermission = $user && (method_exists($user, 'hasAnyPermission')
            ? $user->hasAnyPermission('manage-admissions', 'manage-students')
            : ($user->hasPermission('manage-admissions') || $user->hasPermission('manage-students')));

        if($user && $user->status == 'Active' && ($hasRole || $hasPermission)){
            return $next($request);
        }

        return redirect()->route('access.restricted');
    }
}
