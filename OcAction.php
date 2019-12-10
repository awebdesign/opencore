<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

class OcAction
{
    private $route;

    public function checkAwebCoreRoute($route)
    { 
        $this->route = $route;
        /*
        //should ignore the following
        startup/startup
        startup/error
        startup/event
        startup/sass
        startup/login
        startup/permission
        startup/router
        event/compatibility/controller
        */
        //test
        //if($route == 'extension/module/awebcore') {
        if($route == 'common/footer1') {
            return true;
        }
    }

    public function executeAwebCoreRoute()
    {
        //pre($this->route);
        $app = require __DIR__.'/bootstrap/app.php';
        //
        //$out = $app->run();
        $request = new \Symfony\Component\HttpFoundation\Request();

        $response = $app->handle($request);
        //$out = $response->send();

        return $response->original;
    }
}
