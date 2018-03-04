<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function() {
    Route::get('/horse', 'DataController@getHorseName');
    Route::post('/data', 'DataController@getDataGraph');
    Route::post('/login', 'ApiAuthController@login');
    Route::post('/register', 'ApiAuthController@postRegister');
    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::post('/show_name', 'ApiAuthController@showName');
    });
});
