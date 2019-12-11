<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
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

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$defaults = [
    //'Illuminate\Support\Facades\Auth' => 'IAuth',
    'Illuminate\Support\Facades\Cache' => 'ICache',
    'Illuminate\Support\Facades\DB' => 'IDB',
    'Illuminate\Support\Facades\Event' => 'IEvent',
    'Illuminate\Support\Facades\Gate' => 'IGate',
    'Illuminate\Support\Facades\Log' => 'ILog',
    'Illuminate\Support\Facades\Queue' => 'IQueue',
    'Illuminate\Support\Facades\Schema' => 'ISchema',
    'Illuminate\Support\Facades\URL' => 'IURL',
    'Illuminate\Support\Facades\Validator' => 'IValidator',
    'Illuminate\Support\Facades\Request' => 'IRequest'
];

$app->withFacades(true, $defaults);

$app->withEloquent();

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

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    AwebCore\App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    AwebCore\App\Console\Kernel::class
);

$app->configure('app');


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

// $app->middleware([
//    AwebCore\App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    'Admin' => AwebCore\App\Http\Middleware\AdminRoute::class
]);

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

// $app->register(AwebCore\App\Providers\AppServiceProvider::class);
// $app->register(AwebCore\App\Providers\AuthServiceProvider::class);
// $app->register(AwebCore\App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------`
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

if(defined('HTTPS_CATALOG')) {
    //admin routes here

    //TODO: find a solution for this thing!!!
    /* Very Bad Work Arround -> in order to use route url query param */
    if(isset($_GET['route'])) {
        $_SERVER['ORIGINAL_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
        $_SERVER['REQUEST_URI'] = (new Request())->clean($_GET['route']);
    }
 
    $app->group([
        'namespace' => 'AwebCore\App\Http\Controllers\Admin',
        'middleware' => 'Admin'
    ], function ($router) {
        require __DIR__ . '/../App/Routes/admin.php';
    });
} else {
    //catalog routes here
    $app->group([
        'namespace' => 'AwebCore\App\Http\Controllers\Catalog',
    ], function ($router) {
        require __DIR__ . '/../App/Routes/catalog.php';
    });
}

return $app;
