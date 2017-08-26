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
    //return view('welcome');
    return view('admin/login');
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

    Route::group(['prefix'=>'power'], function(){
        Route::get('rolelist', 'Admin\PowerController@rolelist');
        Route::get('addrole', 'Admin\PowerController@addrole');
        Route::post('doaddrole', 'Admin\PowerController@doaddrole');
        Route::get('editrole', 'Admin\PowerController@editrole');
        Route::post('doeditrole', 'Admin\PowerController@doeditrole');
        Route::get('delrole', 'Admin\PowerController@delrole');

        Route::get('actionlist', 'Admin\PowerController@actionlist');
        Route::get('addaction', 'Admin\PowerController@addaction');
        Route::post('doaddaction', 'Admin\PowerController@doaddaction');
        Route::get('delaction', 'Admin\PowerController@delaction');
    });
});

Route::group(['prefix'=>'adm', 'middleware'=>['check.admtoken']], function(){
    Route::get('index', 'Admin\UserController@index');
    Route::get('signout', 'Admin\LoginController@signout');
});