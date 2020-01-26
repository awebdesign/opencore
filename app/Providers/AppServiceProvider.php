<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use OpenCore\Support\Opencart\Startup;
use Nwidart\Modules\Contracts\RepositoryInterface;
use OpenCore\Support\OcCore;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Initiate loader in case the request is not done throught OpenCart
         * and in order to avoid futher errors throwing
         */
        if (!$this->app->bound('OcLoader')) {
            $this->app->bind('OcLoader', function () {
                return new \OpenCore\Support\OcLoader();
            });
        }

        app('router')->bind('module', function ($module) {
            return app(RepositoryInterface::class)->find($module);
        });

        /**
         * Loading Barryvdh Debug bar
         * must be on local and APP_DEBUG = true
         */
        if (\App::environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Mysql OpenCart Share Connector | ignore it for artisan commands
         */
        if (class_exists('\OpenCore\Framework')) {
            $this->app->singleton('db.connector.mysql', '\OpenCore\Support\MySqlSharedConnector');
        }

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

        $this->app->bind('OcCore', function () {
            return new OcCore();
        });

        /**
         * set language based on OpenCart language session
         * if there is an instance of OpenCart ready
         */
        $locale = config('app.locale');
        if (defined('OPENCORE_VERSION')) {
            $session = Startup::getRegistry('session');

            if (!empty($session->data['language'])) {
                $lang = explode('-', $session->data['language']);
                $locale = $lang[0];
            }
        }
        $this->app->setLocale($locale);

        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format(config('opencore.dateformat'));
        });
    }
}
