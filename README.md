<h1 align="center"><img src="https://opencore.me/images/logo/opencore-logo-large-transparent.png" width="250" alt="OpenCore"></h1>

**An application build on Laravel which can run as a subsystem for OpenCart system.**

OpenCore is an application made on Laravel for OpenCart which let's you develop new features or overwrite the existing ones in Laravel instead of the old OpenCart framework. The application comes with build in features which will help the developers to create new modules or new functionallities for OpenCart ecommerce platform. Also stand alone(idependent of OpenCart) features can be done too.

## OpenCore - Laravel for OpenCart
by <a target="_blank" href="https://www.awebdesign.ro/en/">Aweb Design</a>


## WORK IN PROGRESS

Please note that this package is still under active development. We encourage everyone to try it, give feedback and if possible to contribute.

## Features

* All Laravel's 5.8 built in helpers / features
* Error logs by <a target="_blank" href="https://github.com/ARCANEDEV/LogViewer">ARCANEDEV</a>
* Cronjob system management by <a target="_blank" href="https://github.com/codestudiohq/laravel-totem">Codestudiohq</a>
* WYSIWYG Email HTML/Markdown editor.
* Auto update feature
* Module management by <a target="_blank" href="https://github.com/nWidart/laravel-modules">nWidart Modules</a>
* many more to come...

## System Requirements

PHP >= 7.1.3
BCMath PHP Extension
Ctype PHP Extension
JSON PHP Extension
Mbstring PHP Extension
OpenSSL PHP Extension
PDO PHP Extension
Tokenizer PHP Extension
XML PHP Extension
* check Laravel requirements cause may differ depending on the used version

## Other Requirements
OpenCart 2.x / 3.x installed
root .htaccess.txt renamed to .htaccess
admin / setting / server / Use SEO URLs: Yes

## Installation

``` bash
1. Install Git and Composer on your system
2. go to your OpenCart root folder, open a console and run the following commands step by step
3. git clone https://github.com/opencorero/opencore.git core
4. cd core/
5. composer update
6. php artisan key:generate
7. php artisan migrate:install
8. php artisan migrate
9. copy OpenCart extension files from core/opencart-module/(2.x|3.x)/upload to you OpenCart root folder
10. go to OpenCart admin panel / extensions / extensions / modules, find OpenCore module and install it
11. click on the OpenCore icon from admin / left column / section "System Requirements" and make sure there's nothing marked with red
12. Optional: in order to enable Developer & Example modules you need to access admin / user / user groups section and add permission for them
```
Enjoy!

## How does it works ?

let's take this file from OpenCart system: /admin/controller/common/header.php
As you probably know, this file is commonly used for inserting the header part in all admin sections(there's one for catalog as well in catalog/controller/common/header.php) as is called in other controllers like

    $data['header'] = $this->load->controller('common/header');

and if does not have aother slash after header then the default index() method will be loaded from header controller.
So, if you will like to rewrite this section using Laravel you can create a custom Controller and a GET route 'common/header' which will point to the new created controller and that's it! You will not be able to write whatever custom code you want for the header file.

Example: $router->get('common/header', 'ExampleController@commonColumnLeftReplace')->name('common.header');

If you will want to create a new functionallity, the process is the same but instead of using an existing route you will need to create a unique one.

Example: $router->get('example', 'ExampleController@index')->name('example');

So, everytime you will call /admin/example this controller will throw the content

Just remember that routes are splited into two:
admin.php and catalog.php routes files which can be found in /core/app/routes/ or in any module ex: /core/modules/Example/routes/

## Is it fast ?!
Yes, it is! As long as your server has the minimum requirements for Laravel 5.8 to work, everything should be good. The module is very fast and has no delay of any kind. The integration of Laravel Framework throught OpenCart doesn't affect at all the speed of any of them. That is happenning thanks to the multiple optimizations done in the code and to the mysql shared connector which will help sharing the database active connection between OpenCart and Laravel/OpenCore

## Documentation

Documentation for OpenCore can be found on the [Official website](https://opencore.me).
* right now is under developement but everything will be there soon

## Security

If you discover any security related issues, please email author email [support@opencore.ro](mailto:support@opencore.ro) instead of using the issue tracker.

## License

MIT license. Please see the [license file](LICENSE) for more information.


## TODO

* when enableing a module the permission must be added automatically for the user who made the action
* Example & Developer modules should be disabled by default
* display jobs & failed_jobs lists in developer module
* admin user permission checked on modify | POST / PUT / DELETE
* move occore user permission changes inside OpenCore project in a separate method
* remove getRegistry from Startup controller and let Framework handle it
* add ocmode(on refresh) multi line changes
* change header files comments
* add flash messages: https://github.com/laracasts/flash
* add menu builder: https://github.com/spatie/laravel-menu | https://github.com/harimayco/wmenu-builder | https://github.com/msurguy/laravel-shop-menu
* add activiy logs: https://github.com/spatie/laravel-activitylog
* add javascript validation: https://github.com/proengsoft/laravel-jsvalidation
* add persistent settings: https://github.com/edvinaskrucas/settings
* fix rotues shown on php artisan route:list
* add mail eclipse https://github.com/Qoraiche/laravel-mail-editor (maybe)
* add a MarketPlace for all OpenCore modules! IMPORTANT!

## Screenshots

<img alt="OpenCore home OpenCart 2x" src="https://opencore.me/images/screenshots/home-2x.png">
<img alt="OpenCore home OpenCart 3x" src="https://opencore.me/images/screenshots/home-3x.png">
<img alt="System Requirements" src="https://opencore.me/images/screenshots/system-requirements.png">
<img alt="OpenCore Modules management" src="https://opencore.me/images/screenshots/modules-management.png">
<img alt="Developer Module" src="https://opencore.me/images/screenshots/developer-module.png">
<img alt="Logs Dashboard" src="https://opencore.me/images/screenshots/logs-dashboard.png">
<img alt="Tasks" src="https://opencore.me/images/screenshots/tasks-module.png">
<img alt="Add Task" src="https://opencore.me/images/screenshots/tasks-module-add.png">
<img alt="Example Module" src="https://opencore.me/images/screenshots/example-module.png">
