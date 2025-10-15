<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            switch ($guard) {
                case 'guardian':
                    if (Auth::guard($guard)->check()) {
                        return redirect(route('guardian.index'));
                    }
                    break;
                case 'admin':
                    if (Auth::guard($guard)->check()) {
                        return redirect(route('admin.index'));
                    }
                    break; 
                case 'ict':
                    if (Auth::guard($guard)->check()) {
                        return redirect(route('ict.index'));
                    }
                    break;         
                default:
                    if (Auth::guard($guard)->check()) {
                        return redirect('/dashboard');
                    }
                    break;
            }
        }

        return $next($request);
    }
}
