<?php

class Paquete extends \Eloquent {

    protected $fillable = ['created_at', 'id', 'id_plandepago', 'idnivel', 'nivel', 'periodo', 'recargo', 'recargo_inscripcion', 'updated_at'];
    protected $table = 'paqueteplandepago';
    public static $subconceptos = 'sub_conceptos';
    public static $subconceptos_paquete = 'subconcepto_paqueteplandepago';
    public $timestamps = true;
    public function adeudos() {
        return $this->hasMany('Adeudos');
    }

    public function plan_de_pago() {
        return $this->belongsTo('Planes_de_pago', 'id_plandepago');
    }
    public static function delete_subconceptos_paquetes($paquete_id) {
        $table = DB::table(Paquete::$subconceptos_paquete);
        $table->where('paquete_id', '=', $paquete_id)->delete();
        return TRUE;
    }
    public static function create_subconceptos_paquetes($data) {
        $table = DB::table(Paquete::$subconceptos_paquete);
        foreach ($data['sub_concepto'] as $subconcepto) {
            $data_subconcepto = array(
                "sub_concepto_id" => $subconcepto['id'],
                "recargo" => $data['recargo'][$subconcepto['id']],
                "tipo_recargo" => $data['tipo_recargo'][$subconcepto['id']],
                "fecha_de_vencimiento" => $subconcepto['fecha'],
                "paquete_id" => $data['paquete_id'],
                "tipos_pago"=>json_encode($data['tipos_pago']),
            );
            $table->insert($data_subconcepto);
        }
        return TRUE;
    }

    public static function show_paquete_subconceptos($id) {
        $table = DB::table(Paquete::$subconceptos_paquete . ' as scp');
        $query = $table
                ->where('paquete_id', '=', $id)
                ->join(Paquete::$subconceptos . ' as sc', 'sc.id', '=', 'scp.sub_concepto_id')
                ->select('sc.id', 'sc.importe', 'scp.fecha_de_vencimiento','scp.recargo','scp.tipo_recargo','scp.tipos_pago')
                ->get();
        return $query;
    }
    public static function personasPaquete($id) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $table = DB::table('paqueteplandepago');
        $query=$table
                ->join('adeudos','adeudos.paquete_id','=','paqueteplandepago.id')
                ->select('id_persona')
                ->where('paqueteplandepago.id','=',$id)
                ->groupBy('id_persona')->get();
        return $query;
    }
    public static function personasNoPaquete($id) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $table = DB::table('paqueteplandepago');
        $query=$table
                ->join('adeudos','adeudos.paquete_id','=','paqueteplandepago.id')
                ->select('id_persona')
                ->where('paqueteplandepago.id','!=',$id)
                ->groupBy('id_persona')->get();
        return $query;
    }

}

?>
