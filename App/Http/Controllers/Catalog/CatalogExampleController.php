<?php

namespace AwebCore\App\Http\Controllers\Catalog;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use AwebCore\App\Http\Controllers\Controller;

class CatalogExampleController extends Controller
{
    function index() {
        return response("CatalogExampleController CONTENT HERE");
    }
}
