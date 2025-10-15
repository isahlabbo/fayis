<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(strstr($request->getPathInfo(), '/guardian')){
                $route = 'guardian.login';
            }elseif(strstr($request->getPathInfo(), '/admin')){
                $route = 'admin.login';
            }elseif(strstr($request->getPathInfo(), '/ict')){
                $route = 'ict.login';
            }else{
                $route = 'login';
            } 
            
            return route($route);

        }
    }
}
