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
            $periodo_actual=$commond->periodo_actual();
            $adeudos_no_pagados=Adeudos::where('id_persona','=',$parametros('id_personas'))
                        ->where('periodo','!=',$periodo_actual['idperiodo'])
                        ->where('status_adeudo','=',0)->count();
            if ($adeudos_no_pagados==0) {
                $paquete = Paquete::find($parametros['paquete_id']);
                $subconceptos = Paquete::show_paquete_subconceptos($parametros['paquete_id']);
                Adeudos::$custom_data = array("paquete" => $paquete, "subconcepto" => $subconceptos);
                echo json_encode(Adeudos::$custom_data);
                foreach ($parametros['id_personas'] as $alumno) {
                    Adeudos::agregar_adeudos($alumno);
                }
                return json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $res));
            } else {
                return json_encode(array('error' => true, 'mensaje' => 'Tiene adeudos sin pagas de otro periodo.', 'respuesta' => null));
            }
        } else {
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        //return json_encode(Adeudos::$custom_data["paquete"]);
        //return json_encode(array("paquete" => $paquete, "subconcepto" => $subconceptos));
    }

    public function createSubconcepto() {
        //var_dump(Input::get());
        //
        $parametros = array(
            'subconcepto_id' => Input::get('subconcepto_id'),
            'periodo' => Input::get('periodo'),
            'id_personas' => Input::get('id_personas'),
            'fecha_limite' => Input::get('fecha_limite')
        );
        $reglas = array(
            'subconcepto_id' => 'required|integer',
            'periodo' => 'required|integer',
            'id_personas' => 'required|integer',
            'fecha_limite' => 'date_format:Y-m-d'
        );
        $commond = new Common_functions();
        $grado = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $alumno));
        $validator = Validator::make($parametros, $reglas);
        if (!$validator->fails()) {
            $periodo_actual=$commond->periodo_actual();
            $adeudos_no_pagados=Adeudos::where('id_persona','=',$parametros('id_personas'))
                        ->where('periodo','!=',$periodo_actual['idperiodo'])
                        ->where('status_adeudo','=',0)->count();
            if ($adeudos_no_pagados==0) {
               $subconcepto = Sub_conceptos::find($parametros['subconcepto_id']);
               $adeudo = array(
                    'importe' => $subconcepto['importe'],
                    'sub_concepto_id' => $subconcepto['id'],
                    'fecha_limite' =>  $parametros['fecha_limite'],
                    'grado' => $alumno['grado']
                );
               $res = Adeudos::create($adeudo);
            }
            return json_encode(array('error' => false, 'mensaje' => 'Subconceptos Agregados Correctamente a Paquete', 'respuesta' => $res));
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
            return json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        } else {
            $alumno = Adeudos::obtener_adeudos_alumno($parametros);
            return json_encode(array('error' => false, 'mensaje' => 'Referencias de alumno.', 'respuesta' => $alumno));
        }
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
