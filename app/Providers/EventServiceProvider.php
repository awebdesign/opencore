<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\File;
//use Illuminate\Auth\Events\Registered;
//use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /**
         * delete OpenCore routes stored on OpenCart cache folder
         */
        Event::listen('cache:cleared', function () {
            if (!defined('DIR_CACHE')) {
                require realpath(basename(__DIR__ . '/../../../')) . '/config.php';
            }

            $cachedRoutes = File::glob(DIR_CACHE . 'cache.opencore_routes.*');
            File::delete($cachedRoutes);
        });
    }
}
