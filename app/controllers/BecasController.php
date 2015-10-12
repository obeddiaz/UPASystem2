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
        $commond = new Common_functions();
        $file = Request::file('becas_file');
        if (isset($file)) {    
            $root=$file->getRealPath();
            $data=array();            
            $info_excel=Excel::load($root, function($archivo){})->get();
            $res['data']=array();
            foreach($info_excel as $key => $value)
            {
                $beca= Becas::where('abreviatura', '=', $value->clave)->first();
                if ($beca) {
                    $periodo_actual=$commond->periodo_actual();
                    $data = $commond->obtener_alumno_matricula(
                            array(
                                array(
                                    'periodo' => $periodo_actual['idperiodo'],
                                    'matricula' => $value->matricula,
                                    'idbeca' => $beca['id'],
                                    'status' => 1)));
                    #echo json_encode($value->matricula) . '<br/>';
                    if (isset($data[0])) {
                        $data=$data[0];
                        $beca_existe = Becas::AlumnoBeca_Persona_Periodo(
                            array('id_persona' => $data['id_persona'],
                            'periodo'=>$data['periodo'])); // Consulta beca

                        if ($beca_existe==false) {
                            Becas::create_beca_alumno($data);
                            $data['matricula']= $value->matricula;
                            $res['data']['created'][]=$data;
                        } else {
                            //Becas::create_beca_alumno($data);
                            $data['matricula']= $value->matricula;
                            $res['data']['existente'][]=$data;
                        }
                    } else {
                        $res['data']['nocreado'][]=$value->matricula;    
                    }
                } else {
                    $res['data']['nocreado'][]=$value->matricula;
                }
                $data=array();
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
            'status' => 0
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

            Becas::destroy($parametros['id']);
            $res['data'] = Becas::orderBy('importe', 'desc')->get();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
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
            'id_persona' => 'required|array',
            'idbeca' => 'required|numeric',
            'idnivel' => 'integer',
            'periodo' => 'required|integer',
            'status' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $array_insert = $parametros;
            unset($array_insert['id_persona']);
            $data_todos = $array_insert;
            unset($data_todos['status']);
            foreach ($parametros['id_persona'] as $key => $value) {
                $array_insert['id_persona'] = $value;
                Becas::delete_beca_alumno($array_insert);
            }
            $personasBeca = Becas::obtenerAlumnosBecas($data_todos);
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
            'aplica_beca' => 'required|integer'
        );

        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            if ($parametros['aplica_beca']==0) {
                $data['aplica_recargo']=0;
                $data['aplica_beca']=$parametros['aplica_beca'];
            } else {
                $data['aplica_recargo']=1;
                $data['aplica_beca']=$parametros['aplica_beca'];
            }
            Adeudos::where('id', '=', $parametros['id_adeudo'])->update($data);
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
