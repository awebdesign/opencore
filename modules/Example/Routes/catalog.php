<?php

/*
|--------------------------------------------------------------------------
| Catalog Routes
|--------------------------------------------------------------------------
|
| Here is where you can register catalog routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Catalog" middleware group. Now create something great!
|
*/

$router->get('example', [
    'as' => 'example', 'uses' => 'ExampleController@index'
]);

$router->get('example/json', [
    'as' => 'example.json', 'uses' => 'ExampleController@json'
]);

/**
 * Example overriding common/column_left controller
 * Uncomment the following line if you whant to test it
 */
/*$router->get('common/column_left', [
    'as' => 'common.column_left', 'uses' => 'ExampleController@commonColumnLeftReplace'
]);*/
