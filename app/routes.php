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
Route::group(array('prefix' => '/administracion'), function() {
    Route::group(array('prefix' => '/agrupaciones'), function() {
        Route::get('/', array('as' => 'muestra_agrupaciones', 'uses' => 'AgrupacionesController@index'));
        Route::post('/crear', array('as' => 'crear_agrupacion', 'uses' => 'AgrupacionesController@create'));
    });
});
Route::group(array('prefix' => '/adeudos'), function() {
    Route::get('/todos_periodo', array('as' => 'Adeudos_por_periodo', 'uses' => 'AdeudosControler@show_by_periodo'));
});
Route::group(array('prefix' => '/user'), function() {
    Route::post('/login', array('as' => 'user', 'uses' => 'usuariosController@login'));
    //Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
});
Route::group(array('prefix' => '/caja'), function() {
    Route::group(array('prefix' => '/caja'), function() {
        Route::post('/banco/subir', array('as' => 'referencia', 'uses' => 'ReferenciasController@leer_archivo_banco'));
        //Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
    });
});
