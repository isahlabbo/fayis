<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaymentAuthorizationTimeMiddleware
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
        // Get the current time in 24-hour format
        $current_time = date('H:i');

        // Define the start and end times
        $start_time = '08:00';
        $end_time = '20:00';

        // Check if the current time is within the specified range
        if ($current_time >= $start_time && $current_time <= $end_time) {
            // Execute your code here
            return $next($request);
        } else {
            return redirect()->route('ict.index')->withWarning('The payment closed, it is only open from 8:00am to 8:00pm every day');
        }

        
    }
}
