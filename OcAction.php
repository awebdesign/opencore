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
        if($framework->checkRoute($route)) {
            $this->response = $framework->handle();

//pre($route);
//pre($this->response,1);
            return ($this->response->status() != '404') ? true : false;
        }
    }

    public function executeAwebCoreRoute()
    {
        pre($this->route);
        //pre(IRequest::get('route'));
        pre(IRequest::url());
        pre(IRequest::path());
        //pre($this->response->original);
        /*
        pre(IRequest::query());
        pre(IRequest::fullUrl());
        pre(IRequest::getPathInfo()); 
        */

        //pre(App::make('url')->to('/'),1);
        if(isset($_GET['route']) && (new Request())->clean($_GET['route']) == $this->route) {
            echo $this->response->original;
        } else {
            return $this->response->original;
        }
    }
}
