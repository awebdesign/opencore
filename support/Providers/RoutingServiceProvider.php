<?php 

namespace OpenCore\Support\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
//use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
abstract class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }
    /**
     * @return string
     */
    abstract protected function getCatalogRoute();
    /**
     * @return string
     */
    abstract protected function getAdminRoute();
    /**
     * @return string
     */
    abstract protected function getApiRoute();
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            $this->loadApiRoutes($router);
        });

        //$router->group(['namespace' => $this->namespace, 'prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localizationRedirect']], function (Router $router) {
            $this->loadCatalogRoutes($router);
            $this->loadAdminRoutes($router);
        //});
    }
    /**
     * @param Router $router
     */
    private function loadCatalogRoutes(Router $router)
    {
        $catalog = $this->getCatalogRoute();
        if ($catalog && file_exists($catalog)) {
            $router->group(['namespace' => 'Catalog', 'middleware' => 'CatalogMiddleware'], function (Router $router) use ($catalog) {
                require $catalog;
            });
        }
    }
    /**
     * @param Router $router
     */
    private function loadAdminRoutes(Router $router)
    {
        $admin = $this->getAdminRoute();
        if ($admin && file_exists($admin)) {
            $router->group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'AdminMiddleware'], function (Router $router) use ($admin) {
                require $admin;
            });
        }
    }
    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router)
    {
        $api = $this->getApiRoute();
        if ($api && file_exists($api)) {
            $router->group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => ''], function (Router $router) use ($api) {
                require $api;
            });
        }
    }
}