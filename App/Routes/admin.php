<?php

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

/*$router->get('/', [
    'as' => 'home', 'uses' => 'AdminExampleController@index'
]);*/

$router->get('common/footer', [
    'as' => 'home', 'uses' => 'AdminExampleController@index'
]);

$router->get('extension/module/awebcore1', [
    'as' => 'extension/module/awebcore1', 'uses' => 'AwebcoreController@index'
]);

$router->get('test', [
    'as' => 'test', 'uses' => 'AdminExampleController@index'
]);

$router->get('test2', [
    'as' => 'test', 'uses' => '2AdminExampleController@index'
]);

$router->get('mircea', 'mAdminExampleController@index');
