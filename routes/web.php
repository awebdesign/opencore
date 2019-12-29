<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$namespace = 'App\Http';

Route::middleware(['web', $namespace . '\Middleware\AdminMiddleware'])
    ->prefix('admin')
    ->name('admin::')
    ->namespace('Admin')
    ->group(function ($router) {
        require __DIR__ . '/../app/Routes/admin.php';
    });

Route::middleware(['web', $namespace . '\Middleware\CatalogMiddleware'])
    ->name('catalog::')
    ->namespace('Catalog')
    ->group(function ($router) {
        require __DIR__ . '/../app/Routes/catalog.php';
    });