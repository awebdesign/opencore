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
        /**
         * set language absed on OpenCart language session
         */
        $locale = config('app.locale');

        $session = Startup::getRegistry('session');
        if(!empty($session->data['language'])) {
            $lang = explode('-', $session->data['language']);
            $locale = $lang[0];
        }

        $this->app->setLocale($locale);

        $loader = Startup::getRegistry('load');

        $header = $loader->controller('common/header');
        $column_left = $loader->controller('common/column_left');
        $footer = $loader->controller('common/footer');

        View::share('opencart_header', $header);
        View::share('opencart_column_left', $column_left);
        View::share('opencart_footer', $footer);

        return $next($request);
    }
}
