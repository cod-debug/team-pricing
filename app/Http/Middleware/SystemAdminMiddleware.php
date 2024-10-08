<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SystemAdminMiddleware
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
        if(!Auth::user()->user_type == 1){
            return redirect(RouteServiceProvider::HOME);
        } 

        return $next($request);
    }
}
