<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'IndexController@index')->name('index');
Route::post('/', 'IndexController@getLocation')->name('get_location');

Route::get('/bulk', 'IndexController@bulk')->name('bulk');
Route::post('/bulk', 'IndexController@getBulkLocation')->name('get_bulk_location');
