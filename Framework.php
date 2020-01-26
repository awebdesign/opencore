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
    private $request = null;
    private $kernel;

    private $registry;
    public $route;
    public $output;

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
     * Handle Framework Response
     */
    public function handle($registry, $route, &$output)
    {
        $this->registry =  $registry;
        $this->route =  $route;
        $this->output =  $output;

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

        $this->response = $this->kernel->handle($this->request);

        if ($this->response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            $this->response->send();
        }

        $this->kernel->terminate($this->request, $this->response);
    }
}
