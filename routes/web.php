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

    Route::group(['prefix'=>'user', 'middleware'=>['check.admtoken']], function(){
        Route::get('userlist', 'Admin\UserController@userlist');
        Route::get('deluser', 'Admin\UserController@deluser');
    });

    Route::group(['prefix'=>'order', 'middleware'=>['check.admtoken']], function(){
        Route:: get('orderlist', 'Admin\OrderController@orderlist');
        Route:: get('addorder', 'Admin\OrderController@addorder');
        Route:: get('exportorder', 'Admin\OrderController@exportorder');
        Route:: post('importorder', 'Admin\OrderController@importorder');
    });
});

Route::group(['prefix'=>'adm', 'middleware'=>['check.admtoken']], function(){
    Route::get('index', 'Admin\UserController@index');
    Route::get('signout', 'Admin\LoginController@signout');
});