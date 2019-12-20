<?php

namespace OpenCore\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Mysql OpenCart Share Connector
         */
        $this->app->singleton('db.connector.mysql', '\OpenCore\Connectors\MySqlSharedConnector');

        /**
         * Rewrite admin routes in order to contain the Token query param
         * corrects assets url
         */
        $this->app->singleton('url', function ($app) {
            return new \OpenCore\App\General\UrlGenerator($app->router->getRoutes(), request(), $this->app->config->get('app.asset_url'));
        });

        /**
         * Fix MySql default string length for older mysql versions
         */
        Schema::defaultStringLength(191);
    }
}
