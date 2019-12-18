<?php

namespace AwebCore\App\Http\Controllers\Admin\Core;

use AwebCore\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
//use AwebCore\Startup;
use AwebCore\App\General\OcCore;
use AwebCore\App\Task;

class HomeController extends Controller
{
    function index(OcCore $occore)
    {
        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view("admin.core.home", compact('tasks')); //'header', 'column_left', 'footer',
    }

    function store(Request $request)
    {
        /**
         * Lumen does not support sessions out of the box, so the $errors view variable that is available
         * in every view in Laravel is not available in Lumen. Should validation fail, the $this->validate
         * helper will throw Illuminate\Validation\ValidationException with embedded JSON response that
         * includes all relevant error messages. If you are not building a stateless API that solely sends
         * JSON responses, you should use the full Laravel framework.
         *
         * https://lumen.laravel.com/docs/5.4/validation
         */

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        $redirect = 'core/home?' . (new OcCore())->getTokenStr();

        if ($validator->fails()) {
            return redirect($redirect)
                ->withInput()
                ->withErrors($validator);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->user_id = 0;
        $task->save();

        return redirect($redirect);
    }

    public function destroy(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        //$this->authorize('destroy', $task);

        $task->delete();

        return redirect('core/home?' . (new OcCore())->getTokenStr());
    }
}
