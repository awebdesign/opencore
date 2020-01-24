<?php
/*
 * Created on Wed Dec 29 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace App\Http\Controllers\Admin\Core\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ClearCacheController extends Controller
{
    function index()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return redirect(url()->previous())
        ->with('success', trans('system.cache.cleared'));
    }
}
