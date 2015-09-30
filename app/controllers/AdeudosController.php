<?php

class AdeudosController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $commond = new Common_functions();

        $parametros = array(
            'paquete_id' => Input::get('paquete_id'),
            'id_personas' => Input::get('id_personas')
        );
        $reglas = array(
            'paquete_id' => 'required|integer',
            'id_personas' => 'required|array'
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {

            $periodo_actual = $commond->periodo_actual();
            $paquete = Paquete::find($parametros['paquete_id']);
            $subconceptos = Paquete::show_paquete_subconceptos($parametros['paquete_id']);
            Adeudos::$custom_data = array("paquete" => $paquete, "subconcepto" => $subconceptos);
            foreach ($parametros['id_personas'] as $alumno) {
                $adeudos_no_pagados = Adeudos::where('id_persona', '=', $alumno)
                                ->where('periodo', '!=', $periodo_actual['idperiodo'])
                                ->where('status_adeudo', '=', 0)->count();

                if ($adeudos_no_pagados == 0) {
                    Adeudos::agregar_adeudos($alumno);
                }
            }
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => ''));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function createSubconcepto() {
        $parametros = Input::get();
        $reglas = array(
            'subconcepto_id' => 'required|integer',
            'periodo' => 'required|integer',
            'id_personas' => 'required|integer',
            'fecha_limite' => 'date_format:Y-m-d',
            'tipos_pago' => 'required|array',
            'recargo_acumulado' => 'required|integer',
            'aplica_beca'   => 'integer',
            'aplica_recargo' => 'integer'
        );
        $commond = new Common_functions();
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $periodo_actual = $commond->periodo_actual();
            $adeudos_no_pagados = Adeudos::where('id_persona', '=', $parametros['id_personas'])
                            ->where('periodo', '!=', $periodo_actual['idperiodo'])
                            ->where('status_adeudo', '=', 0)->count();
            $grado = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $parametros['id_personas']));
            if (isset($grado[0]['grado'])) {
                $grado = $grado[0]['grado'];
            } else {
                $grado = null;
            }
            if ($adeudos_no_pagados == 0) {
                $subconcepto = Sub_conceptos::find($parametros['subconcepto_id']);

                $parametros['digito_referencia'] = intval(DB::table('subconcepto_paqueteplandepago')
                                ->where('sub_concepto_id', $parametros['subconcepto_id'])
                                ->max('digito_referencia'));
                if ($parametros['digito_referencia'] > 9) {
                    $parametros['digito_referencia'] = 8;
                }
                $adeudo = array(
                    'importe' => $subconcepto['importe'],
                    'sub_concepto_id' => $subconcepto['id'],
                    'fecha_limite' => $parametros['fecha_limite'],
                    'grado' => $grado,
                    'id_persona' => $parametros['id_personas'],
                    'periodo' => $parametros['periodo'],
                    'digito_referencia' => $parametros['digito_referencia'] + 1,
                    'recargo_acumulado' => $parametros['recargo_acumulado']
                );
                $adeudo_creado = Adeudos::create($adeudo);
                foreach ($parametros['tipos_pago'] as $key => $value) {
                    $adeudo_tipopago['adeudos_id'] = $adeudo_creado['id'];
                    $adeudo_tipopago['tipo_pago_id'] = $value;
                    Adeudos_tipopago::create($adeudo_tipopago);
                }
                $subconcepto_adeudo = Sub_conceptos::find($adeudo_creado['sub_concepto_id']);
                if (!is_array($subconcepto_adeudo)) {
                    $subconcepto_adeudo = $subconcepto_adeudo->toArray();
                }
                if (!is_array($adeudo_creado)) {
                    $adeudo_creado = $adeudo_creado->toArray();
                }
                $res = array_merge($adeudo_creado, $subconcepto_adeudo);
            }
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function create_reporte_key() {
      $commond = new Common_functions();
      $parametros = Input::get();
      if (isset($parametros['filters'])) {
        $parametros['filters'] = json_decode($parametros['filters']);
      }
      $reglas = array(
        'key' => 'required',
        'filters' => 'required|array'
      );
      $validator = Validator::make($parametros, $reglas);
      if ($validator->fails()) {
        $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));    
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
      } else {
        $adeudos=$commond->get_by_key($parametros['key']);
#        echo "<pre>";print_r($adeudos);echo "</pre>"; die();
        $filters=$parametros["filters"];
        if ($adeudos) {
          $adeudos=$commond->parseAdeudos($adeudos);
          #echo "<pre>";print_r($adeudos);echo "</pre>"; die();
          $meses=array('Enero', 'Febrero', 'Marzo','Abril','Mayo','Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
          Excel::create('Reporte Adeudos'.date('Y-m-d'), function($excel) use($adeudos,$filters,$meses) {
            $excel->sheet('Adeudos', function($sheet) use($adeudos,$filters,$meses){
              #$sheet->loadView('excel.create_excel_parseado',array("adeudos"=>$adeudos,"filters"=>$filters,"meses"=>$meses));
              $sheet->setStyle(array(
                  'font' => array(
                      'name'      =>  'Arial',
                      'size'      =>  10,
                      'bold'      =>  false
                  )
              ));
              $sheet->cells('G1:G2', function($cells) {
                $cells->setBackground('#0000CD');
                $cells->setFontColor('#ffffff');
                $cells->setFontFamily('Arial');
                $cells->setFontSize(10);
                $cells->setBorder(array(
                    'borders' => array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                    )
                ));
                $cells->setAlignment('left');  
                $cells->setValignment('middle');
                $cells->setFontWeight('bold');
                  // manipulate the range of cells
              });
              $sheet->cells('A6:L6', function($cells) {
                $cells->setBackground('#0000CD');
                $cells->setFontColor('#ffffff');
                $cells->setFontFamily('Arial');
                $cells->setFontSize(10);
                $cells->setBorder(array(
                    'borders' => array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                    )
                ));
                $cells->setAlignment('left');  
                $cells->setValignment('middle');
                $cells->setFontWeight('bold');
                  // manipulate the range of cells
              });
              $data_excel=array(
                          array("","","","","","","Año"),
                          array("","","","","","","Mes"),
                          array(""),
                          array("","","","","","",date('Y',strtotime('now'))),
                          array("","","","","","",$meses[date('m', strtotime('now'))-1]),
                          array("Periodo","Sub Concepto","Clave","Matricula",
                                "Alumno","Mes","Alumnos","Importe","Recargos",
                                "Beca","Descuentos","Total"),
              );                           
              
              foreach ($adeudos['periodos'] as $key_p => $periodo) {
                  $c_p=0;                  
                  foreach ($periodo['subconceptos'] as $key_s => $sc) {
                      $c_s=0;
                      $persona_ant="0";
                      foreach ($sc['adeudo_info'] as $key_ai => $value_ai) {
                        if ($c_p==0) {
                          $data_excel[]=array(
                              $periodo['periodo'],
                              $sc['sub_concepto'],
                              $value_ai['clave'],
                              $value_ai['matricula'],
                              $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                              $meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                              1,
                              $value_ai['importe'],
                              $value_ai['recargo'],
                              $value_ai['beca'],
                              $value_ai['descuento'],
                              $value_ai['total']
                            );
                        } else {
                            if ($c_s==0) {
                                $data_excel[]=array(
                                "",
                                $sc['sub_concepto'],
                                $value_ai['clave'],
                                $value_ai['matricula'],
                                $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                                $meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                1,
                                $value_ai['importe'],
                                $value_ai['recargo'],
                                $value_ai['beca'],
                                $value_ai['descuento'],
                                $value_ai['total']
                              );
                            } else {
                              if (isset($value_ai['importe_total'])) {
                                $data_excel[]=array(
                                  "",
                                  "",
                                  "Total",
                                  "",
                                  "",
                                  "",
                                  $value_ai['alumnos_total'],
                                  $value_ai['importe_total'],
                                  $value_ai['recargo_total'],
                                  $value_ai['beca_total'],
                                  $value_ai['descuento_total'],
                                  $value_ai['total']
                                );
                              } else {
                                if (intval($persona_ant)==intval($value_ai['clave'])) {
                                  $data_excel[]=array(
                                    "",
                                    "",
                                    "",
                                    "",
                                    "",
                                    $meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                    1,
                                    $value_ai['importe'],
                                    $value_ai['recargo'],
                                    $value_ai['beca'],
                                    $value_ai['descuento'],
                                    $value_ai['total']
                                  );
                                } else {
                                  $data_excel[]=array(
                                    "",
                                    "",
                                    $value_ai['clave'],
                                    $value_ai['matricula'],
                                    $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                                    $meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                    1,
                                    $value_ai['importe'],
                                    $value_ai['recargo'],
                                    $value_ai['beca'],
                                    $value_ai['descuento'],
                                    $value_ai['total']
                                  );
                                }
                              }
                            }
                        }
                        $c_s++;
                        $c_p++;
                      }
                  }
              }
              #var_dump($data_excel);die();
              #$data['titulos_adeudos']=array();
              $sheet->fromArray($data_excel, null, 'A1', false,false);

              #$sheet->fromArray($data['titulos_adeudos'], null, 'A6', false,false);
            });
          })->download('xls');
        } else {
          return View::make('excel.error_excel')->with('key', $parametros['key']);
        }
      }
    }

    public function create_reporte() {
//        $commond = new Common_functions();
//        $parametros = Input::get();
//        $reglas = array(
//            'fecha_desde' => 'date_format:Y-m-d',
//            'fecha_hasta' => 'date_format:Y-m-d',
//            'periodo' => 'integer'
//        );
//        $validator = Validator::make($parametros, $reglas);
//
//        if (!$validator->fails()) {
//            $res['data'] = Adeudos::obtener_adeudos_reporte($parametros);
//            $res['data'] = $commond->obtener_alumno_idPersona($res['data']);
//            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
//        } else {
//            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
//        }



        $commond = new Common_functions();
        $parametros = Input::get();
        $campos = explode(',', $parametros['campos']);
        Adeudos::obtener_adeudos_reporte_filtrado($parametros,$campos);
        var_dump($campos);
//        var_dump($parametros['adeudos_ids']);
//        var_dump(base64_decode($parametros['adeudos_ids']));
        //$data=Adeudos::obtener_adeudos_reporte_filtrado($parametros);
        //echo json_encode($data);
        die();
        $parametros['adeudos_ids'] = json_decode($parametros['adeudos_ids']);
        $parametros['adeudos_campos'] = json_decode($parametros['adeudos_campos']);
        $reglas = array(
            'adeudos_ids' => 'required|array',
            'adeudos_campos' => 'required|array'
        );

        $validator = Validator::make($parametros, $reglas);
        if ($validator->fails()) {
          return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));    
        } else {
          foreach ($parametros['adeudos_ids'] as $key => $id) {
              $adeudos_consulta[] = Adeudos::obtener_adeudos_id($id);
          }
          $adeudos_info = $commond->obtener_alumno_idPersona($adeudos_consulta);
          $adeudos=$commond -> crear_key($parametros,$adeudos_info);
          Excel::create('Reporte '.date('Y-m-d'), function($excel) use($adeudos) {
              $excel->sheet('Sheetname', function($sheet) use($adeudos) {
                  $sheet->fromArray($adeudos["data"]);
              });
          })->download('xlsx');
        }
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
     * Muestra los adeudos del alumno por periodo.
     *
     * @return Response
     */
    public function show_adeudos_alumno() {
        $parametros = array(
            'id_persona' => Input::get('id_persona'),
            'periodo' => Input::get('periodo')
        );
        $reglas = array(
            'id_persona' => 'required|integer',
            'periodo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);
        if ($validator->fails()) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        } else {
            $alumno = Adeudos::obtener_adeudos_alumno($parametros);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Referencias de alumno.', 'respuesta' => $alumno));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Muestra los adeudos por periodos.
     *
     * @param  int  $id_periodo
     * @return Response
     */
    public function show_by_periodo() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'periodo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Adeudos::obtener_adeudos_periodo($parametros['periodo']);
            $res['data'] = $commond->procesar_adeudos_reporte($res['data']);
            $res['data'] = $commond -> crear_key($parametros,$res['data']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_adeudos_reporte() {
        ini_set('max_execution_time', 300);
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'fecha_desde' => 'date_format:Y-m-d',
            'fecha_hasta' => 'date_format:Y-m-d',
            'periodo' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Adeudos::obtener_adeudos_reporte($parametros);
            $res['data'] = $commond->procesar_adeudos_reporte($res['data']);
            $res['data'] = $commond -> crear_key($parametros,$res['data']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
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
    public function update($id) {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required',
            'fecha_limite' => 'date_format:Y-m-d',
            'id_persona' => 'integer',
            'importe' => 'numeric',
            'periodo' => 'integer',
            'status_adeudo' => 'integer',
            'sub_concepto_id' => 'integer',
            'grado' => 'integer',
            'recargo' => 'numeric',
            'tipo_recargo' => 'integer',
            'paquete_id' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            Adeudos::where('id', '=', $parametros['id'])->update($parametros);
            $res['data'] = Adeudos::find($parametros['id']);
            $respuesta= json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta= json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function update_status() {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required',
            'status_adeudo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            Adeudos::where('id', '=', $parametros['id'])->update($parametros);
            $res['data'] = Adeudos::find($parametros['id']);
            $respuesta= json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta= json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function update_status_pagado() {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required',
            'status_adeudo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            Adeudos::where('id', '=', $parametros['id'])->update($parametros);
            $res['data'] = Adeudos::find($parametros['id']);
            if ($parametros['status_adeudo'] == 1) {
                Ingresos::create(
                        array('tipo_pago' => 2,
                            'importe' => $res['data']['importe'],
                            'fecha_pago' => date('Y-m-d')));
            }
            $respuesta= json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta= json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function update_tipospago() {
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required|integer',
            'tipo_pago' => 'required|array'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            Adeudos_tipopago::where('adeudos_id', '=', $parametros['id'])->delete();
            foreach ($parametros['tipos_pago'] as $key => $value) {
                $adeudo_tipopago['adeudos_id'] = $parametros['id'];
                $adeudo_tipopago['tipo_pago_id'] = $value;
                Adeudos_tipopago::create($adeudo_tipopago);
            }
            $res['data'] = Adeudos::find($parametros['id']);
            $respuesta= json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta= json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
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
    public function destroy($id) {
        //
    }

}
