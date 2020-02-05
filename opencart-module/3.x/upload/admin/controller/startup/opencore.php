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
    function before_controller($route, &$data, &$output = false)
    {
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

        switch ($route) {
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

                //get modules
                if (is_dir(realpath(DIR_APPLICATION . '../core/modules'))) {
                    foreach (glob(realpath(DIR_APPLICATION . '../core/modules') . '/*/module.json') as $path) {
                        $moduleManifest = json_decode(file_get_contents($path));
                        if (!json_last_error() && !empty($moduleManifest->active)) {
                            $moduleName = strtolower(basename(str_replace('/module.json', '', $path)));
                            $data['permissions'][] = $moduleName . '/*';
                        }
                    }
                }
                break;
        }

        $this->booted($route);
    }

    private function booted($route)
    {
        $this->booted[$route] = true;
    }
}
