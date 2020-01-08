<?php
/*
 * Created on Wed Dec 17 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace Modules\Example\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Example\Example;

class ExampleController extends Controller
{
    function index()
    {
        $examples = Example::orderBy('created_at', 'asc')->get();

        return view("example::admin.index", compact('examples'));
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect(route('example::admin.index'))
                ->withErrors($validator)
                ->withInput();
        }

        $example = new Example;
        $example->name = $request->name;
        $example->save();

        return redirect(route('example::admin.index'));
    }

    public function destroy(Request $request, $id)
    {
        $example = Example::findOrFail($id);

        //$this->authorize('destroy', $example);

        $example->delete();

        return redirect(route('example::admin.index'));
    }
}
