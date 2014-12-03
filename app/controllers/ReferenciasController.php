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
        $commond = new Common_functions();
        $parametros=Input::get();
         
        $reglas = array( 
            'adeudos'  => 'required'
        );
        $validator = Validator::make($parametros,$reglas);

        if (!$validator->fails())
        {
            $libereriaReferencia = new Referencias();
            $data=array();
            $data['importe_total']=0;
            foreach ($parametros['adeudos'] as $key => $value) {
                if ($value['status_adeudo']==0) {    
                    foreach ($parametros['adeudos'] as $key_a => $value_a) {
                        if (!isset($fecha_limite)) {
                            if ($value_a['fecha_limite']>date('Y-m-d')) {
                                $fecha_limite=$value_a['fecha_limite'];
                            } else {
                                if ($parametros['adeudos'][count($parametros['adeudos'])-1]['fecha_limite']==$value_a['fecha_limite'] && !isset($fecha_limite)) {
                                    $fecha=date('Y-m-d');
                                    $months= $value_a['meses_retraso'];
                                    $fecha_limite=date("Y-m-d", strtotime("$fecha +$months month"));           
                                }
                            }
                        }
                    }
                    $subconcepto=Sub_conceptos::find($value['sub_concepto_id']);
                    $referencia=sprintf('%05d',$value['id_persona']).
                                sprintf('%03d',$value['periodo']).
                                sprintf('%03d',$value['sub_concepto_id']).
                                sprintf('%01d',$value['contador']);
                    $data['referencias'][$key]['referencia']=$libereriaReferencia->Generar($referencia,$value['importe'],$fecha_limite);
                    $data['referencias'][$key]['importe']=json_decode($value['importe']);
                    $data['referencias'][$key]['sub_concepto']=$subconcepto['sub_concepto'];
                    $data['importe_total']+=$value['importe'];
                    $data['fecha_limite']=$fecha_limite;
                    $data['periodo']=$value['periodo'];
                    $data['persona']=$commond->obtener_infoAlumno_idPersona(array('id_persona'=>$value['id_persona']));
                    $existe_referencia=Referencia::where('referencia','=',$data['referencias'][$key]['referencia'])->first();
                    $cuentas=Cuentas::where('activo_cobros','=',1)->first();
                    $data['convenio']=$cuentas['cuenta'];
                    if (!$existe_referencia) {
                         Referencia::create(
                            array(
                                'referencia'=>$data['referencias'][$key]['referencia'],
                                'adeudos_id'=>$value['id'],
                                'cuentas_id'=>$cuentas['id']
                            ));
                    }
                }
            }
            $res['data']=$data;
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
        
        $file = Input::file('referencia_archivo');
        if (isset($file)) {
            $data_file = Archivo_referencias::leer($file);
            //$respuesta_bancaria['nombre_archivo']=Input::file('referencia_archivo')->getClientOriginalName();
            //return json_encode($data_file);
            foreach ($data_file['referencias'] as $key => $value) {
                $adeudo = Referencia::with('adeudos')
                        ->where('referencia','=',$value['referencia']);             
                //var_dump(json_encode($adeudo));
                echo json_encode($adeudo);
                die();
            }

        }
        return json_encode(array('error' => true, 'mensaje' => 'No hay archivo', 'respuesta' => ''));
    }

}
