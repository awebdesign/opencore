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
