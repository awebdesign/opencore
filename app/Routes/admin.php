<?php
/*
 * Created on Fri Dec 13 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->name('core.')->prefix('core')->namespace('Core')->group(function ($router) {
    $router->get('home', 'HomeController@index')->name('home');
    $router->post('home/store', 'HomeController@store')->name('home.store');
    $router->delete('/home/{id}', 'HomeController@destroy')->name('home.destroy');

    $router->get('requirements', 'RequirementsController@index')->name('requirements');

    // admin::core.dashboard
    $router->get('log-viewer', 'LogViewerController@index')->name('logs.dashboard');

    $router->prefix('logs')->name('logs.')->group(function ($router) {
        $router->get('/', 'LogViewerController@listLogs')
            ->name('list'); // admin::core.logs.list

        $router->delete('delete', 'LogViewerController@delete')
            ->name('delete'); // admin::core.logs.delete

        $router->prefix('{date}')->group(function ($router) {
            $router->get('/', 'LogViewerController@show')
                ->name('show'); // admin::core.logs.show

            $router->get('download', 'LogViewerController@download')
                ->name('download'); // admin::core.logs.download

            $router->get('{level}', 'LogViewerController@showByLevel')
                ->name('filter'); // admin::core.logs.filter

            $router->get('{level}/search', 'LogViewerController@search')
                ->name('search'); // admin::core.logs.search
        });
    });
});

/*$router->get('common/header', [
    'as' => 'common/header', 'uses' => 'ExampleController@index'
]);

$router->get('common/footer', [
    'as' => 'common/footer', 'uses' => 'ExampleController@index'
]);*/

/* $router->get('extension/module/awebcore', [
    'as' => 'extension/module/awebcore', 'uses' => 'ExampleController@index'
]); */

/* $router->get('example', [
    'as' => 'example', 'uses' => 'ExampleController@index'
]); */
