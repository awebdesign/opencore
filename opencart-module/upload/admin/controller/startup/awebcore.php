<?php
/*
 * Created on Fri Dec 13 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

require_once realpath(__DIR__ . '/../../../') . '/core/Startup.php';

use AwebCore\Startup;
use AwebCore\Traits\OcCore;

class ControllerStartupAwebcore extends Startup
{
    use OcCore;

    private $booted = [];

    /**
     * This method is executed everytime before loading any controller
     *
     * @param string $route
     * @param array $data
     * @return string
     */
    function before_controller($route, &$data)
    {
        if ($this->checkAwebCoreRoute($route, $data)) {
            return $this->response();
        }

        /**
         * in case the view\/*\/before event is not activated by default we can call
         * $this->event->register('view/\*\/before', new Action('startup/awebcore/before_view'));
         */
    }

    /**
     * Adds admin menu entries
     *
     * @param string $route
     * @param array $data
     */
    public function before_view($route, &$data)
    {
        if (isset($this->booted[$route])) {
            return;
        }

        switch($route) {
            case 'common/column_left':
                //adding entries into admin menu for Aweb Core panel
                $data['menus'][] = [
                    'id'       => 'awebcore-menu',
                    'icon'       => 'fa-superpowers',
                    'name'       => 'Aweb Core',
                    'href'     => $this->url->link('core/home', $this->getTokenStr()),
                    'children' => []
                ];
            break;
            case 'user/user_group_form':
                //adding permissions into admin user/permissions page for Aweb Core panel
                $data['permissions'][] = 'core/home';

                sort($data['permissions']);
            break;
        }

        $this->booted($route);
    }

    private function booted($route) {
        $this->booted[$route] = true;
    }
}
