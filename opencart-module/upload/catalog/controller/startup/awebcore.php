<?php
/*
 * Created on Fri Dec 13 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

require_once realpath(__DIR__ . '/../../../') . '/core/Startup.php';

use OpenCore\Startup;
use OpenCore\Traits\OcCore;

class ControllerStartupAwebcore extends Startup
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
    function before_controller($route, &$data)
    {
        /* if (self::$booted) {
            return false;
        } */

        /**
         * we are using $this->request->get['route'] instead of $route because on $route some characters like dash ("-") are removed
         */
        if (!empty($this->request->get['route']) && preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $this->request->get['route']) == $route) {
            $route = $this->request->get['route'];
        }

        if ($this->checkOpenCoreRoute($route, $data)) {
            return $this->response();
        }

        /**
         * in case the view\/*\/before event is not activated by default we can call
         * $this->event->register('view/\*\/before', new Action('startup/awebcore/before_view'));
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
