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
    $router->get('clear-cache', 'ClearCacheController@index')->name('clear-cache');

    $router->get('modules', 'ModulesController@index')->name('modules.index');
    $router->get('modules/{module}', 'ModulesController@show')->name('modules.show');
    $router->post('modules/{module}/update', 'ModulesController@update')->name('modules.update');
    $router->post('modules/disable/{module}', 'ModulesController@disable')->name('modules.disable');
    $router->post('modules/enable/{module}', 'ModulesController@enable')->name('modules.enable');
    $router->post('modules/{module}/publish', 'ModulesController@publishAssets')->name('modules.publish');
});

/** Example of rewriting common OpenCart controllers */
/*Route::get('common/header', [
    'as' => 'common/header', 'uses' => 'ExampleController@index'
]);
Route::get('common/footer', [
    'as' => 'common/footer', 'uses' => 'ExampleController@index'
]);*/
