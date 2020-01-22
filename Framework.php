<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore;

use Exception;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
require __DIR__ . '/vendor/autoload.php';

class Framework
{
    public static $instance;
    private $app;
    private $response;
    private $request = null;
    private $kernel;

    private $registry;
    public $route;
    public $output;
    public $routes_session;

    public const NOT_FOUND = 0;
    public const FOUND = 1;

    public function __construct()
    {
        $this->app = require __DIR__ . '/bootstrap/app.php';

        $this->kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);

        $this->app->singleton('OcLoader', function () {
            $OcLoader = new \OpenCore\Support\OcLoader();
            /**
             * set loaded true in order to know the request was done through OpenCart
             */
            $OcLoader->set('loaded', true);

            return $OcLoader;
        });
    }

    /**
     * Bootstrap Instance
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            $app = new self();
            self::$instance = $app;
        }
        return self::$instance;
    }

    public function initiate($registry, $route, &$output)
    {
        $this->registry =  $registry;
        $this->route =  $route;
        $this->output =  $output;

        $this->routes_session[] = $route;
    }

    /**
     * Retrieve Response
     */
    public function getResponse()
    {
        $this->response->sendHeaders();

        $content = $this->response;

        $this->kernel->terminate($this->request, $this->response);

        return $content;
    }

    public function getRegistry($type = null)
    {
        if ($type) {
            return $this->registry->get($type);
        }

        return $this->registry;
    }

    /**
     * Run Framework
     */
    public function run()
    {
        $this->response = $this->kernel->handle(
            $this->request = \Illuminate\Http\Request::capture()
        );

        $this->response->send();

        $this->kernel->terminate($this->request, $this->response);
    }

    /**
     * Handle Framework Response
     */
    public function handle()
    {
        $this->response = $this->kernel->handle($this->request);

        if ($this->response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            $this->response->send();

            return true;
        } else {
            return ($this->response->status() != '404') ? true : false;
        }
    }

    /**
     * Check Route function
     *
     * @return bool
     */
    public function checkRoute()
    {
        Framework::getInstance()->initiateRouteRequest();

        /**
         * TODO: find a better way to check all available routes
         */
        if (!$this->app->OcLoader->get('routes')) {
            $request = \Illuminate\Http\Request::capture();

            /**
             * Force returning an empty response
             */
            $this->app->OcLoader->flash('check-routes', true);

            $response = $this->kernel->handle($request);

            $this->kernel->terminate($request, $response);

            $this->app->OcLoader->set('routes', $this->app->router->getRoutes());

        }

        try {
            $routes = $this->app->OcLoader->get('routes');

            if ((bool) $routes->match($this->request)->uri()) {
                return self::FOUND;
            } else {
                return self::NOT_FOUND;
            }

        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return self::NOT_FOUND;
        }
    }

    /**
     * Initiate Route request function
     *
     * @param string $route
     * @param string &$output
     * @return void
     */
    public function initiateRouteRequest()
    {
        $this->request = \Illuminate\Http\Request::capture();

        /**
         * force admin route in case the request comes from admin side
         */
        $appBaseName = basename(DIR_APPLICATION) . '/';
        if (defined('HTTPS_CATALOG')) {
            $serverName = $this->request->server->get('SCRIPT_NAME');
            $this->request->server->set('SCRIPT_NAME', str_replace($appBaseName, '', $serverName));
        }

        /**
         * Change URI for partial loaded controllers like common/header, common/footer etc...
         * in order to be able to override them
         */
        if ($this->output !== false) {
            $this->request->server->set('REQUEST_URI', $this->route);
        }
    }
}
