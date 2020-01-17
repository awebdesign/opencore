<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore;

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
    private $request;
    private $kernel;
    private $registry;
    private $routes_checked = [];

    public function __construct()
    {
        $this->app = require __DIR__ . '/bootstrap/app.php';
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

    /**
     * Retrieve Response
     */
    public function getResponse()
    {
        $this->response->sendHeaders();

        $content = $this->response->getContent();

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
        $this->kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);

        $this->response = $this->kernel->handle(
            $this->request = \Illuminate\Http\Request::capture()
        );

        $this->response->send();

        $this->kernel->terminate($this->request, $this->response);
    }

    /**
     * Handle Framework Response
     */
    public function handle($registry = null)
    {
        $this->registry =  $registry;

        $this->kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);

        $this->response = $this->kernel->handle($this->request);

        if($this->response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse){
            $this->response->send();

            return true;
        } else {
            return ($this->response->status() != '404') ? true : false;
        }
    }

    /**
     * Check Route function
     *
     * @param string $route
     * @param object $registry
     * @return bool
     */
    public function checkRoute($route, &$output)
    {

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($route, '?')) {
            $route = substr($route, 0, $pos);
        }
        $route = rawurldecode($route);

        /**
         * Avoid multi chekings for the same controller
         */
        if(isset($this->routes_checked[$route])) {
            return null;
        } else {
            $this->routes_checked[$route] = true;
        }

        $this->request = \Illuminate\Http\Request::capture();

        /**
         * force admin route in case the request comes from admin side
         */
        if(defined('HTTPS_CATALOG')) {
            $appBaseName = basename(DIR_APPLICATION) . '/';
            $route = $appBaseName . $route;

            $serverName = $this->request->server->get('SCRIPT_NAME');
            $this->request->server->set('SCRIPT_NAME', str_replace($appBaseName, '', $serverName));
        }

        /**
         * Change URI for partial loaded controllers like common/header, common/footer etc...
         * in order to be able to override them
         */
        if($output !== false) {
            $this->request->server->set('REQUEST_URI', $route);
        }

        return $this->app->router->has($route) || $this->request->is($route . '*');
    }
}
