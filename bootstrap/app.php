<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
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
    realpath(__DIR__.'/../')
);

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
$app->withFacades(true, $defaults);

//$app->withEloquent();

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
    AwebCore\App\System\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    AwebCore\App\System\Console\Kernel::class
);

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
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

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

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

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

$app->group([
    'namespace' => 'AwebCore\App\System\Http\Controllers',
], function ($router) {
    require __DIR__.'/../App/System/Routes/web.php';
});



/*
$app->group(['prefix' => 'admin'], function () use ($app) {
    $app->get('users', function ()    {
        // Matches The "/admin/users" URL
    });
});
*/
//Route::has('route.name');
//pre(CoreRequest::is('route'),1);
//pre($app['request']->current('admin/*'),1);
//pre($app['request']->fullurl(),1);
//pre($app['request']->path(),1);

return $app;
