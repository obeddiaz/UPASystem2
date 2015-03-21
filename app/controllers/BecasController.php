<?php

class BecasController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $res['data'] = Becas::orderBy('importe', 'desc')->get();
        return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
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
            return json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
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
                    array('id_persona' => $id_persona,
                    'periodo'=>$periodo)); // Consulta beca
                if (!$beca) {
                    Becas::create_beca_alumno($array_insert);
                }
            }
            $personasBeca = Becas::obtenerAlumnosBecas($data_todos);
            $res['data'] = $commond->obtener_alumno_idPersona($personasBeca);
            $commond->actualiza_status_adeudos($parametros['id_persona'],$parametros['periodo']);
            return json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
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
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

    public function show_catalogos() {

        $res['data']['tipo_importe'] = Becas::obtenerTipoImporte();
        $res['data']['periodicidades'] = Becas::obtenerPerodicidades();
        $res['data']['subcidios'] = Becas::obtenerSubcidios();

        return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
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
                return json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
            }
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
        }
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
                return json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
            }
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'Error en la busqueda de datos.', 'respuesta' => null));
        }
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
            'periodicidades_id' => 'integer',
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
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
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
            return json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
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
            return json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
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
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
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
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

}
