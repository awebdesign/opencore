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

Route::name('core.')->prefix('core')->namespace('Core')->group(function ($router) {
    $router->get('home', 'HomeController@index')->name('home');

    $router->get('requirements', 'RequirementsController@index')->name('requirements');

    $router->name('tasks.')->prefix('tasks')->namespace('Tasks')->group(function ($router) {
        $router->get('/', 'TasksController@index')->name('dashboard');

        $router->get('create', 'TasksController@create')->name('create');
        $router->post('create', 'TasksController@store');

        $router->get('export', 'ExportTasksController@index')->name('export');
        $router->post('import', 'ImportTasksController@index')->name('import');

        $router->get('{task}', 'TasksController@view')->name('view');

        $router->get('{task}/edit', 'TasksController@edit')->name('edit');
        $router->post('{task}/edit', 'TasksController@update');

        $router->delete('{task}', 'TasksController@destroy')->name('delete');

        $router->post('status', 'ActiveTasksController@store')->name('activate');
        $router->delete('status/{id}', 'ActiveTasksController@destroy')->name('deactivate');

        $router->get('{task}/execute', 'ExecuteTasksController@index')->name('execute');
    });

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

    $router->get('clear-cache', 'ClearCacheController@index')->name('clear-cache');
});

/** Example Controller */
Route::get('example', 'ExampleController@index')->name('example');
Route::post('example/store', 'ExampleController@store')->name('example.store');
Route::delete('example/{id}', 'ExampleController@destroy')->name('example.destroy');

/** Example of rewriting common OpenCart controllers */
/*Route::get('common/header', [
    'as' => 'common/header', 'uses' => 'ExampleController@index'
]);
Route::get('common/footer', [
    'as' => 'common/footer', 'uses' => 'ExampleController@index'
]);*/
