<?php
/*
 * Created on Wed Dec 17 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace Modules\Example\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;

class ExampleController extends Controller
{
    function index() {
        return response("Catalog ExampleController CONTENT HERE");
    }

    function json() {
        return response()->json(['name' => 'OpenCore', 'by' => 'Aweb Design']);
    }

    /**
     * Example method for replacing common/column_left or any other partia content
     * You can rewrite any default route based on routes/catalog.php.
     * If the route is already used by the system, that means you will override it
     *
     * @return Response object
     */
    public function commonColumnLeftReplace() {
        return response("COLUMN LEFT REPLACE EXAMPLE");
    }
}
