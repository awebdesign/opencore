<?php
/*
 * Created on Fri Jan 24 2020 by DaRock
 *
 * Copyright (c) Aweb Design
 * https://www.awebdesign.ro
 */

namespace App\Http\Controllers\Admin\Core\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use OpenCore\Support\Entities\OpencoreRoute;

class RegisterRoutesController extends Controller
{
    function index()
    {
        $routes = OpencoreRoute::orderBy('uri')
        ->orderBy('method')
        ->paginate(config('opencore.paginate.admin'))
        ->appends(request()->except('route'));

        return view('admin.core.system.routes', compact('routes'));
    }

    function enable(int $id)
    {
        $route = OpencoreRoute::findOrFail($id);
        $route->status = 1;
        $route->save();

        return redirect(url()->previous())
        ->with('success', trans('system.routes.enabled'));
    }

    function disable(int $id)
    {
        $route = OpencoreRoute::findOrFail($id);
        $route->status = 0;
        $route->save();

        return redirect(url()->previous())
        ->with('success', trans('system.routes.disabled'));
    }

    function register()
    {
        Artisan::call('opencore:register-routes');

        return redirect(url()->previous())
        ->with('success', trans('system.routes.registered'));
    }
}
