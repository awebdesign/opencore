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
    private $uri = null;
    private $httpMethod;
    
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

        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            foreach(self::$routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']['uses']);
            }
        });

        $route = strpos($route, '/') != 0 ? '/' . $route : $route;
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($route, '?')) {
            $route = substr($route, 0, $pos);
        }
        $route = rawurldecode($route);

        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $route);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                return false;
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                return false;
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                return true;
                break;
        }

        return false;
    }
}
