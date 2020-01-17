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

/**
 * Example overriding common/column_left controller
 * Uncomment the following line if you whant to test it
 */
//$router->get('common/column_left', 'ExampleController@commonColumnLeftReplace')->name('common.column_left');

/**
 * Example overriding catalog/category list section
 * Uncomment the following line if you whant to test it
 */
//$router->get('catalog/category', 'ExampleController@index')->name('catalog.category');
