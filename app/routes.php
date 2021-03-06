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
header('Access-Control-Allow-Origin: *');
Route::get('/', function() {
    return View::make('app/index',array('app_version'=>'1.0'));
});
Route::group(array('before' => 'auth'), function() {
    Route::group(array('before' => 'permisos'), function() {
        Route::group(array('prefix' => '/administracion'), function() {
            Route::group(array('prefix' => '/agrupaciones'), function() {
                Route::get('/', array('as' => 'muestra_agrupaciones', 'uses' => 'AgrupacionesController@index'));
                # /administracion/agrupaciones/
                Route::get('/clasificaciones', array('as' => 'muestra_agrupaciones', 'uses' => 'AgrupacionesController@show_planes'));
                # /administracion/agrupaciones/clasificaciones
                Route::post('/agregar', array('as' => 'crear_agrupacion', 'uses' => 'AgrupacionesController@create'));
                Route::group(array('prefix' => '/alumnos_paquete'), function() {
                    Route::post('/agregar', array(
                            'as' => 'agrega_alumos_paquete', 
                            'uses' => 'AdeudosController@create')
                    );
                    Route::get('/agregar_array', array(
                            'as' => 'agrega_alumos_paquete_array', 
                            'uses' => 'AdeudosController@create_array')
                    );
                    Route::post('/agregar_archivo', array('as' => 'agrega_alumos_paquete', 'uses' => 'AdeudosController@create_byFile'));
                });
            });
            Route::group(array('prefix' => '/generales'), function() {
                Route::group(array('prefix' => '/planes_de_pago'), function() {
                    Route::get('/todos', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@index'));
                    Route::get('/expediente', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@show'));
                    Route::get('/alumnos_paquete_alumno', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@show_paquete_alumno'));
                    Route::get('/alumnos_no_paquete_alumno', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@show_no_paquete_alumno'));
                    Route::get('/todos_agrupaciones', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@show_byAgrupaciones'));
                    Route::get('/subconceptos', array('as' => 'muestra_planes', 'uses' => 'Planes_de_pagoController@show_subconceptos'));
                    Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Planes_de_pagoController@create'));
                    Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Planes_de_pagoController@update'));
                    Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Planes_de_pagoController@destroy'));
                    Route::group(array('prefix' => '/paquete_plandepago'), function() {
                        Route::get('/', array('as' => 'muestra_planes', 'uses' => 'PaqueteController@show_nivel_periodo'));
                        Route::get('/sc_plan', array('as' => 'muestra_planes', 'uses' => 'PaqueteController@show_subconceptos'));
                        Route::post('/agregar', array('as' => 'agrega_paquetes', 'uses' => 'PaqueteController@create'));
                        Route::post('/agregar_subconceptos', array('as' => 'agrega_subconcepto_paquete', 'uses' => 'PaqueteController@create_subconcepto'));
                        #Route::put('/guardar', array('as' => 'actualizar_valor_paquete', 'uses' => 'PaqueteController@update_subconceptos_paquetes'));
                        Route::delete('/eliminar', array('as' => 'eliminar_paquete', 'uses' => 'PaqueteController@destroy'));
                    });
                });
                Route::group(array('prefix' => '/registro_pago'), function() {
                    Route::get('/', array('as' => 'muestra_registro_pagos', 'uses' => 'RegistroPagoController@index'));
                    Route::get('/expediente', array('as' => 'muestra_registro_pagos_uno', 'uses' => 'RegistroPagoController@show'));
                    Route::post('/agregar', array('as' => 'crear_registro_pago', 'uses' => 'RegistroPagoController@create'));
                    Route::put('/guardar', array('as' => 'editar_registro_pago', 'uses' => 'RegistroPagoController@update'));
                    Route::delete('/eliminar', array('as' => 'eliminar_cuenta', 'uses' => 'RegistroPagoController@destroy'));
                });
                Route::group(array('prefix' => '/becas'), function() {
                    Route::get('/', array('as' => 'muestra_becas', 'uses' => 'BecasController@index'));
                    Route::get('/alumnos/beca', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show_alumno'));
                    Route::get('/alumnos/nobeca', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show_alumno_nobeca'));
                    Route::get('/expediente', array('as' => 'muestra_detalles_becas', 'uses' => 'BecasController@show'));
                    Route::get('/catalogos', array('as' => 'muestra_catalogos', 'uses' => 'BecasController@show_catalogos'));
                    Route::get('/catalogos/reporte', array('as' => 'muestra_catalogos', 'uses' => 'BecasController@show_alumno_reporte'));
                    Route::post('/agregar', array('as' => 'crear_becas', 'uses' => 'BecasController@create'));
                    Route::post('/alumnos/agregar', array('as' => 'asignar_becas', 'uses' => 'BecasController@create_alumno'));
                    Route::post('/subir', array('as' => 'referencia', 'uses' => 'BecasController@create_alumno_file'));
                    Route::put('/guardar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update'));
                    Route::put('/alumnos/asignar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update_alumno_activar'));
                    Route::put('/alumnos/cancelar', array('as' => 'actualizar_valor_becas', 'uses' => 'BecasController@update_alumno_desactivar'));
                    Route::delete('/eliminar', array('as' => 'eliminar_becas', 'uses' => 'BecasController@destroy'));
                    Route::delete('/alumnos/eliminar', array('as' => 'eliminar_becas', 'uses' => 'BecasController@destroy_alumno'));

                    Route::get('/reporte', array('as' => 'muestra_becas', 'uses' => 'BecasController@reporte'));
                    Route::get('/suspender_mes', array('as' => 'muestra_becas', 'uses' => 'BecasController@suspender_beca_mes'));
                });
            });
        });
        Route::group(array('prefix' => '/adeudos'), function() {
            Route::get('/todos_periodo', array('as' => 'Adeudos_por_periodo', 'uses' => 'AdeudosController@show_by_periodo'));
            Route::get('/adeudos_reporte', array('as' => 'Adeudos_para_reporte', 'uses' => 'AdeudosController@show_adeudos_reporte'));
            Route::get('/adeudos_reporte_ordenado', array('as' => 'Adeudos_para_reporte', 'uses' => 'AdeudosController@show_adeudos_reporte_ordenado'));
            Route::get('/alumno', array('as' => 'Adeudos_por_alumno_periodo', 'uses' => 'AdeudosController@show_adeudos_alumno'));
            #Route::get('/crear_reporte', array('as' => 'Crear_reporte', 'uses' => 'AdeudosController@create_reporte'));
            Route::get('/crear_reporte', array('as' => 'Crear_reporte', 'uses' => 'AdeudosController@create_reporte_key'));
            Route::post('/agregar_subconcepto', array('as' => 'agregar_subconcepto', 'uses' => 'AdeudosController@createSubconcepto'));
            Route::put('/guardar', array('as' => 'actualizar_adeudo', 'uses' => 'AdeudosController@update'));
            Route::put('/status', array('as' => 'actualizar_status_valor_adeudo', 'uses' => 'AdeudosController@update_status'));
            Route::put('/tipo_pago', array('as' => 'actualizar_tipo_pago_adeudo', 'uses' => 'AdeudosController@update_tipospago'));
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
                Route::get('/subconceptos_by_id/', array('as' => 'muestra_todos_subconceptos', 'uses' => 'Sub_ConceptosController@show_all_by_id'));
                Route::get('/subconceptos/actualiza', array('as' => 'muestra_subconcepto', 'uses' => 'Sub_ConceptosController@show'));
                #   Route::get('/subconceptos/actualiza/subconceptos', array('as' => 'muestra_subconceptos_concepto', 'uses' => 'ConceptosController@show_subconceptos'));
                Route::post('/subconceptos/agregar', array('as' => 'crear_subconcepto', 'uses' => 'Sub_ConceptosController@create'));

                Route::get('/reporte', array('as' => 'muestra_becas', 'uses' => 'BecasController@reporte'));
                Route::put('/subconceptos/guardar', array('as' => 'actualizar_valor_subconcepto', 'uses' => 'Sub_ConceptosController@update'));
                Route::delete('/subconceptos/eliminar', array('as' => 'eliminar_subconcepto', 'uses' => 'Sub_ConceptosController@destroy'));
            });
            Route::group(array('prefix' => '/descuentos'), function() {
                Route::get('/', array('as' => 'muestra_todos_descuentos', 'uses' => 'DescuentosController@index'));
                Route::get('/actualiza', array('as' => 'muestra_descuentos', 'uses' => 'DescuentosController@show'));
                Route::get('/reporte', array('as' => 'muestra_descuentos', 'uses' => 'DescuentosController@show_reporte'));
                #   Route::get('/descuentos/actualiza/descuentos', array('as' => 'muestra_descuentos_concepto', 'uses' => 'ConceptosController@show_descuentos'));
                Route::post('/agregar', array('as' => 'crear_descuento', 'uses' => 'DescuentosController@create'));
                Route::put('/guardar', array('as' => 'actualizar_valor_descuento', 'uses' => 'DescuentosController@update'));
                Route::delete('/eliminar', array('as' => 'eliminar_descuento', 'uses' => 'DescuentosController@destroy'));
                Route::get('/expediente', array('as' => 'descuento_adeudo', 'uses' => 'DescuentosController@expediente'));
            });
        });
        Route::group(array('prefix' => '/tipo_adeudo'), function() {
            Route::get('/', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@index'));
            Route::get('/expediente', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@show'));
            Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Tipo_adeudoController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Tipo_adeudoController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Tipo_adeudoController@destroy'));
        });
        Route::group(array('prefix' => '/prorrogas'), function() {
            Route::get('/', array('as' => 'muestra_prorrogas', 'uses' => 'ProrrogasController@index'));
            Route::get('/expediente', array('as' => 'muestra_prorrogas', 'uses' => 'ProrrogasController@show'));
            Route::post('/agregar', array('as' => 'crear_prorrogas', 'uses' => 'ProrrogasController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_prorrogas', 'uses' => 'ProrrogasController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_prorrogas', 'uses' => 'ProrrogasController@destroy'));
        });
        Route::group(array('prefix' => '/tipo_pagos'), function() {
            Route::get('/', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@index'));
            Route::get('/expediente', array('as' => 'muestra_planes', 'uses' => 'Tipo_adeudoController@show'));
            Route::post('/agregar', array('as' => 'crear_planes', 'uses' => 'Tipo_adeudoController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_planes', 'uses' => 'Tipo_adeudoController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_planes', 'uses' => 'Tipo_adeudoController@destroy'));
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
            Route::put('/activar', array('as' => 'actualizar_valor_cuenta', 'uses' => 'CuentasController@update_activo'));
            Route::delete('/eliminar', array('as' => 'eliminar_cuenta', 'uses' => 'CuentasController@destroy'));
        });
        Route::group(array('prefix' => '/resbancaria'), function() {
            Route::get('/', array('as' => 'muestra_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@index'));
            Route::post('/agregar', array('as' => 'crear_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_respuestaBancaria', 'uses' => 'Respuesta_bancariaController@destroy'));
        });
        Route::group(array('prefix' => '/ingresos'), function() {
            Route::get('/', array('as' => 'muestra_ingresos', 'uses' => 'IngresosController@index'));
            Route::post('/agregar', array('as' => 'crear_ingresos', 'uses' => 'IngresosController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_ingresos', 'uses' => 'IngresosController@update'));
            Route::delete('/eliminar', array('as' => 'eliminar_ingresos', 'uses' => 'IngresosController@destroy'));
            Route::post('/totales', array('as' => 'muestra_ingresos_fecha', 'uses' => 'IngresosController@show_ingresos'));
        });
        Route::group(array('prefix' => '/devoluciones'), function() {
            Route::get('/', array('as' => 'muestra_devoluciones', 'uses' => 'DevolucionesController@index'));
            Route::get('/expediente', array('as' => 'muestra_expediente_devoluciones', 'uses' => 'DevolucionesController@show'));
            Route::get('/alumno', array('as' => 'muestra_expediente_devoluciones', 'uses' => 'DevolucionesController@show_persona_periodo'));
            Route::get('/persona', array('as' => 'muestra_expediente_devoluciones', 'uses' => 'DevolucionesController@show_persona'));
            Route::get('/periodo', array('as' => 'muestra_expediente_devoluciones', 'uses' => 'DevolucionesController@show_periodo'));
            Route::post('/agregar', array('as' => 'crear_devoluciones', 'uses' => 'DevolucionesController@create'));
            Route::put('/guardar', array('as' => 'actualizar_valor_devoluciones', 'uses' => 'DevolucionesController@update'));
            Route::put('/status', array('as' => 'actualizar_status_devoluciones', 'uses' => 'DevolucionesController@update_status'));
            Route::delete('/eliminar', array('as' => 'eliminar_devoluciones', 'uses' => 'DevolucionesController@destroy'));
        });
        Route::group(array('prefix' => '/referencias'), function() {
            Route::post('/agregar', array('as' => 'crear_referencia', 'uses' => 'ReferenciasController@create'));
            Route::post('/traducir',array('as' => 'traducir_referencia', 'uses' => 'ReferenciasController@traducir'));
        });
    });
    Route::get('/periodos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@periodos'));
    Route::get('/alumnos_activos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@alumnos_activos'));
    Route::get('/alumnos_todos/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@alumnos_todos'));
    Route::get('/alumnos_todos_matricula/', array('as' => 'muestra_todos_periodos', 'uses' => 'APIServicesController@alumnos_todos_matricula'));
    Route::get('/grupos/', array('as' => 'muestra_todos_grupos', 'uses' => 'APIServicesController@grupos'));
    Route::get('/niveles/', array('as' => 'muestra_todos_niveles', 'uses' => 'APIServicesController@niveles'));
    Route::group(array('prefix' => '/estado_de_cuenta'), function() {
        Route::get('/adeudos', array('as' => 'Adeudos_por_alumno_periodo', 'uses' => 'AdeudosController@show_adeudos_alumno'));
        Route::post('/referencias', array('as' => 'crear_referencia', 'uses' => 'ReferenciasController@create'));
    });
});
Route::group(array('prefix' => '/user'), function() {
    Route::post('/login', array('as' => 'user', 'uses' => 'usuariosController@login'));
    Route::get('/show', array('as' => 'user', 'uses' => 'usuariosController@show'));
});

Route::group(array('prefix' => '/api'), function() {

    Route::group(array('prefix' => '/services', 'before' => 'auth.token'), function() {
        Route::get('/alumnos_adeudos_pagados', array(
            'as' => 'alumnosAdeudosPagadosSubconcepto',
            'before' => 'login_api',
            'uses' => 'APIServicesController@alumnos_adeudos_pagados_subconcepto')
        );
        Route::get('/sub_conceptos', array(
            'as' => 'sub_conceptos',
            'before' => 'login_api',
            'uses' => 'APIServicesController@subconceptos_periodo')
        );
    });

    Route::post('login', function() {
        try {
            $user = Sentry::authenticate(Input::all(), false);

            $token = hash('sha256', Str::random(10), false);

            $user->api_token = $token;

            $user->save();

            return Response::json(array('token' => $token, 'user' => $user->toArray()));
        } catch (Exception $e) {
            App::abort(404, $e->getMessage());
        }
    });

    Route::post('/create', array('as' => 'create', 'uses' => 'APIServicesController@create_user_api'));
});
