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

$router->get('example', 'ExampleController@index')->name('index');
$router->post('example/store', 'ExampleController@store')->name('store');
$router->delete('example/{id}', 'ExampleController@destroy')->name('destroy');
