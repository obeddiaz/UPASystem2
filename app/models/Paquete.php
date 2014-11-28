<?php

class Paquete extends \Eloquent {

    protected $fillable = ['created_at', 'id', 'id_plandepago', 'idnivel', 'nivel', 'periodo', 'recargo', 'recargo_inscripcion', 'updated_at'];
    protected $table = 'paqueteplandepago';
    public static $table_subconceptos = 'subconcepto_paqueteplandepago';
    public $timestamps = true;

    public function plan_de_pago() {
        return $this->belongsTo('Planes_de_pago', 'id_plandepago');
    }

    public static function create_subconceptos_paquetes($data) {
        $table = DB::table(Paquete::$table_subconceptos);
        $query = $table->insert($data);
        return $query;
    }

}

?>
