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
    'Illuminate\Support\Facades\Gate' => 'Gate',
    'Illuminate\Support\Facades\Log' => 'ILog',
    'Illuminate\Support\Facades\Queue' => 'Queue',
    'Illuminate\Support\Facades\Schema' => 'Schema',
    'Illuminate\Support\Facades\URL' => 'IURL',
    'Illuminate\Support\Facades\Validator' => 'Validator',
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

/**
 * Loading session manager
 * https://github.com/rummykhan/lumen-session-example
 */
/* $app->singleton('cookie', function () use ($app) {
    return $app->loadComponent('session', 'Illuminate\Cookie\CookieServiceProvider', 'cookie');
});

$app->bind('Illuminate\Contracts\Cookie\QueueingFactory', 'cookie');

$app->singleton(Illuminate\Session\SessionManager::class, function () use ($app) {
    return $app->loadComponent('session', Illuminate\Session\SessionServiceProvider::class, 'session');
});

$app->singleton('session.store', function () use ($app) {
    return $app->loadComponent('session', Illuminate\Session\SessionServiceProvider::class, 'session.store');
}); */

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
//     \Illuminate\Cookie\Middleware\EncryptCookies::class,
//     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//     \Illuminate\Session\Middleware\StartSession::class,
//     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//     \Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
// ]);

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
$namespace = 'AwebCore\App\Http\Controllers';

if (defined('HTTPS_CATALOG')) {
    //admin routes here
    $app->group([
        'namespace' => $namespace . '\Admin',
        'middleware' => 'Admin'
    ], function ($router) {
        require __DIR__ . '/../app/Routes/admin.php';
    });
} else {
    //catalog routes here
    $app->group([
        'namespace' => $namespace . '\Catalog',
    ], function ($router) {
        require __DIR__ . '/../app/Routes/catalog.php';
    });
}

return $app;
