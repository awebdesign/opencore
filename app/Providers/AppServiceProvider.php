<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use OpenCore\Support\Opencart\Startup;
use Nwidart\Modules\Contracts\RepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app('router')->bind('module', function ($module) {
            return app(RepositoryInterface::class)->find($module);
        });

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

        /**
         * set language based on OpenCart language session
         * if there is an instance of OpenCart ready
         */
        if(defined('OPENCORE_VERSION')) {
            $session = Startup::getRegistry('session');

            $locale = config('app.locale');
            if(!empty($session->data['language'])) {
                $lang = explode('-', $session->data['language']);
                $locale = $lang[0];
            }
            $this->app->setLocale($locale);
        }

        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format(config('opencore.dateformat'));
        });
    }
}
