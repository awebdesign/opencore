<?php

/*
|--------------------------------------------------------------------------
| Catalog Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', [
    'as' => 'home', 'uses' => 'CatalogExampleController@index'
]);
$router->get('extension/module/awebcore', 'CatalogExampleController@index');
//$app->get('common/header', 'CatalogExampleController@index');