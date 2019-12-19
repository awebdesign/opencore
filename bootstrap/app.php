<?php

require_once __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__ . '/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    OpenCore\App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    OpenCore\App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    OpenCore\App\Exceptions\Handler::class
);

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
$namespace = 'OpenCore\App\Http';

if (defined('HTTPS_CATALOG')) {
    //admin routes here //->prefix('core')
    $app->router->middleware(['web', $namespace . '\Middleware\AdminMiddleware'])
        ->name('admin::')
        ->namespace($namespace . '\Controllers\Admin')
        ->group(function ($router) {
            require __DIR__ . '/../app/Routes/admin.php';
        });
} else {
    //catalog routes here
    $app->router
        ->middleware(['web', $namespace . '\Middleware\CatalogMiddleware'])
        ->name('catalog::')
        ->namespace($namespace . '\Controllers\Catalog')
        ->group(function ($router) {
            require __DIR__ . '/../app/Routes/catalog.php';
        });
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
