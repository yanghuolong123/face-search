<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('baidu/token','BaiduController@getAccessToken');
Route::get('baidu/detect','BaiduController@detect');
Route::get('baidu/addUser','BaiduController@addUser');
Route::get('baidu/search','BaiduController@search');


Route::get('tencent/detect', 'TencentController@detect');
Route::get('tencent/addUser', 'TencentController@addUser');
Route::get('tencent/search', 'TencentController@search');


Route::get('tencentCloud/detect', 'TencentCloudController@detect');
Route::get('tencentCloud/addUser', 'TencentCloudController@addUser');
Route::get('tencentCloud/search', 'TencentCloudController@search');
