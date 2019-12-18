<?php

namespace AwebCore\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use AwebCore\Startup;
class AdminPanel
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
        $loader = Startup::getRegistry('load');

        $header = $loader->controller('common/header');
        $column_left = $loader->controller('common/column_left');
        $footer = $loader->controller('common/footer');

        View::share('header', $header);
        View::share('column_left', $column_left);
        View::share('footer', $footer);

        return $next($request);
    }
}
