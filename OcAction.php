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
        if($route == 'common/footer') {
            return true;
        }
    }

    public function executeAwebCoreRoute()
    {
        //pre($this->route);
        require __DIR__.'/App.php';
        $app = App::getInstance();

        //$out = $app->run();
        $request = new \Symfony\Component\HttpFoundation\Request();

        $response = $app->handle($request);

        return $response->original;
    }
}
