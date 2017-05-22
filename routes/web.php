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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'adm'], function(){
    Route::get('login', 'Admin\LoginController@login');
    Route::post('dologin', 'Admin\LoginController@dologin');
    Route::get('index', 'Admin\UserController@index');



    Route::group(['prefix'=>'user'], function(){
        Route::get('userlist', 'Admin\UserController@userlist');
        Route::get('deluser', 'Admin\UserController@deluser');
    });
});