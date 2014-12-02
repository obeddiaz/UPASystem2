<?php

class Adeudos extends \Eloquent {

    protected $fillable = ['fecha_limite', 'id', 'id_persona', 'importe', 'periodo', 'status_adeudo', 'sub_concepto_id', 'grado', 'recargo', 'tipo_recargo', 'paquete_id'];
    protected $table = 'adeudos';
    protected $table_tipoadeudos = 'adeudo_tipopago';
    public $timestamps = true;
    public static $custom_data;

    public static function referencias() {
        return $this
                        ->hasMany('Referencia');
    }

    public function tipo_pago() {
        return $this
                        ->belongsTo('Adeudos_tipopago', 'adeudos_id');
    }

    public static function agregar_adeudos($alumno) {
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
                "grado" => 6,
                "status_adeudo" => 0
            );
            var_dump($adeudo);
            Adeudos::create($adeudo);
            //echo json_encode(Adeudos::$custom_data["paquete"]);
            //echo json_encode($subconcepto->importe);
            //break;
        }
        //return $this->belongsTo('Adeudos_tipopago', 'adeudos_id');
    }

    public static function obtener_adeudos_alumno($data) {
        $commond = new Common_functions();
        $query = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
                ->where("adeudos.id_persona", "=", $data['id_persona'])
                ->where("adeudos.periodo", "=", $data['periodo'])
                ->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'sc.aplica_beca')
                ->get();
        $now = strtotime('now');
        $daynow = date('d', $now);
        $tiene_beca = Becas::AlumnoBeca_Persona_Periodo($data);
        foreach ($query as $key => $adeudo) {
            $tiene_desceunto = Descuentos::obtenerDescuentoPorAdeudo($adeudo['id']);
            if ($adeudo['status_adeudo'] == 0) {
                $fecha_limite = strtotime($adeudo['fecha_limite']);
                $day = date('d', $fecha_limite);
                foreach ($tiene_desceunto as $descuentodata) {
                    $descuento = $commond->calcular_importe_por_tipo($adeudo['importe'], $descuentodata['importe'], $descuentodata['tipo_importe_id']);
                    $query[$key]['tiene_desceunto'] = 1;
                    $query[$key]['importe']-=$descuento;
                }
                if ($daynow > $day) {
                    $query[$key]['meses_retraso'] = $adeudo['meses_retraso'] + 1;
                }
                if ($query[$key]['meses_retraso'] > 0) {
                    if ($tiene_beca) {
                        $databeca = array(
                            "id_persona" => $data['id_persona'],
                            "periodo" => $data['periodo'],
                            "status" => 0
                        );
                        Becas::update_status_beca_alumno($databeca);
                        $tiene_beca = FALSE;
                    }
                    $recargo = $commond->calcular_importe_por_tipo($adeudo['importe'], $adeudo['recargo'], $adeudo['tipo_recargo']);
                    $recargo*= $query[$key]['meses_retraso'];
                    $query[$key]['importe']+=$recargo;
                } elseif ($tiene_beca && ($adeudo['aplica_beca'] == 1)) {
                    $beca = $commond->calcular_importe_por_tipo($adeudo['importe'], $tiene_beca['importe'], $tiene_beca['tipo_importe_id']);
                    $query[$key]['importe']-=$beca;
                }
            } else {
                $pago = strtotime($adeudo['fecha_pago']);
                $fecha_pago = date('d', $pago);
                $fecha_limite = strtotime($adeudo['fecha_limite']);
                $day = date('d', $fecha_limite);
                if ($tiene_desceunto) {
                    $descuento = $commond->calcular_importe_por_tipo($adeudo['importe'], $tiene_desceunto['importe'], $tiene_desceunto['tipo_importe_id']);
                    $query[$key]['tiene_desceunto'] = 1;
                    $query[$key]['importe']-=$descuento;
                }
                if ($fecha_pago > $day) {
                    $query[$key]['meses_retraso'] = $adeudo['meses_retraso'] + 1;
                }
                if ($query[$key]['meses_retraso'] > 0) {
                    $recargo = $commond->calcular_importe_por_tipo($adeudo['importe'], $adeudo['recargo'], $adeudo['tipo_recargo']);
                    $recargo*= $query[$key]['meses_retraso'];
                    $query[$key]['importe']+=$recargo;
                } elseif ($tiene_beca && ($adeudo['aplica_beca'] == 1)) {
                    $beca = $commond->calcular_importe_por_tipo($adeudo['importe'], $tiene_beca['importe'], $tiene_beca['tipo_importe_id']);
                    $query[$key]['importe']-=$beca;
                }
            }
        }
        return $query;
    }

}

?>
	