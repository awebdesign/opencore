<?php

namespace AwebCore\App\Http\Controllers\Catalog;

use AwebCore\App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    function index() {
        return response("Catalog ExampleController CONTENT HERE");
    }

    function json() {
        return response()->json(['name' => 'AwebCore', 'by' => 'Aweb Design']);
    }
}
