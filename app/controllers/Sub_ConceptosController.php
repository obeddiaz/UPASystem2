<?php

class Sub_ConceptosController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $parametros = Input::get();
        $reglas = array(
            'conceptos_id' => 'required|integer',
            'periodo' => 'required',
            'nivel_id' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Sub_conceptos::where('periodo', '=', $parametros['periodo'])
                    ->where('nivel_id', '=', $parametros['nivel_id'])
                    ->where('conceptos_id', '=', $parametros['conceptos_id'])
                    ->get();
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $sii = new Sii();
        $parametros = array(
            'descripcion' => Input::get('descripcion'),
            'sub_concepto' => Input::get('sub_concepto'),
            'conceptos_id' => Input::get('conceptos_id'),
            'importe' => Input::get('importe'),
            'periodo' => Input::get('periodo'),
            'nivel_id' => Input::get('nivel_id')
        );
        $reglas = array(
            'descripcion' => 'required',
            'sub_concepto' => 'required|max:30',
            'conceptos_id' => 'required|integer',
            'importe' => 'required|numeric',
            'periodo' => 'required',
            'nivel_id' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res = Sub_conceptos::create($parametros);
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
            'id' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Sub_conceptos::find($parametros['id']);
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
        $parametros = array(
            'id' => Input::get('id'),
            'descripcion' => Input::get('descripcion'),
            'sub_concepto' => Input::get('sub_concepto'),
            'conceptos_id' => Input::get('conceptos_id'),
            'importe' => Input::get('importe')
        );
        $reglas = array(
            'id' => 'required|integer',
            'sub_concepto' => 'max:30',
            'descripcion' => 'alpha_dash',
            'conceptos_id' => 'integer',
            'importe' => 'numeric'
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {

            $res = Sub_conceptos::where('id', '=', $parametros['id'])->update($parametros);
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
        $sii = new Sii();
        $parametros = Input::get();
        $reglas = array(
            'id' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $res = Sub_conceptos::destroy($parametros['id']);
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

}
