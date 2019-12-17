<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

if (!defined('DIR_APPLICATION')) {
    exit;
}

if (!defined('AWEBCORE_VERSION')) {
    define('AWEBCORE_VERSION', '1.0.0');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper/General.php';
require_once __DIR__ . '/Framework.php';

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
     * Run function
     *
     * @execute Framewrok
     */
    public function run()
    {
        Framework::getInstance()->run();
    }

    /**
     * Check Framewrok available routes
     *
     * @param string $route
     */
    public function checkAwebCoreRoute($route, &$data)
    {
        $this->route = $route;
        $this->data = $data;
        /*
            We should add a dinamyc ignore list here
        */
        if (Framework::getInstance()->checkRoute($route)) {
            return Framework::getInstance()->handle();
        }
    }

    /**
     * Framework Response function
     */
    public function response()
    {
        $response = Framework::getInstance()->getResponse();

        if (isset($this->request->get['route']) && $this->request->get['route'] == $this->route) {
            echo $response;
            return false;
        } else {
            return $response;
        }
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
