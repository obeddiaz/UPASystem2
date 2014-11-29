<?php

class Adeudos extends \Eloquent {

    protected $fillable = ['fecha_limite', 'id', 'id_persona', 'importe', 'periodo', 'status_adeudo', 'sub_concepto_id', 'grado', 'recargo', 'tipo_recargo', 'paquete_id'];
    protected $table = 'adeudos';
    protected $table_tipoadeudos = 'adeudo_tipopago';
    public $timestamps = true;
    public static $custom_data;

    public static function referencias() {
        return $this
                        ->hasMany('Referencias');
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
        $query = Adeudos::where("id_persona", "=", $data['id_persona'])
                ->where("periodo", "=", $data['periodo'])
                ->get();
        foreach ($query as $key => $adeudo) {
            //var_dump($query[$key]['status_adeudo']);
            if ($adeudo['status_adeudo'] == 0) {
               //select period_diff(date_format(now(), '%Y%m'), date_format(time, '%Y%m')) as months from your_table;
            }
        }
        die();
        return $query;
    }

}

?>
	