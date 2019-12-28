<h2 align="center">
    OpenCore project - Laravel for OpenCart
</h2>
<p align="center">
by <a target="_blank" href="https://www.awebdesign.ro/en/">Aweb Design</a>
</p>
<br/><br/>

OpenCore is an application made on Laravel for OpenCart and let's you develop new features or overwrite the existing ones in Laravel instead of the old OpenCart framework. The application comes with build in features which will help the developers to create new modules or new functionallities for OpenCart ecommerce platform.

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
5. php artisan key:generate OR php -r "echo bin2hex(random_bytes(16));", copy the key shown there and change APP_KEY from core/.env file
6. php artisan migrate:install
7. php artisan migrate
8. copy OpenCart extension files from core/opencart-module/upload to you OpenCart root folder
9. go to OpenCart admin panel / extensions / extensions / modules, find OpenCore module and install it
```

## How does it works ?

let's take this file from OpenCart system: /admin/controller/common/header.php
    => IF exists in /core/app/Http/Controllers/Admin/common/header.php => we load it from here
		That means you can write your own core based on Laravel framework and integrate it into OpenCart
    => IF DOES NOT exists in /core/app/Http/Controllers/Admin/common/header.php => we load the system default one; same for model, lang, view, etc
	
Also you want to access a custom URL like yourwebsite.com/example
you can create you custom route in laravel /core/app/routes/catalog.php which will handle the request for /example page!

## Speed ?!
yes, it has! as long as your server has the minimum required for Laravel 5.8 to work everything should be good. There's no delay and on some opperations Laravel is even fasted than the default OpenCart system!

Also the application comes with a mysql shared connector which will help sharing the database active connection between OpenCart and Laravel/OpenCore

## Documentation

Documentation for OpenCore can be found on the [Official website](https://opencore.ro).

## Security

If you discover any security related issues, please email author email [support@opencore.ro](mailto:support@opencore.ro) instead of using the issue tracker.

## License

MIT license. Please see the [license file](LICENSE) for more information.


## TODO

* admin user permission checked on modify | POST / PUT / DELETE
* move occore user permission changes inside OpenCore project in a separate method
* remove getRegistry from Startup controller and let Framework handle it
* add ocmode(on refresh) multi line changes
* change header files comments
* change template directory structure
* change template design
* fix inserting CSS & JS header / footer tags correctly
* add flash messages: https://github.com/laracasts/flash
* add menu builder: https://github.com/spatie/laravel-menu | https://github.com/harimayco/wmenu-builder | https://github.com/msurguy/laravel-shop-menu
* add activiy logs: https://github.com/spatie/laravel-activitylog
* add javascript validation: https://github.com/proengsoft/laravel-jsvalidation
* add persistent settings: https://github.com/edvinaskrucas/settings
* fix rotues shown on php artisan route:list
* fix web routes -> split it into admin & catalog;
* add mail eclipse https://github.com/Qoraiche/laravel-mail-editor
