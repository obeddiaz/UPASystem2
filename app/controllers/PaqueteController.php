<?php

class PaqueteController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $parametros = Input::all();
        
        $reglas = array(
            'id_plandepago' => 'required',
            'idnivel' => 'required|integer',
            'nivel' => 'required',
            'periodo' => 'required|integer',
            'es_propedeutico'   => 'integer'
        );

        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {

            $res['data'] = Paquete::create($parametros);
            $respuesta = json_encode(array('error' => false, 'mensaje' => 'Nuevo registro', 'respuesta' => $res));
            
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }

        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function create_subconcepto() {
        $parametros = array(
            'paquete_id' => Input::get('paquete_id'),
            'sub_concepto' => Input::get('sub_concepto'),
            'recargo' => Input::get('recargo'),
            'tipo_recargo' => Input::get('tipo_recargo'),
            'tipos_pago' => Input::get('tipos_pago'),
        );
        $reglas = array(
            'paquete_id' => 'required|integer',
            'sub_concepto' => 'required|array',
            'recargo' => 'required|array',
            'tipo_recargo' => 'required|array',
            'tipos_pago'=> 'required|array',
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {            
           $res = Paquete::create_subconceptos_paquetes($parametros);
           $respuesta = json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $res));
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
            'id' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Paquete::find($parametros['id']);
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show_nivel_periodo() {
        $parametros = Input::get();
        $reglas = array(
            'idnivel' => 'required|integer',
            'periodo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Paquete::where('idnivel', '=', $parametros['idnivel'])
                    ->where('periodo', '=', $parametros['periodo']);
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
    /*
    public function update() {
        $parametros = array(
            'id' => Input::get('id'),
            'id_plandepago' => Input::get('id_plandepago'),
            'idnivel' => Input::get('idnivel'),
            'nivel' => Input::get('nivel'),
            'periodo' => Input::get('periodo'),
            'recargo' => Input::get('recargo'),
            'recargo_inscripcion' => Input::get('recargo_inscripcion'),
            'sub_concepto' => Input::get('sub_concepto'),
        );
        $reglas = array(
            'id' => 'required|integer',
            'id_plandepago' => 'integer',
            'idnivel' => 'integer',
            'nivel' => 'alpha_dash',
            'periodo' => 'integer',
            'recargo' => 'numeric',
            'recargo_inscripcion' => 'numeric',
            'sub_concepto' => 'array',
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $subconceptos_paquete=array();
            if (isset($parametros['sub_concepto'])) {
                Paquete::update_subconceptos_paquetes($parametros);
            }
            $res = Paquete::where('id', '=', $parametros['id'])->update($parametros);
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }*/

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
            Paquete::destroy($parametros['id']);
            $res['data'] = Paquete::All();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

}
