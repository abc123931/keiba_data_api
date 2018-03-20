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

    // Data用
    Route::group(['prefix' => 'data'], function() {
        Route::get('/horse', 'data\HorseController@getHorseName');
        Route::get('/race', 'data\RaceController@getRaceName');
        Route::post('/graph/horse', 'data\HorseController@getDataGraph');
        Route::post('/graph/race', 'data\RaceController@getDataGraph');
    });

    Route::post('/login', 'ApiAuthController@login');
    Route::post('/register', 'ApiAuthController@postRegister');
    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::post('/show_name', 'ApiAuthController@showName');
    });
});
