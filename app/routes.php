<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', function() {
    return View::make('hello');
});
Route::get('/prueba', array('as' => 'prueba', 'uses' => 'PruebaAPIController@index'));
Route::group(array('prefix' => '/user'), function() {
    Route::post('/login', array('as' => 'user', 'uses' => 'usuariosController@login'));
    //Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
});