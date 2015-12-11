<?php

class BecasController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $res['data'] = Becas::orderBy('importe', 'desc')->get();
        $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
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
        $parametros = array(
            'abreviatura' => Input::get('abreviatura'),
            'importe' => Input::get('importe'),
            'subcidios_id' => Input::get('subcidios_id'),
            'tipo_importe_id' => Input::get('tipo_importe_id'),
            'descripcion' => Input::get('descripcion'),
            'tipobeca' => Input::get('tipobeca')
        );
        $reglas = array(
            'abreviatura' => 'required',
            'importe' => 'required|numeric',
            'subcidios_id' => 'required|integer',
            'tipo_importe_id' => 'required|integer',
            'descripcion' => 'required',
            'tipobeca' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Becas::create($parametros);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function create_alumno() {
        $commond = new Common_functions();

        $parametros = array(
            'id_persona' => Input::get('id_persona'),
            'idbeca' => Input::get('idbeca'),
            'idnivel' => Input::get('idnivel'),
            'periodo' => Input::get('periodo'),
            'status' => 1
        );
        $reglas = array(
            'id_persona' => 'required|array',
            'idbeca' => 'required|numeric',
            'idnivel' => 'required|integer',
            'periodo' => 'required|integer',
            'status' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $array_insert = $parametros;
            unset($array_insert['id_persona']);
            $data_todos = $array_insert;
            unset($data_todos['status']);
            foreach ($parametros['id_persona'] as $key => $value) {
                $array_insert['id_persona'] = $value;
//                $array_insert['created_at'] = date('Y-m-d H:i:s');
//                $array_insert['updated_at'] = date('Y-m-d H:i:s');
                $beca = Becas::AlumnoBeca_Persona_Periodo(
                    array('id_persona' => $value,
                    'periodo'=>$parametros['periodo'])); // Consulta beca
                if (!$beca) {
                    Becas::create_beca_alumno($array_insert);
                }
            }
            $personasBeca = Becas::obtenerAlumnosBecas($data_todos);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            $commond->actualiza_status_adeudos($parametros['id_persona'],$parametros['periodo']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function create_alumno_file() {
        ini_set('memory_limit', '1000M');
        ini_set('post_max_size', '10M');
        ini_set('upload_max_filesize', '150M');
        
        $commond = new Common_functions();
        $file = Request::file('becas_file');
        $config = Config::get('app');
        $meses = $config['meses_periodo'];
        if (isset($file)) {    
            $root=$file->getRealPath();
            $data=array();            
            $info_excel=Excel::load($root, function($archivo){})->get();
            $res['data']=array();
            foreach($info_excel as $key => $value)
            {   
                /// #-----
                $meses[]=$value->mes_1;
                $meses[]=$value->mes_2;
                $meses[]=$value->mes_3;
                $meses[]=$value->mes_4;
                /// ------ # Obtiene los meses a los que se le asiganara la beca
                $beca= Becas::where('abreviatura', '=', $value->clave)->first(); 
                // Se obtiene la infotmacion de la beca con respecto a la clave dada
                if ($beca) {    // Si existe la beca empieza el proceso de asignacion
                    $periodo_actual=$commond->periodo_actual();
                    $data = $commond->obtener_alumno_matricula(
                            array(
                                array(
                                    'periodo' => $periodo_actual['idperiodo'],
                                    'matricula' => $value->matricula,
                                    'idbeca' => $beca['id'],
                                    'status' => 1)));

                    if (isset($data[0])) {
                        $data=$data[0];
                        $beca_existe = Becas::AlumnoBeca_Persona_Periodo(
                            array('id_persona' => $data['id_persona'],
                            'periodo'=>$data['periodo'])); // Consulta beca
                        if ($beca_existe==false) { // si el alumno no tiene beca asignada en ese periodo realiza proceso
                            $adeudos = Adeudos::obtener_adeudos_alumno(
                                            array('periodo'=>$data['periodo'],
                                                  'id_persona'=>$data['id_persona'])); // Se obtienen los Adeudos del alumno en el periodo
                            $adeudos_no_inscripcion=array();
                            $lock_adeudos=0; // Variable para no aplicar beca por retraso de pago en la inscripcion
                            foreach ($adeudos as $key => $adeudo) { // Obtenemos los adedudos que no sean inscripcion y los seperamos
                                if (intval($adeudo['lock'])!=1) {
                                    if (intval($adeudo['locker_manager'])==0) {
                                        $adeudos_no_inscripcion[]=$adeudo;
                                    }
                                } else {
                                    $lock_adeudos=1;
                                    break;
                                }
                            }
                            if (count($adeudos_no_inscripcion)>0 &&  $lock_adeudos==0) {
                                foreach ($adeudos_no_inscripcion as $key => $adeudo) {
                                    foreach ($meses as $key => $mes) {
                                        if (intval($mes)==intval(date('m',strtotime($adeudo['fecha_limite'])))) {
                                            #$ids_update_beca[]=array('ids'=>$adeudo['id'],'mes'=>$mes);
                                            Adeudos::where('id','=',$adeudo['id'])->update(array('aplica_beca'=>0));
                                            break;
                                        }
                                    }
                                }
                                Becas::create_beca_alumno($data);
                                $data['matricula']= $value->matricula;
                                $res['data']['created'][]=$data;
                            } else {
                                if ($lock_adeudos==0) {
                                    Becas::create_beca_alumno($data);
                                    $data['matricula']= $value->matricula;
                                    $res['data']['created'][]=$data;
                                }   else {
                                    $res['data']['nocreado'][]=array('matricula' => $value->matricula,
                                                                     'motivo' => 'No pago inscripcion a tiempo');
                                }
                            }
                        } else {
                            $data['matricula']= $value->matricula;
                            $res['data']['existente'][]=$data;
                        }
                    } else {
                        $res['data']['nocreado'][]=array('matricula' => $value->matricula,
                                                                     'motivo' => 'Alumno no encontrado o inactico');    
                    }
                } else {
                    $res['data']['nocreado'][]=array('matricula' => $value->matricula,
                                                     'motivo' => 'La clave de la beca es incorrecta');
                }
                $data=array();
                $meses=array();
            }
            #echo json_encode($res);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay archivo o tiene errores.', 'respuesta' => ''));
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
            'id' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['tipo_importe'] = Becas::obtenerTipoImporte();
            $res['subcidios'] = Becas::obtenerSubcidios();
            $res['data'] = Becas::find($parametros['id']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_alumno_reporte() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            //'id_persona' => 'required', 
            'periodo' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            DB::setFetchMode(PDO::FETCH_ASSOC);
        
            $personasBeca = DB::table('becas')->join('becas_alumno', 'becas_alumno.idbeca', '=', 'becas.id')
                ->join('tipo_importe', 'tipo_importe.id', '=', 'becas.tipo_importe_id')
                ->where('becas_alumno.periodo','=',$parametros['periodo'])
                ->select('becas.*','becas_alumno.periodo', 'becas_alumno.id_persona','tipo_importe.nombre as tipo_cobro','becas_alumno.created_at')
                ->distinct()
                ->get();
                
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            if ($res['data'] == null) {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
            }
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error parametros incorrectos.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_catalogos() {

        $res['data']['tipo_importe'] = Becas::obtenerTipoImporte();
        #$res['data']['periodicidades'] = Becas::obtenerPerodicidades();
        $res['data']['subcidios'] = Becas::obtenerSubcidios();

        $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_alumno() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            //'id_persona' => 'required', 
            'idbeca' => 'required|numeric',
            'idnivel' => 'required|integer',
            'periodo' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $personasBeca = Becas::obtenerAlumnosBecas($parametros);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            if ($res['data'] == null) {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
            }
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_alumno_nobeca() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            //'id_persona' => 'required', 
            'idbeca' => 'required|numeric',
            'idnivel' => 'required|integer',
            'periodo' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $personasBeca = Becas::obtenerAlumnosBecas($parametros);
            $res['data'] = $commond->obtener_alumno_No_idPersona($personasBeca);
            if ($res['data'] == null) {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
            }
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
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
            'id' => 'required',
            'abreviatura' => '',
            'importe' => 'numeric',
          #  'periodicidades_id' => 'integer',
            'subcidios_id' => 'integer',
            'tipo_importe_id' => 'integer',
            'descripcion' => '',
            'tipobeca' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key, $reglas)) {
                    unset($parametros[$key]);
                }
            }
            Becas::where('id', '=', $parametros['id'])->update($parametros);
            $res['data'] = Becas::find($parametros['id']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function update_alumno_activar() {
        $commond = new Common_functions();
        $parametros = array(
            'id_persona' => Input::get('id_persona'),
            'idbeca' => Input::get('idbeca'),
            'idnivel' => Input::get('idnivel'),
            'periodo' => Input::get('periodo'),
            'status' => 1
        );
        $reglas = array(
            'id_persona' => 'required|array',
            'idbeca' => 'required|numeric',
            'idnivel' => 'integer',
            'periodo' => 'required|integer',
            'status' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $array_insert = $parametros;
            unset($array_insert['id_persona']);
            $data_todos = $array_insert;
            unset($data_todos['status']);
            foreach ($parametros['id_persona'] as $key => $value) {
                $array_insert['id_persona'] = $value;
                Becas::update_status_beca_alumno($array_insert);
            }
            $personasBeca = Becas::obtenerAlumnosBecas($data_todos);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function update_alumno_desactivar() {
        $commond = new Common_functions();
        $parametros = array(
            'id_persona' => Input::get('id_persona'),
            'idbeca' => Input::get('idbeca'),
            'idnivel' => Input::get('idnivel'),
            'periodo' => Input::get('periodo'),
            'status' => 0,
            'motivo_cancelacion' => Input::ger('motivo_cancelacion'),
            'fecha_cancelacion' =>  date('Y-m-d')
        );
        $reglas = array(
            'id_persona' => 'required|array',
            'idbeca' => 'required|numeric',
            'idnivel' => 'integer',
            'periodo' => 'required|integer',
            'status' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $array_insert = $parametros;
            unset($array_insert['id_persona']);
            $data_todos = $array_insert;
            unset($data_todos['status']);

            foreach ($parametros['id_persona'] as $key => $value) {
                $array_insert['id_persona'] = $value;
                Becas::update_status_beca_alumno($array_insert);
            }

            $personasBeca = Becas::obtenerAlumnosBecas($data_todos);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
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
            $asignaciones=DB::table('becas_alumno')
                            ->Select('*')
                            ->Where('idbeca','=',$parametros['id'])
                            ->get();
            if (count($asignaciones) == 0){
                Becas::destroy($parametros['id']);
                $res['data'] = Becas::orderBy('importe', 'desc')->get();
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $res['data'] = '';
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'No se puede borrar la beca ya que existen alumnos asignados', 'respuesta' => $res));
            }
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function destroy_alumno() {
        $commond = new Common_functions();
        $parametros = array(
            'id_persona' => Input::get('id_persona'),
            'idbeca' => Input::get('idbeca'),
            'idnivel' => Input::get('idnivel'),
            'periodo' => Input::get('periodo'),
            'status' => Input::get('status')
        );
        $reglas = array(
            'id_persona' => 'required|integer',
            'idbeca' => 'required|integer',
            'idnivel' => 'integer',
            'periodo' => 'required|integer',
            'status' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {

            Becas::delete_beca_alumno($parametros);

            $personasBeca = DB::table('becas_alumno')->Select('*')->where('periodo','=',$parametros['periodo']);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }
    public function reporte() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'periodo'=> 'required|integer',
            'idbeca' => 'required|numeric',
            'idnivel' => 'integer',
            'status' => 'integer'
        );

        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $personasBeca = Becas::obtenerAlumnosBecasCompleto($parametros);
            $becas_info = $commond->obtener_alumno_idPersona($personasBeca);
            Excel::create('Reporte Becas'.date('Y-m-d'), function($excel) use($becas_info) {
                $excel->sheet('Adeudos', function($sheet) use($becas_info){
                    $sheet->loadView('excel.create_excel_becas',array("becas"=>$becas_info));
                });
            })->download('xlsx');
            #$respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return View::make('excel.error_excel');
            #$respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        return $respuesta;
    }
    public function suspender_beca_mes() {
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'id_adeudo'=> 'required|integer',
            'id_persona' => 'required|integer',
            'periodo' => 'required|integer',
            'aplica_beca' => 'required|integer',
            'tipo' => 'required|integer'
        );

        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            if ($parametros['tipo']==1) {
                if ($parametros['aplica_beca']==0) {
                    $data['aplica_recargo']=0;
                    $data['aplica_beca']=$parametros['aplica_beca'];
                } else {
                    $data['aplica_recargo']=1;
                    $data['aplica_beca']=$parametros['aplica_beca'];
                }
                Adeudos::where('id', '=', $parametros['id_adeudo'])->update($data);
            } elseif ($parametros['tipo']==2) {
                $bandera = false;
                $adeudos = Adeudos::where('periodo','=',$parametros['periodo'])
                                    ->where('id_persona','=',$parametros['id_persona'])
                                    ->orderBy('fecha_limite', 'asc')
                                    ->get();
                foreach ($adeudos as $key_adeudo => $adeudo) {
                    if ($bandera==true) {
                        if ($parametros['aplica_beca']==0) {
                            $data['aplica_recargo']=0;
                            $data['aplica_beca']=$parametros['aplica_beca'];
                            if (intval($adeudo['aplica_beca'])==1) {
                                Adeudos::where('id', '=', $adeudo['id'])->update($data);
                            }
                        } else {
                            $data['aplica_recargo']=1;
                            $data['aplica_beca']=$parametros['aplica_beca'];
                            if (intval($adeudo['aplica_beca'])==0) {
                                Adeudos::where('id', '=', $adeudo['id'])->update($data);
                            }
                        }
                    } else {
                        if (intval($parametros['id_adeudo'])==intval($adeudo['id'])) {
                            $bandera=true;
                            if ($parametros['aplica_beca']==0) {
                                $data['aplica_recargo']=0;
                                $data['aplica_beca']=$parametros['aplica_beca'];
                                if (intval($adeudo['aplica_beca'])==1) {
                                    Adeudos::where('id', '=', $adeudo['id'])->update($data);
                                }
                            } else {
                                $data['aplica_recargo']=1;
                                $data['aplica_beca']=$parametros['aplica_beca'];
                                if (intval($adeudo['aplica_beca'])==0) {
                                    Adeudos::where('id', '=', $adeudo['id'])->update($data);
                                }
                            }
                        }
                    }
                }
            }
            $res['data'] = Adeudos::obtener_adeudos_alumno(array('id_persona'=>$parametros['id_persona'],'periodo'=>$parametros['periodo']));
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        }   else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }
}
