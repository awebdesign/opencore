<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Studio\Totem\Task;
use App\Observers\TaskObserver;
//use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /* if ($this->app->environment() === 'local') {
            $this->app->register('\Barryvdh\Debugbar\ServiceProvider');
        } */
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
        $this->app->singleton('db.connector.mysql', '\OpenCore\Support\MySqlSharedConnector');

        /**
         * Rewrite admin routes in order to contain the Token query param
         * corrects assets url
         */
        $this->app->singleton('url', function ($app) {
            return new \OpenCore\Support\UrlGenerator($app->router->getRoutes(), request(), $this->app->config->get('app.asset_url'));
        });

        /**
         * Fix MySql default string length for older mysql versions
         */
        Schema::defaultStringLength(191);

        /* Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('d/m/y H:i:s');
        }); */

        Task::observe(TaskObserver::class);
    }
}
