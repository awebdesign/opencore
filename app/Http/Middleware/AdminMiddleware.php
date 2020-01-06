<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Application;
use OpenCore\Support\Opencart\Startup;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(Application $app, Request $request) {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        if(!defined('DIR_CATALOG')) {
            return abort(404);
        }

        $session = Startup::getRegistry('session');

        /** check if OpenCart admin token is present and if is valid */
        if(empty($session->data['token']) || empty($request->get('token')) || $session->data['token'] != $request->get('token')) {
            return redirect(basename(DIR_APPLICATION) . '/login');
        }

        return $next($request);
    }
}
