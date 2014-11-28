<?php

class Paquete extends \Eloquent {

    protected $fillable = ['created_at', 'id', 'id_plandepago', 'idnivel', 'nivel', 'periodo', 'recargo', 'recargo_inscripcion', 'updated_at'];
    protected $table = 'paqueteplandepago';
    public static $subconceptos = 'sub_conceptos';
    public static $subconceptos_paquete = 'subconcepto_paqueteplandepago';
    public $timestamps = true;

    public function plan_de_pago() {
        return $this->belongsTo('Planes_de_pago', 'id_plandepago');
    }

    public static function create_subconceptos_paquetes($data) {
        $table = DB::table(Paquete::$subconceptos_paquete);
        $query = $table->insert($data);
        return $query;
    }

    public static function show_paquete_subconceptos($id) {
        $table = DB::table(Paquete::$subconceptos_paquete . ' as scp');
        $query = $table
                ->where('paquete_id', '=', $id)
                ->join(Paquete::$subconceptos . ' as sc', 'sc.id', '=', 'scp.id')
                ->select('sc.id', 'sc.importe', 'scp.fecha_de_vencimiento')
                ->get();
        return $query;
    }

}

?>
