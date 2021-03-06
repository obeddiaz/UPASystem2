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
        $adeudo_info = Adeudos::obtener_adeudos_alumno(array('id' => $value['id']));

        if ($adeudo_info['status_adeudo'] == 0) {
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

          $subconcepto = Sub_conceptos::find($adeudo_info['sub_concepto_id']);
          $referencia   =   sprintf('%05d', $adeudo_info['id_persona']) .
                            sprintf('%03d', $adeudo_info['periodo']) .
                            sprintf('%03d', $adeudo_info['sub_concepto_id']) .
                            sprintf('%01d', $adeudo_info['digito_referencia']);

          $data['referencias'][$key]['referencia'] = $libereriaReferencia->Generar( $referencia, 
                                                                                    $adeudo_info['importe'], 
                                                                                    $fecha_limite);

          $data['referencias'][$key]['importe'] = json_decode($adeudo_info['importe']);
          $data['referencias'][$key]['sub_concepto'] = $subconcepto['sub_concepto'];
          $data['referencias'][$key]['sub_concepto'] = $subconcepto['descripcion'];

          $data['importe_total']+=$adeudo_info['importe'];
          $data['fecha_limite'] = $fecha_limite;
          $data['periodo'] = $adeudo_info['periodo'];
          $data['persona'] = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $adeudo_info['id_persona']));
          $existe_referencia = Referencia::where('referencia', '=', $data['referencias'][$key]['referencia'])->first();
          $cuentas = Cuentas::where('id', '=', $adeudo_info['cuenta_id'])->first();
          $data['convenio'] = $cuentas['cuenta'];
          if (!$existe_referencia) {
            Referencia::create(array( 'referencia' => $data['referencias'][$key]['referencia'],
                                      'adeudos_id' => $adeudo_info['id'],
                                      'cuentas_id' => $cuentas['id'],
                                      'importe'    => $adeudo_info['importe']));
          } else {
            Referencia::where('id', '=', $existe_referencia['id'])
                        ->update(array(
                                      'cuentas_id' => $cuentas['id'],
                                      'importe'    => $adeudo_info['importe']));
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

          if ($file->getClientOriginalExtension() == "txt") {
              $ingresos = 0.0;
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
                  'fecha' => date('Y-m-d H:i:s')
              );
              $personas = array();
              $i = 0;

              $respuesta_bancaria = Respuesta_bancaria::where(  'nombre_archivo',     '=',  $infoarchivo['nombre_archivo'])
                                                        ->where('no_transacciones',   '=',  $infoarchivo['no_transacciones'])
                                                        ->where('cobro_inmediato',    '=',  $infoarchivo['cobro_inmediato'])
                                                        ->where('comisiones_creadas', '=',  $infoarchivo['comisiones_creadas'])
                                                        ->where('abonado',            '=',  $infoarchivo['abonado'])
                                                        ->where('monto',              '=',  $infoarchivo['monto'])
                                                        ->first();
              if (!$respuesta_bancaria) {
                Respuesta_bancaria::create($infoarchivo);
              } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'El archivo ya se subio anteriormente.', 'respuesta' => ''));
                $final_response = Response::make($respuesta, 200);
                $final_response->header('Content-Type', "application/json; charset=utf-8");

                return $final_response;
              }

              foreach ($data_file['referencias'] as $key => $referencia) {
                $referencia_info = Referencia::where('referencia', '=', $referencia['referencia'])->first();
                if ($referencia) {
                  $adeudo =  Adeudos::obtener_adeudos_alumno(array( 'id'  =>  $referencia_info['adeudos_id'],
                                                                  'fecha' =>  $referencia['fecha_de_pago']));
                  $referencia_pagada = Referencia::obtener_info_referencias_pagadas(array('referencia'  =>  $referencia['referencia']));
                  if (!empty($adeudo) && $adeudo['status_adeudo'] == 0) {
                    if (empty($referencia_pagada)) {
                      
                      $referencia['referencia_id']  = $referencia_info['id'];
                      $referencia['tipo_pago']      = 1;

                      // Funcionalidad para Crear un pago (Con sus Validaciones).
                      $personas['existe_referencia'][$i]['persona'] = $commond->validarPago($adeudo,  $referencia);
                      $personas['existe_referencia'][$i]['referencia'] = $referencia;
                      $ingresos += $referencia['importe'];
                    } else {
                      $personas['no_existe_referencia'][$i]['persona']                  = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $adeudo['id_persona']));
                      $personas['no_existe_referencia'][$i]['referencia']               = $referencia;
                      $personas['no_existe_referencia'][$i]['referencia']['motivo']     = "La referencia ya fue pagada";
                      $personas['no_existe_referencia'][$i]['referencia']['tipo_error'] = 2;
                    }
                  } else {
                    $personas['no_existe_referencia'][$i]['persona']                  = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $adeudo['id_persona']));
                    $personas['no_existe_referencia'][$i]['referencia']               = $referencia;
                    $personas['no_existe_referencia'][$i]['referencia']['motivo']     = "El adeudo ya fue pagado";
                    $personas['no_existe_referencia'][$i]['referencia']['tipo_error'] = 2;
                  }
                } else {
                  $personas['no_existe_referencia'][$i]['referencia']               = $referencia;
                  $personas['no_existe_referencia'][$i]['referencia']['motivo']     = "Referencia incorrecta (no se encuentra en los registros)";
                  $personas['no_existe_referencia'][$i]['referencia']['tipo_error'] = 1;
                }
                $i++;
              }

              $res['data'] = $personas;
              
              if ($ingresos > 0) {
                Ingresos::create( array(  'tipo_pago'   =>  1,
                                          'importe'     =>  $ingresos,
                                          'fecha_pago'  =>  date('Y-m-d H:i:s')  ) );
              }
              
              $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
          }   else {
              $respuesta = json_encode(array('error' => true, 'mensaje' => 'El tipo de archivo es incorrecto. (txt)', 'respuesta' => ''));
          }

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
