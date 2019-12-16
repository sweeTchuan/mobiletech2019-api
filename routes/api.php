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
// Route::post('/user', 'UserController@fn_userAction');

// UserController
// user => create_user
// user => login_user
// user => update_user : update user field without profile picture path
// user => update_user_profile_pic  : update profile image and save image to storage
// user => update_user_profile_all  : update user table for all field and save image to storage
Route::post('/user', 'UserController@fn_userAction')->middleware('cors');

// PostController
// Add new photo post
Route::post('/newpost', 'PostController@fn_newPost')->middleware('cors');
// load all posts
Route::post('/getposts','PostController@fn_getPosts')->middleware('cors');

// same as getposts, testing
// Route::post('/allpostsdetails','PostController@fn_showAlltPostsWithUser')->middleware('cors');

// testing upload images
// Route::get('/posts','PostController@index')->middleware('cors');
// Route::post('/image', 'ImageController@fn_uploadImage')->middleware('cors');


// testing simple API
//Route::get('/testingg', 'UserController@fn_testGetAPI');
// Route::get('/testingg', 'UserController@fn_testGetAPI')->middleware('testcors');
Route::get('/testingg', 'UserController@fn_testGetAPI')->middleware('cors');
// Route::post('/testingp', 'UserController@fn_testPostAPI');
Route::post('/testingp', 'UserController@fn_testPostAPI')->middleware('cors');
