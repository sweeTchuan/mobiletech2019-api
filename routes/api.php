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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users', 'UserController@fn_getUsers');
Route::post('/user', 'UserController@fn_userAction')->middleware('cors');
// Route::post('/user', 'UserController@fn_userAction');
Route::get('/posts','PostController@index');
Route::post('/image', 'ImageController@fn_uploadImage');//->middleware('cors');

// testing purposes
//Route::get('/testingg', 'UserController@fn_testGetAPI');
// Route::get('/testingg', 'UserController@fn_testGetAPI')->middleware('testcors');
Route::get('/testingg', 'UserController@fn_testGetAPI')->middleware('cors');

// Route::post('/testingp', 'UserController@fn_testPostAPI');
Route::post('/testingp', 'UserController@fn_testPostAPI')->middleware('cors');
