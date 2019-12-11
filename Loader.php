<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore;

if (!defined('DIR_APPLICATION')) {
	exit;
}

if (!defined('AWEBCORE_VERSION')) {
    define('AWEBCORE_VERSION', '1.0.0');
}

if (!defined('AWEBCORE_DIR')) {
    define('AWEBCORE_DIR', __DIR__ . '/');
}

require_once AWEBCORE_DIR . 'vendor/autoload.php';
require_once AWEBCORE_DIR . 'Helper/General.php';
require_once AWEBCORE_DIR . 'OcRouter.php';

//pre(new AwebCore\App\Controllers\OcController(),1);

//register autoloader
/*spl_autoload_register(function ($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $className = str_ireplace('AwebCore/', '', $className);
    $fileName =  AWEBCORE_DIR . $className . '.php';

    if (file_exists($fileName)) {
        require_once $fileName;
    } else {
        throw new \Exception('AwebCore File not found: ' . $fileName);
    }
});*/

/*
//composer require illuminate/container "^5.4"

function setup_App(){
    $container = new Illuminate\Container\Container();
    Illuminate\Support\Facades\Facade::setFacadeApplication($container);
    $container->singleton('app', 'Illuminate\Container\Container');
    class_alias('Illuminate\Support\Facades\App', 'App');
}

setup_App();

App::bind('w', 'Widget');

Readme:
https://www.sitepoint.com/how-laravel-facades-work-and-how-to-use-them-elsewhere/
https://github.com/laracasts/Eloquent-Outside-of-Laravel/blob/master/vendor/illuminate/support/Illuminate/Support/Facades/Facade.php
https://www.sitepoint.com/how-laravel-facades-work-and-how-to-use-them-elsewhere/
https://github.com/illuminate/container/tree/5.4
http://php-di.org/doc/getting-started.html
https://blog.albert-chen.com/laravels-dependency-injection-container-in-depth/
https://medium.com/@jeffochoa/using-the-illuminate-validation-validator-class-outside-laravel-6b2b0c07d3a4
*/
