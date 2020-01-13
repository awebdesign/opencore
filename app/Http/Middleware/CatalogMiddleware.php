<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Application;
use OpenCore\Support\Opencart\Startup;

class CatalogMiddleware
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
        /**
         * set language absed on OpenCart language session
         */
        // $locale = config('app.locale');

        // $session = Startup::getRegistry('session');
        // if(!empty($session->data['language'])) {
        //     $lang = explode('-', $session->data['language']);
        //     $locale = $lang[0];
        // }

        // $this->app->setLocale($locale);

        return $next($request);
    }
}
