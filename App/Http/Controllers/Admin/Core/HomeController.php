<?php

namespace AwebCore\App\Http\Controllers\Admin\Core;

use AwebCore\App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function index() {
        //return response("Core Home controller");
        return view("Admin/Core/Home");
    }
}
