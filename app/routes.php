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
        Route::post('/agregar', array('as' => 'crear_agrupacion', 'uses' => 'AgrupacionesController@create'));
    });
});
Route::group(array('prefix' => '/adeudos'), function() {
    Route::get('/todos_periodo', array('as' => 'Adeudos_por_periodo', 'uses' => 'AdeudosController@show_by_periodo'));
});
Route::get('/prueba', array('as' => 'prueba_api', 'uses' => 'PruebaAPIController@index'));
Route::group(array('prefix' => '/user'), function() {
    Route::post('/login', array('as' => 'user', 'uses' => 'usuariosController@login'));
    //Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
});
Route::group(array('prefix' => '/caja'), function() {
    Route::group(array('prefix' => '/caja'), function() {
        Route::post('/banco/subir', array('as' => 'referencia', 'uses' => 'ReferenciasController@leer_archivo_banco'));
    });
    Route::group(array('prefix' => '/conceptos'), function() {
        Route::get('/conceptos/', array('as' => 'muestra_todos_conceptos', 'uses' => 'ConceptosController@index'));
        Route::get('/conceptos/expediente', array('as' => 'muestra_concepto', 'uses' => 'ConceptosController@show'));
        Route::get('/conceptos/expediente/subconceptos', array('as' => 'muestra_subconceptos_concepto', 'uses' => 'ConceptosController@show_subconceptos'));
        Route::post('/conceptos/agregar', array('as' => 'crear_concepto', 'uses' => 'ConceptosController@create'));
        Route::put('/conceptos/guardar', array('as' => 'actualizar_valor_concepto', 'uses' => 'ConceptosController@update'));
        Route::delete('/conceptos/eliminar', array('as' => 'eliminar_concepto', 'uses' => 'ConceptosController@destroy'));
    });
    Route::group(array('prefix' => '/subconceptos'), function() {
        Route::get('/subconceptos/', array('as' => 'muestra_todos_subconceptos', 'uses' => 'Sub_ConceptosController@index'));
        Route::get('/subconceptos/actualiza', array('as' => 'muestra_subconcepto', 'uses' => 'Sub_ConceptosController@show'));
      #  Route::get('/subconceptos/actualiza/subconceptos', array('as' => 'muestra_subconceptos_concepto', 'uses' => 'ConceptosController@show_subconceptos'));
        Route::post('/subconceptos/agregar', array('as' => 'crear_subconcepto', 'uses' => 'Sub_ConceptosController@create'));
        Route::put('/subconceptos/guardar', array('as' => 'actualizar_valor_subconcepto', 'uses' => 'Sub_ConceptosController@update'));
        Route::delete('/subconceptos/eliminar', array('as' => 'eliminar_subconcepto', 'uses' => 'Sub_ConceptosController@destroy'));
    });
});
Route::get('/periodos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@periodos'));
