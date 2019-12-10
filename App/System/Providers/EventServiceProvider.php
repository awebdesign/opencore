<?php

namespace AwebCore\App\System\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'AwebCore\App\System\Events\SomeEvent' => [
            'AwebCore\App\System\Listeners\EventListener',
        ],
    ];
}
