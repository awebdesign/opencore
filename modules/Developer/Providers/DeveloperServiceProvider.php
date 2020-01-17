<?php

namespace Modules\Developer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Config;

class DeveloperServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        //avoid errors when working with local machine composer & remote server files
        if (defined('TOTEM_DATABASE_CONNECTION') && class_exists('\Studio\Totem\Task')) {
            \Studio\Totem\Task::observe(\Modules\Developer\Observers\TaskObserver::class);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->register(\Studio\Totem\Providers\TotemServiceProvider::class);
        $this->app->register(\Arcanedev\LogViewer\LogViewerServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('developer.php'),
            __DIR__ . '/../Config/log-viewer.php' => config_path('log-viewer.php'),
            __DIR__ . '/../Config/totem.php' => config_path('totem.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'developer'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/log-viewer.php',
            'log-viewer'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/totem.php',
            'totem'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/developer');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/developer';
        }, Config::get('view.paths')), [$sourcePath]), 'developer');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/developer');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'developer');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'developer');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
