<?php

namespace AwebCore\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
