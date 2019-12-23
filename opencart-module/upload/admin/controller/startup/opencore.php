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
         * $this->event->register('view/\*\/before', new Action('startup/opencore/before_view'));
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
                //adding entries into admin menu for OpenCore panel
                $data['menus'][] = [
                    'id'       => 'opencore-menu',
                    'icon'       => 'fa-cube',
                    'name'       => 'OpenCore',
                    'href'     => $this->url->link('core/home', $this->getTokenStr()),
                    'children' => []
                ];
            break;
            case 'user/user_group_form':
                //adding permissions into admin user/permissions page for OpenCore panel
                $data['permissions'][] = 'core/*';
                $data['permissions'][] = 'example/*';

                sort($data['permissions']);
            break;
        }

        $this->booted($route);
    }

    private function booted($route) {
        $this->booted[$route] = true;
    }
}
