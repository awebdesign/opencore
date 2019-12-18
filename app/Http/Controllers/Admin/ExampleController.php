<?php
/*
 * Created on Wed Dec 17 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore\App\Http\Controllers\Admin;

use AwebCore\App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    function index() {
        return response("Admin ExampleController CONTENT HERE");
    }
}
