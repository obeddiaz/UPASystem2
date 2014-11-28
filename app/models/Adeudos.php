<?php

class Adeudos extends \Eloquent {

    protected $fillable = ['becas_id', 'fecha_limite', 'id', 'id_persona', 'importe',
        'periodo', 'status_adeudo', 'status_beca', 'sub_conceprto_id', 'tipo_adeudo_id'];
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
            Adeudos::insert(
                    array(
                        "sub_concepto_id" => $subconcepto->id,
                        "id_persona" => $alumno,
                        "importe" => $subconcepto->importe,
                        "fecha_limite" => $subconcepto->fecha_de_vencimiento,
                        "periodo" => Adeudos::$custom_data["paquete"]->periodo,
                        "paquete_id" => Adeudos::$custom_data["paquete"]->id,
                        "tipo_adeudo_id" => 1,
                        "grado" => 6
                    )
            );
            //echo json_encode(Adeudos::$custom_data["paquete"]);
            //echo json_encode($subconcepto->importe);
            //break;
        }
        //return $this->belongsTo('Adeudos_tipopago', 'adeudos_id');
    }

}

?>
	