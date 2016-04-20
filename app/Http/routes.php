<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api'], function(){
  Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
    Route::post('authenticate', 'AuthController@authenticate');
    Route::post('get-user', 'AuthController@getUser');
  });

  Route::group(['prefix' => 'categories', 'namespace' => 'Category'], function(){
    Route::get('/', 'CategoryController@getCategories');
  });
});
