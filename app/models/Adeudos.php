<?php

class Adeudos extends \Eloquent {

    protected $fillable = [
        'fecha_limite', 'id', 'id_persona',
        'importe', 'periodo', 'status_adeudo',
        'sub_concepto_id', 'grado', 'recargo',
        'tipo_recargo', 'paquete_id', 'subconcepto_paquete_id',
        'digito_referencia', 'descripcion_sc', 'recargo_acumulado',
        'aplica_beca','aplica_recargo','locker_manager','recargo_pago','beca_pago','importe_pago'];
    protected $table = 'adeudos';
    protected $table_tipoadeudos = 'adeudo_tipopago';
    public $timestamps = true;
    public static $custom_data;

    public static function obtener_adeudos_periodo($periodo) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('adeudos');
        $query = $Temporaltable->join('sub_conceptos', 'adeudos.sub_concepto_id', '=', 'sub_conceptos.id')
                ->join('conceptos','sub_conceptos.conceptos_id','=','conceptos.id')
                ->where('adeudos.periodo', '=', $periodo)
                ->select('sub_conceptos.sub_concepto', 'adeudos.*','conceptos.concepto', 'conceptos.descripcion as descripcion_concepto')
                ->get();
        return $query;
    }

    public static function obtener_adeudos_id($id) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('adeudos');
        $query = $Temporaltable->join('sub_conceptos', 'adeudos.sub_concepto_id', '=', 'sub_conceptos.id')
                ->where('adeudos.id', '=', $id)
                ->select('sub_conceptos.sub_concepto', 'adeudos.*')
                ->first();
        return $query;
    }

    public function paquete() {
        return $this->belongsTo('Paquete', 'paquete_id');
    }

    public static function referencias() {
        return $this
                        ->hasMany('Referencia');
    }

    public function tipo_pago() {
        return $this
                        ->belongsTo('Adeudos_tipopago', 'adeudos_id');
    }

    public static function agregar_adeudos($alumno) {

        //  Se obtiene los datos de el alumno desde el api por medio del id_persona 
        //  ---->

        $commond = new Common_functions();
        $grado = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $alumno));

        if (isset($grado[0]['grado'])) {
            $grado = $grado[0]['grado'];
        } else {
            $grado = null;
        }
        // <----
        //  Dependiendo de los subconceptos que se reciban son los adeudos que
        //  se generaran, se construye el array para la tabla
        //  ---->

        foreach (Adeudos::$custom_data["subconcepto"] as $subconcepto) {
            $adeudo = array(
                "sub_concepto_id" => $subconcepto->id,
                "id_persona" => $alumno,
                "importe" => $subconcepto->importe,
                "fecha_limite" => $subconcepto->fecha_de_vencimiento,
                "periodo" => Adeudos::$custom_data["paquete"]->periodo,
                "paquete_id" => Adeudos::$custom_data["paquete"]->id,
                "recargo" => $subconcepto->recargo,
                "tipo_recargo" => $subconcepto->tipo_recargo,
                "subconcepto_paquete_id" => $subconcepto->idsub_paqueteplan,
                "digito_referencia" => $subconcepto->digito_referencia,
                "grado" => $grado,
                "status_adeudo" => 0,
                "descripcion_sc" => $subconcepto->descripcion_sc,
                "recargo_acumulado" => $subconcepto->recargo_acumulado,
                "aplica_beca" => $subconcepto->aplica_beca,
                "locker_manager" => $subconcepto->locker_manager
            );
            $adeudo = Adeudos::create($adeudo);
            //  Se gnera el registro de los tipos de pago que tendra el adeudo
            //  caja ó por medio del banco, se reciben en json y se descodifican
            //  ---->
            foreach (json_decode($subconcepto->tipos_pago) as $key => $value) {
                $adeudo_tipopago['adeudos_id'] = $adeudo['id'];
                $adeudo_tipopago['tipo_pago_id'] = $value;
                Adeudos_tipopago::create($adeudo_tipopago);
            }
            // <----
        }
        // <----
    }

    public static function obtener_adeudos_reporte($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('adeudos');
        $query = $Temporaltable
                ->join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
                ->join('conceptos','sc.conceptos_id','=','conceptos.id')
                #->join('descuentos as des', 'des.adeudos_id','=','adeudos.id','right')
                ->select('adeudos.*', 
                          DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 
                          'sc.aplica_beca', 
                          'sc.sub_concepto',
                          'conceptos.concepto', 
                          'conceptos.descripcion as descripcion_concepto');
        if (isset($data['fecha_desde']) && isset($data['fecha_hasta'])) {
            $query = $query->where("adeudos.fecha_limite", ">=", $data['fecha_desde'])
                    ->where("adeudos.fecha_limite", "<=", $data['fecha_hasta'])
                    ->where("adeudos.status_adeudo","=",$data['status']);
        } else {
            if (isset($data['periodo'])) {
                $query = $query->where("adeudos.periodo", "=", $data['periodo'])
                               ->where("adeudos.status_adeudo","=",$data['status']);;
            }
        }
        return $query->get();
    }

    public static function obtener_adeudos_reporte_filtrado($data, $campos) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('adeudos');

        foreach ($campos as $campo) {
            if ($campo=='aplica_beca') {
                $campos_finales[] = "sc.aplica_beca";
            } elseif ($campo=='sub_concepto') {
                $campos_finales[] = "sc.sub_concepto";
            } elseif ($campo=='meses_retraso') {
                $campos_finales[] = DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso");
            } else {
                $campos_finales[] = "adeudos." . $campo;
            }
        }        
        $query = $Temporaltable
                ->join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
                ->select($campos_finales);
                
        if (isset($data['fecha_desde']) && isset($data['fecha_hasta'])) {
            $query = $query->where("adeudos.fecha_limite", ">=", $data['fecha_desde'])
                    ->where("adeudos.fecha_limite", "<=", $data['fecha_hasta']);
        } else {
            if (isset($data['periodo'])) {
                $query = $query->where("adeudos.periodo", "=", $data['periodo']);
            }
        }
        return $query->get();
    }

    public static function obtener_adeudos_alumno($data) {
        $commond = new Common_functions();
        // Actualiza el status_adeudo si tiene beca del 100%
        $commond->actualiza_status_adeudos($data['id_persona'], $data['periodo']);
        #var_dump($data);die();
        if (isset($data['id'])) {
            $query = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
                #->where("adeudos.id_persona", "=", $data['id_persona'])
                #->where("adeudos.periodo", "=", $data['periodo'])
                ->where("adeudos.id","=",$data['id'])
                ->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'adeudos.aplica_beca', 'sc.sub_concepto')
                ->get(); // Se obtienen los adeudos de una persona en el periodo solicitado
                $now = strtotime($data['fecha_pago']); // Se obtiene la fecha actual
        } else {
            $query = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
                ->where("adeudos.id_persona", "=", $data['id_persona'])
                ->where("adeudos.periodo", "=", $data['periodo'])
                ->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'adeudos.aplica_beca', 'sc.sub_concepto')
                ->get(); // Se obtienen los adeudos de una persona en el periodo solicitado
                $now = strtotime('now'); // Se obtiene la fecha actual
        }

        $tiene_beca = Becas::AlumnoBeca_Persona_Periodo($data); // Consulta beca

        $daynow = date('d', $now); // Dia actual
        $sub_cont = array(); // Contador de adeudos
        $lock=false;
        foreach ($query as $key => $adeudo) { // Se genera la informacion para el Edo. de cuenta del alumno
            $query[$key]['tipos_pago'] = Adeudos_tipopago::where('adeudos_id', '=', $adeudo['id'])->get();
            $query[$key]['importe_inicial'] = $query[$key]['importe'];
            if (isset($sub_cont[$adeudo['sub_concepto_id']])) {
                $sub_cont[$adeudo['sub_concepto_id']]+=1;
            } else {
                $sub_cont[$adeudo['sub_concepto_id']] = 0;
            }
            $query[$key]['contador'] = $sub_cont[$adeudo['sub_concepto_id']];
            $tiene_desceunto = Descuentos::obtenerDescuentoPorAdeudo($adeudo['id']);

            // Limitando a solo un descuento por adeudo 
            $descuentos_limitado = array();
            if (isset($tiene_desceunto[0])) {
                $descuentos_limitado[0] = $tiene_desceunto[0];
            }
            $fecha_limite = strtotime($adeudo['fecha_limite']);
            $day = date('d', $fecha_limite);
            $descuento = 0;
            $descuento_recargo =0;
            $descuento_id = null;
            foreach ($descuentos_limitado as $key_d => $descuentodata) {
                $descuento_tmp = $commond->calcular_importe_por_tipo($adeudo['importe'], $descuentodata['importe'], $descuentodata['tipo_importe_id']);
                $descuento_recargo_temp = $commond->calcular_importe_por_tipo($adeudo['importe'], $descuentodata['importe_recargo'], $descuentodata['tipo_importe_id']);
                $query[$key]['tiene_desceunto'] = 1;
                $descuento_recargo = $descuento_recargo + $descuento_recargo_temp;
                $descuento = $descuento + $descuento_tmp;
                $descuento_id = $descuentodata['id'];
            }
            $query[$key]['descuento_id'] = $descuento_id;
            $query[$key]['descuento'] = $descuento;
            $query[$key]['descuento_recargo'] = $descuento_recargo;
            if (!$tiene_beca) {
                $query[$key]['beca'] = 'N/A';
            }

            if ($adeudo['status_adeudo'] == 0) {
                if ($daynow > $day) {
                    $query[$key]['meses_retraso'] = $adeudo['meses_retraso'] + 1;
                }
                if ($query[$key]['meses_retraso'] <= 0) {
                    $query[$key]['recargo_total'] = 0;
                }
                if ($query[$key]['meses_retraso'] > 0) {
                    if ($adeudo['locker_manager']==1) {
                        $lock=true;
                    }
                    $query[$key]['beca'] = 'N/A';
                    if ($tiene_beca) {
                        $databeca = array(
                            "id_persona" => $data['id_persona'],
                            "periodo" => $data['periodo'],
                            "status" => 0
                        );
                        Becas::update_status_beca_alumno($databeca);
                        $tiene_beca = FALSE;
                    }
                    if ($adeudo['aplica_recargo']==1) {
                        $recargo = $commond->calcular_importe_por_tipo($adeudo['importe'], $adeudo['recargo'], $adeudo['tipo_recargo']);
                    } else {
                        $recargo = 0;
                    }
                    if ($adeudo['recargo_acumulado'] == 1) {
                        $recargo*= $query[$key]['meses_retraso'];
                    }
                    $query[$key]['recargo_no_descuento'] = $recargo;
                    $query[$key]['recargo_total'] = $recargo-$descuento_recargo;
                    #$query[$key]['recargo_total'] = $query[$key]['recargo_total']
                    $query[$key]['importe'] += ($query[$key]['recargo_total'] - $descuento);
                } elseif ($tiene_beca && ($adeudo['aplica_beca'] == 1)) {
                    $beca = $commond->calcular_importe_por_tipo($adeudo['importe'], $tiene_beca['importe'], $tiene_beca['tipo_importe_id']);
                    $query[$key]['importe']-=$beca;
                    $query[$key]['importe']-=$descuento;
                    $query[$key]['beca'] = $beca;
                }
                if ($lock==true) {
                    $query[$key]['lock'] = 1;   
                } else {
                    $query[$key]['lock'] = 0;   
                }
            } else {
                $pago = strtotime($adeudo['fecha_pago']);
                $fecha_pago = date('d', $pago); 
                $query[$key]['lock'] = 0;
                if ($fecha_pago > $day) {
                    $query[$key]['meses_retraso'] = $adeudo['meses_retraso'] + 1;
                }
                if ($query[$key]['meses_retraso'] <= 0) {
                    $query[$key]['recargo_total'] = 0;
                }
                if ($query[$key]['meses_retraso'] > 0) {
                    $query[$key]['beca'] = 'N/A';
                    if ($adeudo['aplica_recargo']==1) {
                        $recargo = $commond->calcular_importe_por_tipo($adeudo['importe'], $adeudo['recargo'], $adeudo['tipo_recargo']);
                    } else {
                        $recargo = 0;
                    }
                    $query[$key]['recargo_no_descuento'] = null;
                    if ($adeudo['recargo_acumulado'] == 1) {
                        $recargo*= $query[$key]['meses_retraso'];
                    }
                    $query[$key]['recargo_total'] = $adeudo['recargo_pago'];
                    if ( intval($adeudo['beca_pago'])==0) {
                        $query[$key]['beca'] = 'N/A';
                    } else {
                        $query[$key]['beca'] = $adeudo['beca_pago'];
                    }
                    $query[$key]['importe']= $adeudo['importe_pago'];
                } elseif ($tiene_beca && ($adeudo['aplica_beca'] == 1)) {
                    $beca = $commond->calcular_importe_por_tipo($adeudo['importe'], $tiene_beca['importe'], $tiene_beca['tipo_importe_id']);
                    #$query[$key]['importe']-=$beca;
                    $query[$key]['beca'] = $adeudo['beca_pago'];
                }
            }
        }
        return $query;
    }

}
