Aweb Core Project
by Aweb Design
https://www.awebdesign.ro/en/



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
    => IF exists in /awebcore/App/Controllers/Admin/common/header.php => we load it from here
    => IF DOES NOT exists in /awebcore/App/Controllers/Admin/common/header.php => we load the system default one
same for model, lang, view, etc

ATTENTION
most of facades have an I in front of the name: instead of URL you will need to use IURL. That's because OpenCart already using some of the names and in order to duplicates we had to rename it


we need to run stres tests
ab -n 500 -c 100 homestead.app/

Steps to use AwebCore

1. Install Git and Composer on your system
2. go to your OpenCart root folder, open a console and run the following commands step by step
3. git clone https://github.com/awebdesign/awebcore.git core
4. composer update
5. php artisan key:generate OR php -r "echo bin2hex(random_bytes(16));", copy the key shown there and change APP_KEY from core/.env file
6. php artisan migrate:install
7. php artisan migrate
8. copy OpenCart extension files from core/opencart-module/upload to you OpenCart root folder
9. go to OpenCart admin panel / extensions / extensions / modules, find AwebCore module and install it

Enjoy!

