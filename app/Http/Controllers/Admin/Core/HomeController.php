<?php
/*
 * Created on Wed Dec 17 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index()
    {
        return view("admin.core.home");
    }
}
