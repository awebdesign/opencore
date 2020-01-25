<?php
/*
 * Created on Fri Jan 23 2020 by DaRock
 *
 * Copyright (c) Aweb Design
 * https://www.awebdesign.ro
 */

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'opencore:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache options';

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return $this->info("Cache has been deleted!");
    }
}
