<?php

class Paquete extends \Eloquent {

    protected $fillable = [
                            'id', 'id_plandepago', 'idnivel', 
                            'nivel', 'periodo', 'es_propedeutico'
                            ];
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
        $config = Config::get('utilities');

        $table = DB::table(Paquete::$subconceptos_paquete);
        $ids_sub = array();
        $paquete = $data['paquete_id'];
        
        foreach ($data['sub_concepto'] as $subconcepto) {
            if (isset($subconcepto['idsub_paqueteplan'])) {
                $ids_sub[] = intval($subconcepto['idsub_paqueteplan']); // si existe un paquete cacha el id
            }
        }
        
        $query = $table
                ->where('paquete_id', '=', $paquete)
                ->select('id')
                ->get();

        foreach ($query as $i) {
            if (!in_array(intval($i->id), $ids_sub)) {
                $table = DB::table(Paquete::$subconceptos_paquete);
                $table->where('id', '=', $i->id)->delete();
                DB::table('adeudos')->where('subconcepto_paquete_id', $i->id)->delete();
            }
        }
        foreach ($data['sub_concepto'] as $subconcepto) {
            if (!isset($subconcepto['recargo_acumulado'])) {
                $subconcepto['recargo_acumulado'] = 0;
            }
            $table = DB::table(Paquete::$subconceptos_paquete);
            $data_subconcepto = array( // Crea el array para generar el adeudo
                "sub_concepto_id" => $subconcepto['id'],
                "recargo" => $data['recargo'][$subconcepto['id']],
                "tipo_recargo" => $data['tipo_recargo'][$subconcepto['id']],
                "fecha_de_vencimiento" => $subconcepto['fecha'],
                "paquete_id" => $data['paquete_id'],
                "tipos_pago" => json_encode($data['tipos_pago']),
                "digito_referencia" => $subconcepto['digito_referencia'],
                "descripcion_sc" => $subconcepto['descripcion_sc'],
                "recargo_acumulado"=>$subconcepto['recargo_acumulado'],
                "mes"   => $config['meses'][date('m',strtotime($subconcepto['fecha']))-1]
            );
            if (isset($subconcepto['idsub_paqueteplan'])) { // Verifica si existe un subconcepto en el paquete
                $id = $subconcepto['idsub_paqueteplan']; // obtiene el id del registro subconcepto_paqueteplandepago
                $table->where('id', $id)
                        ->update($data_subconcepto); //actualiza los datos del registro con el id anterior
                $adeudos = Adeudos::where('subconcepto_paquete_id', $subconcepto['idsub_paqueteplan'])
                        ->get(); // busca los adeudos del registro con ese id 
                foreach ($adeudos as $key => $adeudo) { //
                    DB::table('adeudos')
                    ->where('id', $adeudo['id'])
                    ->update(array(
                        "recargo" => $data['recargo'][$subconcepto['id']],
                        "sub_concepto_id" => $subconcepto['id'],
                        "tipo_recargo" => $data['tipo_recargo'][$subconcepto['id']],
                        "fecha_limite" => $subconcepto['fecha'],
                        "paquete_id" => $data['paquete_id'],
                        "digito_referencia" => $subconcepto['digito_referencia'],
                        "descripcion_sc" => $subconcepto['descripcion_sc'],
                        "recargo_acumulado"=> $subconcepto['recargo_acumulado'],
                        "mes"   => $config['meses'][date('m',strtotime($subconcepto['fecha']))-1]
                    ));
                    foreach ($data['tipos_pago'] as $key => $value) {
                        $adeudo_tipopago['adeudos_id'] = $adeudo['id'];
                        $adeudo_tipopago['tipo_pago_id'] = $value;
                        Adeudos_tipopago::where('adeudos_id', $adeudo['id'])
                                ->update($adeudo_tipopago);
                    }
                }
            } else {
                $table->insert($data_subconcepto);
            }
        }
        return TRUE;
    }

    /*
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
     */

    public static function show_paquete_subconceptos($id) {
        $table = DB::table(Paquete::$subconceptos_paquete . ' as scp');
        $query = $table
                ->where('paquete_id', '=', $id)
                ->join(Paquete::$subconceptos . ' as sc', 'sc.id', '=', 'scp.sub_concepto_id')
                ->join('conceptos','sc.conceptos_id','=','conceptos.id')
                ->select(
                    'sc.id', 'sc.importe', 'scp.fecha_de_vencimiento',
                    'scp.recargo', 'scp.tipo_recargo', 'scp.tipos_pago',
                    'scp.recargo_acumulado' ,'scp.id as idsub_paqueteplan', 
                    'scp.digito_referencia', 'scp.descripcion_sc','scp.recargo_acumulado', 'sc.sub_concepto',
                    'sc.aplica_beca','sc.es_inscripcion','sc.tipo_adeudo','scp.mes','conceptos.cuenta_id'
                )
                ->get();
        return $query;
    }

    public static function personasPaquete($id) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $table = DB::table('paqueteplandepago');
        $query = $table
            ->join('adeudos', 'adeudos.paquete_id', '=', 'paqueteplandepago.id')
            ->select('id_persona')
            ->where('paqueteplandepago.id', '=', $id)
            ->groupBy('id_persona')->get();
        return $query;
    }

    public static function personasNoPaquete($id) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $table = DB::table('paqueteplandepago');
        $query = $table
                ->join('adeudos', 'adeudos.paquete_id', '=', 'paqueteplandepago.id')
                ->select('id_persona')
                ->where('paqueteplandepago.id', '!=', $id)
                ->groupBy('id_persona')->get();
        return $query;
    }

}

?>
