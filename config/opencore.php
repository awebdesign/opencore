<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenCart Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | OpenCart application.
    |
    */

    'debug_opencart' => env('OPENCORE_DEBUG_OPENCART', false),

    /*
    |--------------------------------------------------------------------------
    | These are the core modules that should NOT be disabled under any circumstance
    |--------------------------------------------------------------------------
    */
    'coreModules' => [
        'core'
    ],

    /*
    |--------------------------------------------------------------------------
    | Date format
    |--------------------------------------------------------------------------
    */
    'dateformat' => env('OPENCORE_DATEFORMAT', 'd.m.Y H:i:s'),
];
