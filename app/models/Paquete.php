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
    public static function update_subconceptos_paquetes($data) {
        $table = DB::table(Paquete::$subconceptos_paquete);
        $ids_sub=array();
        $paquete=null;
        foreach ($data['sub_concepto'] as $subconcepto) {
            $paquete=$data['paquete_id'];
            $data_subconcepto = array(
                "sub_concepto_id" => $subconcepto['id'],
                "recargo" => $data['recargo'][$subconcepto['id']],
                "tipo_recargo" => $data['tipo_recargo'][$subconcepto['id']],
                "fecha_de_vencimiento" => $subconcepto['fecha'],
                "paquete_id" => $data['paquete_id'],
                "tipos_pago"=>json_encode($data['tipos_pago']),
            );
            if ($subconcepto['idsub_paqueteplan']) {
                $ids_sub[]=$sub_concepto['idsub_paqueteplan'];
                $id=$sub_concepto['idsub_paqueteplan'];
                $table->where('id',$id)
                  ->update($data_subconcepto);
                $adeudos=Adeudos::where('subconcepto_paquete',$sub_concepto['idsub_paqueteplan'])
                        ->get();
                foreach ($adeudos as $key => $adeudo) {
                    DB::table('adeudos')->where('id',$adeudo['id'])
                        ->update(array(
                            'recargo'=>$data['recargo'][$subconcepto['id']],
                            "sub_concepto_id" => $subconcepto['id'],
                            "tipo_recargo" => $data['tipo_recargo'][$subconcepto['id']],
                            "fecha_limite" => $subconcepto['fecha'],
                            "paquete_id" => $data['paquete_id']
                        ));   
                    foreach ($data['tipos_pago'] as $key => $value) {
                        $adeudo_tipopago['adeudos_id']=$adeudo['id'];
                        $adeudo_tipopago['tipo_pago_id']=$value;
                        Adeudos_tipopago::where('adeudos_id',$adeudo['id'])
                                        ->update($adeudo_tipopago);
                    }
                }
            } else {
                $table->insert($data_subconcepto);    
            }
        }

        $query = $table
                ->where('paquete_id', '=', $paquete)
                ->select('id')
                ->get();
        foreach ($query as $key => $i) {
            if (!in_array($i, $ids_sub)) {
                 $table
                 ->where('id', '=', $i)
                 ->delete();
                 DB::table('adeudos')->where('id',$adeudo['id'])
                        ->delete();
            }
        }
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
                ->select(
                    'sc.id', 
                    'sc.importe', 
                    'scp.fecha_de_vencimiento',
                    'scp.recargo',
                    'scp.tipo_recargo',
                    'scp.tipos_pago',
                    'scp.id as idsub_paqueteplan'
                    )
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
