<?php

class ReferenciasController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $res['data'] = Referencia::All();

        $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $commond = new Common_functions();
        $parametros = Input::get(); //Adeudos::obtener_adeudos_alumno(array('id_persona'=>838,'periodo'=>143));

        $reglas = array(
            'adeudos' => 'required'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $libereriaReferencia = new Referencias();
            $data = array();
            $data['importe_total'] = 0;
            foreach ($parametros['adeudos'] as $key => $value) {
                if ($value['status_adeudo'] == 0) {
                    foreach ($parametros['adeudos'] as $key_a => $value_a) {
                        if (!isset($fecha_limite)) {
                            if ($value_a['fecha_limite'] > date('Y-m-d')) {
                                $fecha_limite = $value_a['fecha_limite'];
                            } else {
                                if ($parametros['adeudos'][count($parametros['adeudos']) - 1]['fecha_limite'] == $value_a['fecha_limite'] && !isset($fecha_limite)) {
                                    $fecha = $value_a['fecha_limite'];
                                    $months = $value_a['meses_retraso'];
                                    $fecha_limite = date("Y-m-d", strtotime("$fecha +$months month"));
                                }
                            }
                        }
                    }

                    $subconcepto = Sub_conceptos::find($value['sub_concepto_id']);
                    $referencia = sprintf('%05d', $value['id_persona']) .
                            sprintf('%03d', $value['periodo']) .
                            sprintf('%03d', $value['sub_concepto_id']) .
                            sprintf('%01d', $value['digito_referencia']);
                    $data['referencias'][$key]['referencia'] = $libereriaReferencia->Generar($referencia, $value['importe'], $fecha_limite);
                    $data['referencias'][$key]['importe'] = json_decode($value['importe']);
                    $data['referencias'][$key]['sub_concepto'] = $subconcepto['sub_concepto'];
                    $data['referencias'][$key]['sub_concepto'] = $subconcepto['descripcion'];

                    $data['importe_total']+=$value['importe'];
                    $data['fecha_limite'] = $fecha_limite;
                    $data['periodo'] = $value['periodo'];
                    $data['persona'] = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $value['id_persona']));
                    $existe_referencia = Referencia::where('referencia', '=', $data['referencias'][$key]['referencia'])->first();
                    $cuentas = Cuentas::where('activo_cobros', '=', 1)->first();
                    $data['convenio'] = $value['cuenta_pagoid'];
                    if (!$existe_referencia) {
                        Referencia::create(
                                array(
                                    'referencia' => $data['referencias'][$key]['referencia'],
                                    'adeudos_id' => $value['id'],
                                    'cuentas_id' => $cuentas['id']
                        ));
                    }
                }
            }
            $res['data'] = $data;
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show() {
        $parametros = Input::get();
        $reglas = array(
            'id' => $parametros['id']
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Referencia::find($parametros['id']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_by_adeudo() {
        $parametros = Input::get();
        $reglas = array(
            'adeudos_id' => $parametros['adeudos_id']
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Referencia::find($parametros['adeudos_id'])->adeudos();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update() {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required|integer',
            'adeudos_id' => 'integer',
            'referencia' => ''
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            $res = Referencia::where('id', '=', $parametros['id'])->update($parametros);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            Referencia::destroy($parametros['id']);
            $res['data'] = Referencia::All();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function leer_archivo_banco() {
        $commond = new Common_functions();
        $file = Input::file('referencia_archivo');
        if (isset($file)) {
            $data_file = Archivo_referencias::leer($file);
            $data_file['infoFile']['nombre_archivo'] = Input::file('referencia_archivo')->getClientOriginalName();
            $infoarchivo = array(
                'no_transacciones' => $data_file['infoFile']['TRANSACCIONES'],
                'cobro_inmediato' => $data_file['infoFile']['COBROINMEDIATO'],
                'comisiones_creadas' => $data_file['infoFile']['COMISIONESCOBRADAS'],
                'remesas' => $data_file['infoFile']['REMESAS'],
                'comisiones_remesas' => $data_file['infoFile']['COMISIONREMESAS'],
                'abonado' => $data_file['infoFile']['ABONADO'],
                'monto' => $data_file['infoFile']['ABONADO'],
                'nombre_archivo' => $data_file['infoFile']['nombre_archivo'],
                'fecha' => date('Y-m-d')
            );
            $personas = array();
            $i = 0;
            foreach ($data_file['referencias'] as $key => $value) {
                $adeudo = Referencia::with('adeudos')
                                ->where('referencia', '=', $value['referencia'])->first();

                if ($adeudo && !empty($adeudo)) {
                    $adeudo_up = Adeudos::obtener_adeudos_alumno(array(
                        'id' => $adeudo['adeudos']['id'],
                        'fecha_pago' => $value['fecha_de_pago'],
                        'id_persona' => $adeudo['id_persona'],
                        'periodo' => $adeudo['periodo']
                    ));
                    $referencia_info = Referencia::where('referencia', '=', $value['referencia'])->first();
                    if ($value['importe'] >= $adeudo_up[0]['importe']) {
                        if ($adeudo_up[0]['beca']=="N/A") {
                            $adeudo_up[0]['beca']=0;
                        }
                        if ($value['importe'] >= (intval($adeudo_up[0]['importe']) + 100)) {
                            Devoluciones::create(array(
                                'periodo' => $adeudo['adeudo']['periodo'],
                                'fecha_devolucion' => date('Y-m-d'),
                                'importe' => $adeudo_up[0]['importe'] - $value['importe'],
                                'id_persona' => $id_persona,
                                'status_devolucion' => 0
                            ));   
                        }
                        Adeudos::where('id', '=', $adeudo['adeudos']['id'])->update(
                                array(
                                    'beca_pago' => $adeudo_up[0]['beca'],
                                    'recargo_pago' => $adeudo_up[0]['recargo_total'],
                                    'importe_pago' => $adeudo_up[0]['importe'],
                                    'status_adeudo' => 1,
                                    'fecha_pago' => $value['fecha_de_pago'],
                                    'tipo_pagoid' => 1
                        ));
                    } else {
                        Adeudos::where('id', '=', $adeudo['adeudos']['id'])->update(
                                array(
                                    'beca_pago' => $adeudo_up[0]['beca'],
                                    'recargo_pago' => $adeudo_up[0]['recargo_total'],
                                    'importe_pago' => $adeudo_up[0]['importe'],
                                    'importe' => floatval(floatval($value['importe'] - $adeudo_up[0]['importe'])),
                                    'fecha_pago' => $value['fecha_de_pago']
                        ));
                    }
                    $referencia_pagada = array(
                        'id_referencia' => $referencia_info['id'],
                        'fecha_de_pago' => $value['fecha_de_pago'],
                        'importe' => $value['importe'],
                        'estado' => $value['estado']
                    );
                    Referencia::create_referencia_pagada($referencia_pagada);
                    Ingresos::create(
                            array('tipo_pago' => 1,
                                'importe' => $value['importe'],
                                'fecha_pago' => $value['fecha_de_pago']));
                    $personas['existe_referencia'][$i]['persona'] = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $adeudo['adeudos']['id_persona']));
                    $personas['existe_referencia'][$i]['referencia'] = $value;
                    $i++;
                } else {
                    $personas['no_existe_referencia'][$i]['referencia'] = $value;
                    $i++;
                }
            }
            $res['data'] = $personas;
            Respuesta_bancaria::create($infoarchivo);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay archivo o tiene errores.', 'respuesta' => ''));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function traducir() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'referencias' => 'required|array',
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            foreach ($parametros['referencias'] as $key => $referencia) {
                
                if (strlen($referencia) == 20 || strlen($referencia)==27) {

                    if (strlen($referencia)==27) {
                        $ref_temp[$key]['convenio'] = substr($referencia, 0, 7);
                        $referencia=substr($referencia, 7, 20);
                    }
                    $no_calculada = substr($referencia, 0, 12);
                    $calculada = substr($referencia, 12, 8);
                    $ref_temp[$key]['referencia'] = $referencia;
                    $ref_temp[$key]['id_persona'] = substr($no_calculada, 0, 5);
                    $ref_temp[$key]['periodo'] = substr($no_calculada, 5, 3);
                    $ref_temp[$key]['id_subconcepto'] = substr($no_calculada, 8, 3);
                    $ref_temp[$key]['digito_referencia'] = substr($no_calculada, 11, 1);
                    $ref_temp[$key]['fecha_condensada'] = substr($calculada, 0, 4);
                    $ref_temp[$key]['monto_condensado'] = substr($calculada, 4, 2);
                    $ref_temp[$key]['referencia_condensada'] = substr($calculada, 6, 2);
                    $subconcepto_info = Sub_conceptos::find($ref_temp[$key]['id_subconcepto']);
                    $ref_temp[$key]['sub_concepto'] = $subconcepto_info['sub_concepto'];
                    $ref_temp[$key]['sub_concepto_descripcion'] = $subconcepto_info['descripcion'];
                    $ref_temp[$key]['status'] = 'Referencia valida';
                } else {
                    $ref_temp[$key]['referencia'] = $referencia;
                    $ref_temp[$key]['status'] = 'Referencia no valida';
                }
            }
            #$ref_temp = $commond->obtener_alumno_idPersona($ref_temp);
            $res = $commond->obtener_periodo_id($ref_temp);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /*
      public function show_ingresos() {
      $commond = new Common_functions();
      $parametros = Input::get();
      $reglas = array(
      'fecha_desde' => 'required|date_format:Y-m-d',
      'fecha_hasta' => 'required|date_format:Y-m-d'
      );
      $validator = Validator::make($parametros, $reglas);

      if (!$validator->fails()) {
      $res['total'] = Referencia::obtener_ingresos($parametros);
      $res['data']=Referencia::obtener_info_referencias_pagadas($parametros);
      echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
      } else {
      echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
      }
      } */
}
