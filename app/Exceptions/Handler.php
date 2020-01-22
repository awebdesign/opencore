<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Config;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            /**
             * Ignore 404 Error in case of route check
             */

            if (app('OcLoader')->get('check-routes')) {
                /* disable debug bar */
                $debugBar = (Config::get('app.debug') && \App::environment('local'));
                if ($debugBar) {
                    app('debugbar')->disable();
                }
                /* return blank response */
                $response = response('');

                /* re-enable debug but for next response */
                if ($debugBar) {
                    app('debugbar')->enable();
                }
                return $response;
            }
        }

        if (app('OcLoader')->get('loaded') && Config::get('app.debug') && Config::get('opencore.debug_opencart')) {
            /**
             * In case of an error throw by OpenCart we will clear the output and display just the error itself
             * Also we will delete the last route_session from cache
             */

            $cache = \OpenCore\Framework::getInstance()->getRegistry('cache');

            $appBaseName = basename(DIR_APPLICATION);
            $allowed_routes = $cache->get('opencore_routes.' . $appBaseName);

            foreach(\OpenCore\Framework::getInstance()->routes_session as $route_session) {
                if(isset($allowed_routes[$route_session])) {
                    unset($allowed_routes[$route_session]);
                }
            }
            $cache->set('opencore_routes.' . $appBaseName, $allowed_routes, time() + 3600);

            ob_clean();
        }

        return parent::render($request, $exception);
    }
}
