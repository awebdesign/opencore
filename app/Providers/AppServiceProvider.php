<?php

namespace AwebCore\App\Providers;

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
        Schema::defaultStringLength(191);

        /* OpenCart MySql shared driver */
        /**
         * more info: https://github.com/mixartemev/dbal-vertica-driver
         */
        /* App::bind('db.connector.mysqlshare', function () {
            return new MysqlShareDriver;
        });
        DB::resolverFor('mysqlshare', function ($connection, $database, $prefix, $config) {
            return new PostgresConnection($connection, $database, $prefix, $config);
        }); */
    }
}
