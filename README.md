<h1 align="center"><img src="https://opencore.me/images/logo/opencore-logo-large-transparent.png" width="180" alt="OpenCore"></h1>

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

## Requirements

* OpenCart 2.x / 3.x

## Installation

``` bash
1. Install Git and Composer on your system
2. go to your OpenCart root folder, open a console and run the following commands step by step
3. git clone https://github.com/opencorero/opencore.git core
4. composer update
5. php artisan key:generate
6. php artisan migrate:install
7. php artisan migrate
8. copy OpenCart extension files from core/opencart-module/upload to you OpenCart root folder
9. go to OpenCart admin panel / extensions / extensions / modules, find OpenCore module and install it
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

<img src="https://opencore.me/images/screenshots/screenshot-opencore-home.png">
<img src="https://opencore.me/images/screenshots/screenshot-opencore-home-2.png">
