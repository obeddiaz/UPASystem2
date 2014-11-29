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
        //var_dump(Input::get());
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
            Adeudos::$custom_data = array("paquete" => $paquete, "subconcepto" => $subconceptos);
            echo json_encode(Adeudos::$custom_data);
            //echo json_encode(Adeudos::$custom_data);
            foreach ($parametros['id_personas'] as $alumno) {
                Adeudos::agregar_adeudos($alumno);
            }
            //$res = Paquete::create_subconceptos_paquetes($parametros);
            //return json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $res));
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        //return json_encode(Adeudos::$custom_data["paquete"]);
        //return json_encode(array("paquete" => $paquete, "subconcepto" => $subconceptos));
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
     * Muestra los adeudos por periodos.
     *
     * @param  int  $id_periodo
     * @return Response
     */
    public function show_by_periodo() {
        $parametros = Input::get();
        $reglas = array(
            'periodo' => 'required|integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res['data'] = Adeudos::where('periodo', '=', $parametros['periodo']);
            echo json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            echo json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
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
    public function update($id) {
        //
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
