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
Route::get('/', [
        'uses' => '\App\Http\Controllers\EndpointController@index',
        'as' => 'home'
    ]
);

Route::get('/error', [
        'uses' => '\App\Http\Controllers\ErrorController@index',
        'as' => 'error'
    ]
)->middleware(['lang']);
