<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

//namespace AwebCore;


class Framework
{
    public static $instance;

    private $app;
    
    public static $routes;

    public function __construct()
    {
        $this->app = require __DIR__ . '/bootstrap/app.php';
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            $app = new self();
            self::$instance = $app;
        }
        return self::$instance;
    }

    public function get()
    {
        return $this->app;
    }

    public function run()
    {
        $this->app->run();
    }

    public function handle()
    {
        return $this->app->dispatch(null);
    }

    public function checkRoute($route) {
        if (!self::$routes) {
            $routes = $this->app->getRoutes();
            self::$routes = $routes;
        }

        $route = strpos($route, '/') != 0 ? '/' . $route : $route;

        return collect(self::$routes)->contains('uri', $route);
    }
}
