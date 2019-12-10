<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

require_once __DIR__.'/vendor/autoload.php';

class App {

    public static $instance;

    private $app;

    public function __construct() {
        try {
            (new \Dotenv\Dotenv(__DIR__.'/'))->load();
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            //
        }

        /*
        |--------------------------------------------------------------------------
        | Create The Application
        |--------------------------------------------------------------------------
        |
        | Here we will load the environment and create the application instance
        | that serves as the central piece of this framework. We'll use this
        | application as an "IoC" container and router for this framework.
        |
        */

        $this->app = new \Laravel\Lumen\Application(
            realpath(__DIR__.'/')
        );

        $this->registerFacades();

        $this->registerContainers();

        //$this->app->configure('app');

        $this->registerMiddlewares();

        $this->registerProviders();
        $this->prepareConsoleUrl();
        $this->registerRoutes();
    }

    //fix ul problem
    protected function prepareConsoleUrl()
    {
        $url = config('app.url');
        $urlParts = parse_url($url);

        if (isset($urlParts['path']) && mb_strlen($urlParts['path']) > 1) {
            $_SERVER['SCRIPT_NAME'] = $urlParts['path'].DIRECTORY_SEPARATOR.'index.php';
            $_SERVER['SCRIPT_FILENAME'] = getenv('PWD').DIRECTORY_SEPARATOR.$urlParts['path'].DIRECTORY_SEPARATOR.'index.php';
        }
        $this->app->instance('request', \Illuminate\Http\Request::create($url, 'GET', [], [], [], $_SERVER));
    }

    public static function getInstance()
    {
        if ( ! ( self::$instance instanceof self) ) {
            $app = new self();
            self::$instance = $app->app;
        }
        return self::$instance;
    }

    private function registerFacades() {
        $defaults = [
            'Illuminate\Support\Facades\Auth' => 'CoreAuth',
            'Illuminate\Support\Facades\Cache' => 'CoreCache',
            'Illuminate\Support\Facades\DB' => 'CoreDB',
            'Illuminate\Support\Facades\Event' => 'CoreEvent',
            'Illuminate\Support\Facades\Gate' => 'CoreGate',
            'Illuminate\Support\Facades\Log' => 'CoreLog',
            'Illuminate\Support\Facades\Queue' => 'CoreQueue',
            'Illuminate\Support\Facades\Schema' => 'CoreSchema',
            'Illuminate\Support\Facades\URL' => 'CoreURL',
            'Illuminate\Support\Facades\Validator' => 'CoreValidator',
            'Illuminate\Support\Facades\Request' => 'CoreRequest'
        ];
        $this->app->withFacades(true, $defaults);
        
        //$this->app->withEloquent();
    }

    private function registerContainers() {
        /*
        |--------------------------------------------------------------------------
        | Register Container Bindings
        |--------------------------------------------------------------------------
        |
        | Now we will register a few bindings in the service container. We will
        | register the exception handler and the console kernel. You may add
        | your own bindings here if you like or you can make another file.
        |
        */

        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \AwebCore\App\System\Exceptions\Handler::class
        );

        $this->app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \AwebCore\App\System\Console\Kernel::class
        );
    }

    private function registerMiddlewares() {
        /*
        |--------------------------------------------------------------------------
        | Register Middleware
        |--------------------------------------------------------------------------
        |
        | Next, we will register the middleware with the application. These can
        | be global middleware that run before and after each request into a
        | route or middleware that'll be assigned to some specific routes.
        |
        */

        // $this->app->middleware([
        //    AwebCore\App\System\Http\Middleware\ExampleMiddleware::class
        // ]);

        // $this->app->routeMiddleware([
        //     'auth' => AwebCore\App\System\Http\Middleware\Authenticate::class,
        // ]);
    }

    private function registerProviders() {
        /*
        |--------------------------------------------------------------------------
        | Register Service Providers
        |--------------------------------------------------------------------------
        |
        | Here we will register all of the application's service providers which
        | are used to bind services into the container. Service providers are
        | totally optional, so you are not required to uncomment this line.
        |
        */

        // $this->app->register(AwebCore\App\Providers\AppServiceProvider::class);
        // $this->app->register(AwebCore\App\Providers\AuthServiceProvider::class);
        // $this->app->register(AwebCore\App\Providers\EventServiceProvider::class);
    }

    private function registerRoutes() {
        /*
        |--------------------------------------------------------------------------
        | Load The Application Routes
        |--------------------------------------------------------------------------
        |
        | Next we will include the routes file so that they can all be added to
        | the application. This will provide all of the URLs the application
        | can respond to, as well as the controllers that may handle them.
        |
        */

        $this->app->group([
            'namespace' => 'AwebCore\App\System\Http\Controllers',
        ], function ($router) {
            require __DIR__.'/App/System/Routes/web.php';
        });

        /*
        $this->app->group(['prefix' => 'admin'], function () {
            $this->app->get('users', function ()    {
                // Matches The "/admin/users" URL
            });
        });
        */

        //Route::has('route.name');
        //pre(CoreRequest::is('route'),1);
        //pre($app['request']->current('admin/*'),1);
        //pre($_SERVER);
        //$this->app->make('config')->set('app.url', 'http://localhost');

        /*pre($this->app['request']->url());
        pre($this->app['request']->fullurl());
        pre($this->app['request']->path());
        pre($this->app['request']->getQueryString(),1);*/
        //pre($app['request']->path(),1);
    }
}
