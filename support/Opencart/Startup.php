<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support\Opencart;

if (!defined('DIR_APPLICATION')) {
    exit;
}

if (!defined('OPENCORE_VERSION')) {
    define('OPENCORE_VERSION', '1.2.2');
}

require_once __DIR__ . '/../../Framework.php';

use Exception;
use Opencore\Framework;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Startup extends \Controller
{
    private static $_registry;
    private $route;
    private $data;

    public $routes_cache_time = 3600; //1 hour expiration time

    private $default_allowed_routes = [
        'admin/core/home',
        'admin/core/modules',
        'admin/core/system/routes',
        'admin/core/system/routes/register',
        'admin/core/system/requirements',
        'admin/core/system/clear-cache',
    ];

    function __construct($registry)
    {
        parent::__construct($registry);

        self::$_registry = $registry;
    }

    /**
     * Execute Laravel if route exists
     *
     * @param string $route
     * @param array &$data
     * @param string &$output
     */
    public function executeIfRouteExists($route, &$data, &$output = false)
    {
        $this->route = $route;
        $this->data = $data;

        /**
         * we are using $this->request->get['route'] instead of $route because on $route some characters like dash ("-") are removed
         */
        if (!empty($this->request->get['route']) && preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $this->request->get['route']) == $this->route) {
            $this->route = $this->request->get['route'];
        }

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($this->route, '?')) {
            $this->route = substr($this->route, 0, $pos);
        }
        $this->route = rawurldecode($this->route);

        /**
         * if is an Admin request add path to route
         */
        $appBaseName = basename(DIR_APPLICATION);
        if (defined('HTTPS_CATALOG') && !strstr($this->route, $appBaseName . '/')) {
            $this->route = $appBaseName . '/' . $this->route;
        }

        if ($this->checkOpenCoreRoute()) {
            Framework::getInstance()->handle(self::$_registry, $this->route, $output);

            $response = $this->response()->getContent();

            /**
             * set content type of the response received
             */
            $contentType = $this->response()->headers->all('content-type');
            if (!empty($contentType[0])) {
                $this->response->addHeader('Content-Type: ' . $contentType[0]);
            }

            if ($output === false) {
                /**
                 * means is a controller request which means we need to use default setOutput()
                 */
                $this->response->setOutput($response);

                return true;
            } else {
                /**
                 * the request is done using the loader method and $output need to be populated
                 * Check system/engine/loader.php
                 */
                $output = $response;

                return false;
            }
        }

        return null;
    }

    /**
     * Check Framewrok available routes
     */
    public function checkOpenCoreRoute()
    {
        /**
         * Check default allowed routes
         */
        if (in_array($this->route, $this->default_allowed_routes)) {
            return true;
        }

        /**
         * force admin route in case the request comes from admin side
         */
        $allowed_routes = [];

        if (!$allowed_routes = $this->cache->get('opencore_routes')) {
            $query = $this->db->query("SELECT method, name, uri FROM `" . DB_PREFIX . "opencore_routes` WHERE `status` = '1' ORDER BY uri");

            if (!$query->num_rows)
                return false;

            foreach ($query->rows as $route) {
                $name = $route['name'] ?? $route['uri'];
                $allowed_routes[$route['method']][$name] = $route['uri'];
            }

            /**
             * Cache OpenCore allowed routes for faster rendering
             */
            $this->cache->set('opencore_routes', $allowed_routes, time() + $this->routes_cache_time);
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (!empty($allowed_routes[$requestMethod])) {
            $routes = new RouteCollection();
            $context = new RequestContext('/');

            foreach ($allowed_routes[$requestMethod] as $name => $routeUri) {
                $routeInstance = new Route($routeUri);
                $routes->add($name, $routeInstance);
            }

            $matcher = new UrlMatcher($routes, $context);

            try {
                return $matcher->match('/'.$this->route);
            } catch(Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Framework Response function
     */
    public function response()
    {
        return Framework::getInstance()->getResponse();
    }

    /**
     * Retrieves the OpenCart registry
     *
     * @return object
     */
    public static function getRegistry($type = '')
    {
        $registry = self::$_registry;

        if ($type) {
            return $registry->get($type);
        }

        return $registry;
    }
}
