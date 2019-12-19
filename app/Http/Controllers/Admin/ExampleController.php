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
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use AwebCore\App\Example;

class ExampleController extends Controller
{
    function index()
    {
        $examples = Example::orderBy('created_at', 'asc')->get();

        return view("admin.example.index", compact('examples'));
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin::example'))
                ->withErrors($validator)
                ->withInput();
        }

        $example = new Example;
        $example->name = $request->name;
        $example->save();

        return redirect(route('admin::example'));
    }

    public function destroy(Request $request, $id)
    {
        $example = Example::findOrFail($id);

        //$this->authorize('destroy', $example);

        $example->delete();

        return redirect(route('admin::example'));
    }
}
