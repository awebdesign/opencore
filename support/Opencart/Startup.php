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
    define('OPENCORE_VERSION', '1.2.1');
}

require_once __DIR__ . '/../../Framework.php';

use Opencore\Framework;

class Startup extends \Controller
{
    private static $_registry;
    private $route;
    private $data;

    public $routes_cache_time = 3600; //1 hour expiration time

    private $default_allowed_routes = [
        'admin/core/home',
        'admin/core/requirements',
        'admin/core/modules',
        'admin/core/clear-cache'
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

        if ($this->checkOpenCoreRoute()) {
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
         * if is an Admin request add path to route
         */
        $appBaseName = basename(DIR_APPLICATION);
        if (defined('HTTPS_CATALOG') && !strstr($this->route, $appBaseName . '/')) {
            $this->route = $appBaseName . '/' . $this->route;
        }
        /*
            TODO: We should add a dynamic ignore list here which should be configurable in admin/core/settings
        */

        /**
         * force admin route in case the request comes from admin side
         */
        $allowed_routes = [];

        if ($this->routes_cache_time) {
            $cache = self::$_registry->get('cache');
            $allowed_routes = $cache->get('opencore_routes.' . $appBaseName); //separate for admin & catalog to avoid large cache files
        }

        $force = false;
        if (in_array($this->route, $this->default_allowed_routes)) {
            $force = true;
        }

        if (isset($allowed_routes[$this->route]) && $allowed_routes[$this->route] == false) {
            return false;
        } else {
            Framework::getInstance()->initiate(self::$_registry, $this->route, $output);

            if (!empty($allowed_routes[$this->route]) || $force) {
                Framework::getInstance()->initiateRouteRequest();
            } else {
                $checkRoute = Framework::getInstance()->checkRoute();

                switch ($checkRoute) {
                    case Framework::FOUND:
                        $allowed_routes[$this->route] = true;
                        break;
                    case Framework::NOT_FOUND:
                        $allowed_routes[$this->route] = false;
                        break;
                }
            }
        }

        /**
         * Cache OpenCore allowed routes for faster rendering
         */
        if ($this->routes_cache_time && !$force) {
            $cache->set('opencore_routes.' . $appBaseName, $allowed_routes, time() + $this->routes_cache_time);
        }

        if (!empty($allowed_routes[$this->route]) || $force) {
            return Framework::getInstance()->handle();
        }

        return false;
    }

    /**
     * Run function
     *
     * @execute Framewrok
     */
    public function run()
    {
        Framework::getInstance()->run();
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

    /**
     * Retrieves OpenCart variables defined when the controller was loaded
     *
     * @return object
     */
    public function getOcVars()
    {
        return $this->data;
    }
}
