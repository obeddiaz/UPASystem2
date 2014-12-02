<?php

class ReferenciasController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $res['data'] = Referencia::All();
        echo json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $parametros=Input::get();
        $libereriaReferencia = new Referencias();
        $referencia=$parametros['id_persona'].$parametros['periodo'].$parametros['sub_concepto_id'];
        $data['referencia']=$libereriaReferencia->Generar($parametros['referencia'],$parametros['importe'],$parametros['fecha']);
        return json_encode($data);
        if (!$validator->fails()) {
            $res['data'] = Referencia::create($parametros);
            echo json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
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
            'id' => $parametros['id']
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Referencia::find($parametros['id']);
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

    public function show_by_adeudo() {
        $parametros = Input::get();
        $reglas = array(
            'adeudos_id' => $parametros['adeudos_id']
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Referencia::find($parametros['adeudos_id'])->adeudos();
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
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
            'id' => 'required|integer',
            'adeudos_id' => 'integer',
            'referencia' => ''
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            foreach ($parametros as $key => $value) {
                if (!array_key_exists($key,$reglas)) {
                    unset($parametros[$key]);   
                }
            }
            $res = Referencia::where('id', '=', $parametros['id'])->update($parametros);
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
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
            Referencia::destroy($parametros['id']);
            $res['data'] = Referencia::All();
            return json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

    public function leer_archivo_banco() {
        /*
        $file = Input::file('referencia_archivo');
        if (isset($file)) {
            $data_file = Archivo_referencias::leer($file);
            //return json_encode($data_file);
            foreach ($data_file['referencias'] as $key => $value) {
            $adeudo = Referencia::find();             
            var_dump(json_encode($adeudo));
            die();
            }

        }
        return json_encode(array('error' => true, 'mensaje' => 'No hay archivo', 'respuesta' => ''));
        */
        return Referencia::All();
    }

}
