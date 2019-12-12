<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

//namespace AwebCore;

require_once __DIR__ . '/Helper/General.php';
require __DIR__.'/Framework.php';

$framework = Framework::getInstance();

class OcAction
{
    private $route;
    private $response;

    public function checkAwebCoreRoute($route)
    {
        global $framework;

        $this->route = $route;
        /*
        //we should ignore the following routes
        startup/startup
        startup/error
        startup/event
        startup/sass
        startup/login
        startup/permission
        startup/router
        event/compatibility/controller
        */

        if($framework->checkRoute($route)) {
            /* vendor/laravel/lumen-framework/src/Concerns/RoutesRequests.php */
            $this->response = $framework->handle();

            return ($this->response->status() != '404') ? true : false;
        }
    }

    public function executeAwebCoreRoute()
    {
        if(isset($_GET['route']) && (new Request())->clean($_GET['route']) == $this->route) {
            echo $this->response->original;
        } else {
            return $this->response->original;
        }
    }
}
