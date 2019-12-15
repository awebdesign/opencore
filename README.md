/*
 * Created on Fri Dec 13 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

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

ATTENTIE
most of facades have an I in front of the name: instead of URL you will need to use IURL. That's because OpenCart already using some of the names and in order to duplicates we had to rename it


we need to run stres tests
ab -n 500 -c 100 homestead.app/

Stept to use AwebCore
1. run: php artisan key:generate OR php -r "echo bin2hex(random_bytes(16));" => and change APP_KEY from core/.env file
2. run: php artisan migrate:install
3. run: php artisan migrate
