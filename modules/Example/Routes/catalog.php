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
