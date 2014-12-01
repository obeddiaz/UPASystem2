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
        # /administracion/agrupaciones/
        Route::get('/clasificaciones', array('as' => 'muestra_agrupaciones', 'uses' => 'AgrupacionesController@show_planes'));
        # /administracion/agrupaciones/clasificaciones
        Route::post('/agregar', array('as' => 'crear_agrupacion', 'uses' => 'AgrupacionesController@create'));
        Route::group(array('prefix' => '/alumnos_paquete'), function() {
            Route::post('/agregar', array('as' => 'agrega_alumos_paquete', 'uses' => 'AdeudosController@create'));
        });
    });
    Route::group(array('prefix' => '/generales'), function() {
        Route::group(array('prefix' => '/planes_de_pago'), function() {
            Route::get('/todos', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@index'));
            Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Planes_de_pagoController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Planes_de_pagoController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Planes_de_pagoController@destroy'));
            Route::group(array('prefix' => '/paquete_plandepago'), function() {
                Route::get('/', array('as' => 'muestra_planes', 'uses' => 'PaqueteController@show_nivel_periodo'));
                Route::post('/agregar', array('as' => 'agrega_paquetes', 'uses' => 'PaqueteController@create'));
                Route::post('/agregar_subconceptos', array('as' => 'agrega_subconcepto_paquete', 'uses' => 'PaqueteController@create_subconcepto'));
            });
        });
        Route::group(array('prefix' => '/becas'), function() {
            Route::get('/', array('as' => 'muestra_becas', 'uses' => 'BecasController@index'));
            Route::get('/alumnos/beca', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show_alumno'));
            Route::get('/alumnos/nobeca', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show_alumno'));
            Route::get('/expediente', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show'));
            Route::post('/agregar', array('as' => 'crear_becas', 'uses' => 'BecasController@create'));
            Route::post('/alumnos/agregar', array('as' => 'asignar_becas', 'uses' => 'BecasController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update'));
            Route::put('/alumnos/asignar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update'));
            Route::put('/alumnos/cancelar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_becas', 'uses' => 'BecasController@destroy'));
            Route::delete('/alumnos/eliminar', array('as' => 'eliminar_becas', 'uses' => 'BecasController@destroy'));
        });
    });
});
Route::group(array('prefix' => '/adeudos'), function() {
    Route::get('/todos_periodo', array('as' => 'Adeudos_por_periodo', 'uses' => 'AdeudosController@show_by_periodo'));
    Route::get('/alumno', array('as' => 'Adeudos_por_alumno_periodo', 'uses' => 'AdeudosController@show_adeudos_alumno'));
});
Route::get('/prueba', array('as' => 'prueba_api', 'uses' => 'PruebaAPIController@index'));
Route::group(array('prefix' => '/caja'), function() {
    Route::group(array('prefix' => '/caja'), function() {
        Route::get('/', array('as' => 'referencia', 'uses' => 'ReferenciasController@index'));
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
        #   Route::get('/subconceptos/actualiza/subconceptos', array('as' => 'muestra_subconceptos_concepto', 'uses' => 'ConceptosController@show_subconceptos'));
        Route::post('/subconceptos/agregar', array('as' => 'crear_subconcepto', 'uses' => 'Sub_ConceptosController@create'));
        Route::put('/subconceptos/guardar', array('as' => 'actualizar_valor_subconcepto', 'uses' => 'Sub_ConceptosController@update'));
        Route::delete('/subconceptos/eliminar', array('as' => 'eliminar_subconcepto', 'uses' => 'Sub_ConceptosController@destroy'));
    });
    Route::group(array('prefix' => '/descuentos'), function() {
        Route::get('/', array('as' => 'muestra_todos_descuentos', 'uses' => 'DescuentosController@index'));
        Route::get('/actualiza', array('as' => 'muestra_descuentos', 'uses' => 'DescuentosController@show'));
        #   Route::get('/descuentos/actualiza/descuentos', array('as' => 'muestra_descuentos_concepto', 'uses' => 'ConceptosController@show_descuentos'));
        Route::post('/agregar', array('as' => 'crear_descuento', 'uses' => 'DescuentosController@create'));
        Route::put('/guardar', array('as' => 'actualizar_valor_descuento', 'uses' => 'DescuentosController@update'));
        Route::delete('/eliminar', array('as' => 'eliminar_descuento', 'uses' => 'DescuentosController@destroy'));
        Route::get('/expediente', array('as' => 'descuento_adeudo', 'uses' => 'DescuentosController@expediente'));
    });
});
Route::group(array('prefix' => '/user'), function() {
    Route::post('/login', array('as' => 'user', 'uses' => 'usuariosController@login'));
    Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
});
Route::group(array('prefix' => '/tipo_adeudo'), function() {
    Route::get('/', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@index'));
    Route::get('/expediente', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@show'));
    Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Tipo_adeudoController@create'));
    Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Tipo_adeudoController@update'));
    Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Tipo_adeudoController@destroy'));
});
Route::group(array('prefix' => '/tipo_pagos'), function() {
    Route::get('/', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudo@index'));
    Route::get('/expediente', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudo@show'));
    Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Tipo_adeudo@create'));
    Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Tipo_adeudo@update'));
    Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Tipo_adeudo@destroy'));
});
Route::group(array('prefix' => '/bancos'), function() {
    Route::get('/', array('as' => 'muestra_bancos', 'uses' => 'BancosController@index'));
    Route::post('/agregar', array('as' => 'crear_banco', 'uses' => 'BancosController@create'));
    Route::put('/guardar', array('as' => 'actualizar_valor_banco', 'uses' => 'BancosController@update'));
    Route::delete('/eliminar', array('as' => 'eliminar_banco', 'uses' => 'BancosController@destroy'));
});
Route::group(array('prefix' => '/cuentas'), function() {
    Route::get('/', array('as' => 'muestra_cuentas', 'uses' => 'CuentasController@index'));
    Route::post('/agregar', array('as' => 'crear_cuenta', 'uses' => 'CuentasController@create'));
    Route::put('/guardar', array('as' => 'actualizar_valor_cuenta', 'uses' => 'CuentasController@update'));
    Route::delete('/eliminar', array('as' => 'eliminar_cuenta', 'uses' => 'CuentasController@destroy'));
});
Route::group(array('prefix' => '/resbancaria'), function() {
    Route::get('/', array('as' => 'muestra_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@index'));
    Route::post('/agregar', array('as' => 'crear_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@create'));
    Route::put('/guardar', array('as' => 'actualizar_valor_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@update'));
    Route::delete('/eliminar', array('as' => 'eliminar_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@destroy'));
});
Route::group(array('prefix' => '/referencias'), function() {
    Route::post('/agregar', array('as' => 'crear_referencia', 'uses' => 'ReferenciasController@create'));
});
Route::get('/periodos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@periodos'));
Route::get('/alumnos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@alumnos'));
Route::get('/grupos/', array('as' => 'muestra_todos_grupos', 'uses' => 'APIServicesController@grupos'));
