<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin panel routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Admin" middleware group. Now create something great!
|
*/

Route::prefix('developer')->group(function ($router) {
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

    // admin::developer.logs.dashboard
    $router->get('log-viewer', 'LogViewerController@index')->name('logs.dashboard');

    $router->prefix('logs')->name('logs.')->group(function ($router) {
        $router->get('/', 'LogViewerController@listLogs')
            ->name('list'); // admin::developer.logs.list

        $router->delete('delete', 'LogViewerController@delete')
            ->name('delete'); // admin::developer.logs.delete

        $router->prefix('{date}')->group(function ($router) {
            $router->get('/', 'LogViewerController@show')
                ->name('show'); // admin::developer.logs.show

            $router->get('download', 'LogViewerController@download')
                ->name('download'); // admin::developer.logs.download

            $router->get('{level}', 'LogViewerController@showByLevel')
                ->name('filter'); // admin::developer.logs.filter

            $router->get('{level}/search', 'LogViewerController@search')
                ->name('search'); // admin::developer.logs.search
        });
    });
});
