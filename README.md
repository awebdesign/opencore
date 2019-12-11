FOLDER STRUCTURE OF Aweb Core

/Modules
    /System
        /Controllers
            => /admin
            => /catalog
        /Models
        /Trait
        /Lang
        /Route
        /Views
        /Migrations
        ...etc
    /Any_other_module_name
        /Controllers
            => /admin
            => /catalog
        /Models
        /Trait
        /Lang
        /Route
        /Views
        /Migrations
        ...etc
/Traits
/Helper
/Ocmod
etc..



/admin/controller/common/header.php
    => IF exists in /awebcore/Modules/System/Controllers/admin/common/header.php => we load it from here
    => IF DOES NOT exists in /awebcore/Modules/System/Controllers/admin/common/header.php => we load the system default one
same for model, lang, view, etc

/* tests for setting route url */


/*$uri = $app->make('config')->get('app.url', 'http://localhost');

    $components = parse_url($uri);

    $server = $_SERVER;

    if (isset($components['path'])) {
        $server = array_merge($server, [
            'SCRIPT_FILENAME' => $components['path'],
            'SCRIPT_NAME' => $components['path'],
        ]);
    }

    $app->instance('request', IRequest::create(
        $uri, 'GET', [], [], [], $server
    )); */


    //$this->server->set('REQUEST_URI', $requestUri);
    //admin routes here

    //pre(\IRequest::query(),1);
    //$uri = '/admin/extension/module/awebcore';
    //pre($app->request->query(),1);

    //$app->request->server->set('REQUEST_URI', $_wanna_be_uri);
    
    /* $server = $_SERVER;
    $app->instance('request', IRequest::create(
            $uri, 'GET', [], [], [], $server
    ));     */
	
	
	//pre(IRequest::url(),1);
        //pre($request,1);
        //$_SERVER['REQUEST_URI'] = 'extension/module/awebcore';
        //$request->server->set('REQUEST_URI', 'extension/module/awebcore');
        //pre('AdminRoute',1);
        //pre($request->path(),1);
        //pre(\IRequest::route(),1);
        
        //$request = Request::createFromBase($request);
        //pre($request,1);
        //pre($this->currentRoute);
        //$this->currentRoute = 'extension/module/awebcore';
        /* if($request->route(null)) {
            //$request->route(null)->setParameter('orderId', 'sss');
            pre($request->namedRoutes);
            //$routeArray[2] = $routeParameters;

            //pre($routeArray);
        } */

        //pre($request->route());
        //$routes = app()->getRoutes();
        
       
        /*if(0 && $foundRoute = collect($routes)->where('uri', '/test')) {
            
            //pre($routes);
            $routeArray = $foundRoute->first();
            $routeArray[2]['test'] = 'test';
            //pre($routeArray);
            $request->setRouteResolver(function() use ($routeArray)
            {
                return $routeArray;
            });
            
            //pre(\IRequest::getPathInfo());

            //pre($request->route());
            /* $routeParameters = $request->route(null)[2];

            foreach ($routeParameters as $key=>$routeParameter) {
                $routeParameters[$key] = urldecode($routeParameter);
            }

            //$routeParameters['extension/module/awebcore'] = 'extension/module/awebcore';
            $routeArray = $request->route();
            $routeArray[2] = $routeParameters;

            pre($routeArray);
            $request->setRouteResolver(function() use ($routeArray)
            {
                return $routeArray;
            }); */
        /*}*/