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

Route::view('/', 'welcome')->name('welcome');

Route::get('/login', 'Auth\LoginController@login')->name('login');
Route::get('/login/twitter', 'Auth\LoginController@redirectToProvider');
Route::get('/login/twitter/callback', 'Auth\LoginController@handleProviderCallback');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/home', 'home')->name('home');
    Route::view('/mining', 'mining')->name('mining');
    Route::view('/ranking', 'ranking')->name('ranking');
});
