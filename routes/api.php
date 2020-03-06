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

Route::post('login', 'AuthController@login');
Route::group([
 
   'middleware' => ['api','jwt.verify']
   
 
], function ($router) { 
   
   Route::post('logout', 'AuthController@logout');
   Route::post('refresh', 'AuthController@refresh');
   Route::post('me', 'AuthController@me');   
   Route::get('users', 'UserController@index');
   Route::get('users/{id}', 'UserController@findUserById');
   Route::post('users', 'UserController@store');
});
// Route::post('users', 'UserController@store');