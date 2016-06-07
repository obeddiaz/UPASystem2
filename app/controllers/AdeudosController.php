<?php

class AdeudosController extends \BaseController {
    private $sii;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->sii = new Sii();
    }

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
            $paquete = Paquete::find($parametros['paquete_id']);
            $subconceptos = Paquete::show_paquete_subconceptos($parametros['paquete_id']);
            
            $personas_ids['no_asignados'] = array();
            $personas_ids['asignados'] = array();
            
            $count_asigned = 0;
            $count_no_asigned = 0;
            
            foreach ($parametros['id_personas'] as $alumno) {
                $persona = $commond->getInfoiIDPersona($alumno);
                if (!empty($persona)) {
                    $validacionAlumno = $commond->validarInfoCrearAdeudo($alumno, $paquete['periodo'], $paquete);
                    if ($validacionAlumno['status']) {
                        $count_asigned++;
                        Adeudos::agregar_adeudos($alumno, $subconceptos, $paquete);
                        $personas_ids['asignados'][] = $persona;
                    }   else   {
                        $count_no_asigned++;
                        $persona['motivo_no_asignacion'] = $validacionAlumno['motivo'];
                        $personas_ids['no_asignados'][] = $persona;
                    }
                }
            }
            $personas_ids['total_asignados']=$count_asigned;
            $personas_ids['total_no_asignados']=$count_no_asigned;

            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $personas_ids));
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
            'descripcion_sc'    =>  'size:30',
            'aplica_beca'   => 'integer',
            'aplica_recargo' => 'integer'
        );
        $commond = new Common_functions();
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $periodo_actual = $commond->periodo_actual();
            $adeudos_no_pagados = Adeudos::where('id_persona', '=', $parametros['id_personas'])
                            ->where('periodo', '<', $periodo_actual['idperiodo'])
                            ->where('status_adeudo', '=', 0)->count();
            $grado = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $parametros['id_personas']));
            if (isset($grado[0]['grado'])) {
                $grado = $grado[0]['grado'];
            } else {
                $grado = null;
            }
            if ($adeudos_no_pagados == 0) {
                $subconcepto = Sub_conceptos::find($parametros['subconcepto_id']);
                $concepto = Conceptos::where($subconcepto['conceptos_id'])->first();
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

    public function create_array() {
        $commond = new Common_functions();

        $parametros = array(
            'paquete_id' => Input::get('paquete_id')
        );

        $reglas = array(
            'paquete_id' => 'required|integer'
            //'id_personas' => 'required|array'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $matriculasFile = Config::get('matriculas');

            $todos = $this->sii->new_request('POST', '/alumnos/all');
            foreach($matriculasFile as $key => $value)  { 
                foreach ($todos as $key_todos => $todos_row) {
                    if ($value == $todos_row['matricula']) {
                        $parametros['id_personas'][]= $todos_row['idpersonas'];
                        break;
                    }
                }
            }
            
            $paquete = Paquete::find($parametros['paquete_id']);
            $subconceptos = Paquete::show_paquete_subconceptos($parametros['paquete_id']);
            $personas_ids['no_asignados'] = array();
            $personas_ids['asignados'] = array();
            $count_asigned = 0;
            $count_no_asigned = 0;
            if (isset($parametros['id_personas'])) {
                foreach ($parametros['id_personas'] as $alumno) {
                    $persona = $commond->getInfoiIDPersona($alumno);
                    if (!empty($persona)) {
                        $validacionAlumno = $commond->validarInfoCrearAdeudo($alumno, $paquete['periodo'], $paquete);
                        if ($validacionAlumno['status']) {
                            $count_asigned++;
                            Adeudos::agregar_adeudos($alumno, $subconceptos, $paquete);
                            $personas_ids['asignados'][] = $persona;
                        }   else   {
                            $count_no_asigned++;
                            $persona['motivo_no_asignacion'] = $validacionAlumno['motivo'];
                            $personas_ids['no_asignados'][] = $persona;
                        }
                    }
                }
                $personas_ids['total_asignados']=$count_asigned;
                $personas_ids['total_no_asignados']=$count_no_asigned;
                $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $personas_ids));    # code...
            }   else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'No existen los alumnos ingresados', 'respuesta' => null));
            }
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function create_byFile() {
        ini_set('max_execution_time', 300000000);
        ini_set('memory_limit', '100000M');
        ini_set('post_max_size', '10000M');
        ini_set('upload_max_filesize', '300000M');
      
        $commond = new Common_functions();

        $parametros = array(
            'paquete_id' => Input::get('paquete_id')
            //'id_personas' => Input::get('id_personas')
        );
        $reglas = array(
            'paquete_id' => 'required|integer'
            //'id_personas' => 'required|array'
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
          $file = Request::file('paquete_file');
          if (isset($file)) {    
            $root=$file->getRealPath();
            $info_excel=Excel::load($root, function($archivo){
            })->all();
            $matriculasFile = array();
            foreach ($info_excel as $key => $value) {
              $matriculasFile[] = $value['matricula'];
            }
            $parametros['id_personas'] = array();
            //$selected= Config::get('matriculas');
            $todos = $this->sii->new_request('POST', '/alumnos/all');
            foreach($matriculasFile as $key => $value)  { 
                foreach ($todos as $key_todos => $todos_row) {
                    if ($value == $todos_row['matricula']) {
                        $parametros['id_personas'][]= $todos_row['idpersonas'];
                        break;
                    }
                }
            }
            $paquete = Paquete::find($parametros['paquete_id']);
            $subconceptos = Paquete::show_paquete_subconceptos($parametros['paquete_id']);
            $personas_ids['no_asignados'] = array();
            $personas_ids['asignados'] = array();
            $count_asigned = 0;
            $count_no_asigned = 0;

            foreach ($parametros['id_personas'] as $alumno) {
                $persona = $commond->getInfoiIDPersona($alumno);
                if (!empty($persona)) {
                    $validacionAlumno = $commond->validarInfoCrearAdeudo($alumno, $paquete['periodo'], $paquete);
                    if ($validacionAlumno['status']) {
                        $count_asigned++;
                        Adeudos::agregar_adeudos($alumno, $subconceptos, $paquete);
                        $personas_ids['asignados'][] = $persona;
                    }   else   {
                        $count_no_asigned++;
                        $persona['motivo_no_asignacion'] = $validacionAlumno['motivo'];
                        $personas_ids['no_asignados'][] = $persona;
                    }
                }
            }
            $personas_ids['total_asignados']=$count_asigned;
            $personas_ids['total_no_asignados']=$count_no_asigned;
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $personas_ids));
          }  else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No se subio archivo o se subio incorrectamente.', 'respuesta' => null));  
          }
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
            $res['data'] = $commond -> procesar_adeudos_reporte($res['data']);
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
            'periodo' => 'integer',
            'status' => 'required'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Adeudos::obtener_adeudos_reporte($parametros);
            $res['data'] = $commond->procesar_adeudos_reporte($res['data']);
            //$res['data'] = $commond -> crear_key($parametros,$res['data']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_adeudos_reporte_ordenado() {
        ini_set('max_execution_time', 300);
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'fecha_desde' => 'date_format:Y-m-d',
            'fecha_hasta' => 'date_format:Y-m-d',
            'periodo' => 'integer',
            'status' => 'required'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {

            $res['data'] = Adeudos::obtener_adeudos_alumno($parametros);
            $res['data'] = $commond->procesar_adeudos_reporte($res['data']);
            $res['data'] = $commond->parseAdeudos($res,array());
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
            'paquete_id' => 'integer',
            'recargo_pago' => 'numeric',
            'beca_pago' => 'numeric',
            'importe_pago' => 'numeric'
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
            $info_adeudo = Adeudos::where('id','=',$parametros['id'])->first();
            $info_adeudo = Adeudos::obtener_adeudos_alumno(array('id_persona' => $info_adeudo['id_persona'],
                                                                  'periodo'=>$info_adeudo['periodo']));
            Adeudos::where('id', '=', $parametros['id'])->update(
                                                                array(
                                                                  'recargo_pago' => $info_adeudo['recargo'],
                                                                  'beca_pago' => $info_adeudo['beca'],
                                                                  'importe_pago'=> $info_adeudo['importe'],
                                                                  'status_adeudo' => $parametros['status_adeudo'],
                                                                  'tipo_pagoid' => 2));
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
