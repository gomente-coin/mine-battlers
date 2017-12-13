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

Route::middleware('auth:api')->group(function () {
    Route::get('/pow/challenge', 'Api\PowController@getChallenge');
    Route::post('/pow/response', 'Api\PowController@postResponse');

    Route::get('/ranking/top-100-miners', 'Api\RankingController@getTop100Miners');
});
