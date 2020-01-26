<?php
/*
 * Created on Fri Dec 13 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

require_once realpath(__DIR__ . '/../../../') . '/core/support/Opencart/Startup.php';

use OpenCore\Support\Opencart\Startup;
use OpenCore\Support\OpenCart\OcCore;

class ControllerStartupOpencore extends Startup
{
    use OcCore;

    /* static $booted = false; */

    /**
     * This method is executed everytime before loading any controller
     *
     * @param string $route
     * @param array $data
     * @return string
     */
    function before_controller($route, &$data, &$output = false)
    {
        /**
         * In case we want to disable OpenCore for catalog
         * OPENCORE_DISABLED constant must be defined in config.php
         */
        if(defined('OPENCORE_DISABLED')) {
            return null;
        }

        /* if (self::$booted) {
            return false;
        } */

        /**
         * Check if a laravel route exists and if so execute it
         * else return NULL
         */
        return $this->executeIfRouteExists($route, $data, $output);

        /**
         * in case the view\/*\/before event is not activated by default we can call
         * $this->event->register('view/\*\/before', new Action('startup/opencore/before_view'));
         */
    }

    /**
     * Before loading views event
     *
     * @param string $route
     * @param array $data
     */
    /* public function before_view($route, &$data)
    {
        return null;
    } */
}
