<?php

namespace AwebCore\App\Http\Controllers\Admin\Core;

use AwebCore\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use AwebCore\Startup;
use AwebCore\App\General\OcCore;
use AwebCore\App\Task;

class HomeController extends Controller
{
    function index()
    {
        $loader = Startup::getRegistry('load');

        $header = $loader->controller('common/header');
        $column_left = $loader->controller('common/column_left');
        $footer = $loader->controller('common/footer');

        $token = (new OcCore())->getTokenStr();

        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view("admin/core/home", compact('header', 'column_left', 'footer', 'tasks', 'token'));
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        $redirect = '/admin/core/home?' . (new OcCore())->getTokenStr();
        if ($validator->fails()) {
            //return $validator->errors();
            return redirect($redirect)
                ->withInput()
                ->withErrors($validator);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return redirect($redirect);
    }

    public function destroy(Request $request, Task $task)
    {
        //$this->authorize('destroy', $task);

        $task->delete();

        return redirect('/admin/core/home?' . (new OcCore())->getTokenStr());
    }
}
