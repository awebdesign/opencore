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
    define('OPENCORE_VERSION', '1.2.0');
}

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../Framework.php';

use Opencore\Framework;

class Startup extends \Controller
{
    private $route;
    private static $_registry;
    private $data;

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
        /**
         * we are using $this->request->get['route'] instead of $route because on $route some characters like dash ("-") are removed
         */
        if (!empty($this->request->get['route']) && preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $this->request->get['route']) == $route) {
            $route = $this->request->get['route'];
        }

        if ($this->checkOpenCoreRoute($route, $data, $output)) {
            $response = $this->response();

            if($output === false) {
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
     *
     * @param string $route
     */
    public function checkOpenCoreRoute($route, &$data, &$output)
    {
        $this->route = $route;
        $this->data = $data;

        /*
            We should add a dinamyc ignore list here
        */
        if (Framework::getInstance()->checkRoute($route, $output)) {
            return Framework::getInstance()->handle(self::$_registry);
        }
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
