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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 未添加中间件身份验证
Route::group(['prefix'=>'test'], function(){
    Route::get('index', 'Api\TestController@index');
    Route::any('redistest', 'Api\TestController@redistest');
    Route::post('savefile', 'Api\TestController@savefile');
    Route::post('test123', 'Api\TestController@test123');

});

// 添加token验证
Route::group(['prefix'=>'test', 'middleware'=>'check.apitoken'], function(){
    Route::post('gettoken', 'Api\TestController@gettoken');
    Route::post('includetest', 'Api\TestController@includetest');
});
