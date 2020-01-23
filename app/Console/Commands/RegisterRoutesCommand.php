<?php

namespace App\Console\Commands;

use Illuminate\Routing\Router;
use Illuminate\Console\Command;
use OpenCore\Support\Entities\OpencoreRoute;

class RegisterRoutesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'opencore:register-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register all routes into databse';

    /**
     * Ignore methods
     */
    protected $ignore_methods = [
        'HEAD'
    ];

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new route command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (empty($this->router->getRoutes())) {
            $this->deleteAllRoutes();

            return $this->error("Your application doesn't have any routes.");
        }

        $this->registerRoutes();

        return $this->info("Your route have been registered.");
    }

    /**
     * Register all routes into database
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $registeredRoutes = OpencoreRoute::get()->pluck('id', 'unique_key')->toArray();

        $keep = [];
        $routes = $this->router->getRoutes();
        foreach ($routes as $route) {
            foreach ($route->methods() as $method) {
                if (!in_array($method, $this->ignore_methods)) {
                    $data = [
                        'method' => $method,
                        'uri' => $route->uri(),
                        'name' => $route->getName()
                    ];

                    $key = implode('', $data);

                    $id = $registeredRoutes[$key] ?? null;
                    if (is_null($id)) {
                        $id = OpencoreRoute::insertGetId($data);
                    }
                    $keep[] = $id;
                }
            }
        }

        OpencoreRoute::whereNotIn('id', $keep)->delete();
    }

    /**
     * Delete all routes from database
     *
     * @return void
     */
    protected function deleteAllRoutes()
    {
        OpencoreRoute::truncate();
    }
}
