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
        Artisan::call('opencore:clear-cache');

        return redirect(url()->previous())
        ->with('success', trans('system.cache.cleared'));
    }
}
