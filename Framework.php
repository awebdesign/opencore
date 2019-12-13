<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

class Framework
{
    public static $instance;
    private $app;
    private static $routes;
    private $response;
    protected $registry;
    private $data;

    public function __construct()
    {
        //TEMPORARY REQUEST URI CHANGE -> IN ORDER TO USE MULTIPLE INSTANCES
        $_SERVER['ORIGINAL_REQUEST_URI'] = $_SERVER['REQUEST_URI'];

        $this->app = require __DIR__ . '/bootstrap/app.php';
    }

    /**
     * Bootstrap Instance
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            $app = new self();
            self::$instance = $app;
        }
        return self::$instance;
    }

    /**
     * Retrieve Response
     */
    public function getResponse()
    {
        return $this->response->getContent();
    }

    /**
     * Retrieves the OpenCart registry
     *
     * @return object
     */
    public function getOcRegistry()
    {
        return $this->registry;
    }

    /**
     * Retrieves OpenCart variables defined when the controller was loaded
     *
     * @return object
     */
    public function getOcVars()
    {
        return $this->data;
    }

    /**
     * Run Framework
     */
    public function run()
    {
        $this->app->run();
    }

    /**
     * Handle Framework Response
     */
    public function handle()
    {
        $this->response = $this->app->dispatch(null);

        //TEMPORARY REQUEST URI CHANGE -> IN ORDER TO USE MULTIPLE INSTANCES
        $_SERVER['REQUEST_URI'] = $_SERVER['ORIGINAL_REQUEST_URI'];

        return ($this->response->status() != '404') ? true : false;
    }

    /**
     * Check Route function
     *
     * @param string $route
     * @param object $registry
     * @return bool
     */
    public function checkRoute($route, $registry, &$data = []) {
        $this->registry = $registry;
        $this->data = $data;

        if (!self::$routes) {
            $routes = $this->app->getRoutes();
            self::$routes = $routes;
        }

        $dispatcher = \FastRoute\cachedDispatcher(function(\FastRoute\RouteCollector $r) {
            foreach(self::$routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']['uses']);
            }
        }, [
            'cacheFile' => __DIR__ . '/route.cache', /* required */
            'cacheDisabled' => false,     /* optional, enabled by default -> should be based on debug mode */
        ]);

        $route = strpos($route, '/') != 0 ? '/' . $route : $route;
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($route, '?')) {
            $route = substr($route, 0, $pos);
        }
        $route = rawurldecode($route);

        $_SERVER['REQUEST_URI'] = $route;
        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $route);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                return false;
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                //$allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                return false;
                break;
            case \FastRoute\Dispatcher::FOUND:
                //$handler = $routeInfo[1];
                //$vars = $routeInfo[2];
                // ... call $handler with $vars
                return true;
                break;
        }

        return false;
    }
}
