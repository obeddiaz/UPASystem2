<?php

class Planes_de_pago extends \Eloquent {

    protected $fillable = ['clave_plan', 'created_at', 'descripcion',
        'id', 'id_agrupaciones', 'updated_at'];
    protected $table = 'plan_de_pago';
    public $timestamps = true;

    public function paquete() {
        return $this->hasMany('Paquete');
    }

    public function agrupaciones() {
        return $this
                        ->belongsTo('Agrupaciones', 'id_agrupaciones');
    }

    public static function paquetes($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $query = Planes_de_pago::join('paqueteplandepago', 'plan_de_pago.id', '=', 'paqueteplandepago.id_plandepago')
                        ->where('id_plandepago', '=', $data['id'])
                        ->where('idnivel', '=',$data['idnivel'])
                        ->where('periodo', '=', $data['periodo'])->first();
        return $query;
    }

    public static function sub_conceptos($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $query = Paquete::join('subconcepto_paqueteplandepago', 'subconcepto_paqueteplandepago.paquete_id', '=', 'paqueteplandepago.id')
                ->join('sub_conceptos', 'sub_conceptos.id', '=', 'subconcepto_paqueteplandepago.sub_concepto_id')
                ->where('paqueteplandepago.id', '=', $data['id'])
                ->select(
                        'paqueteplandepago.id', 
                        'sub_conceptos.*',   
                        'subconcepto_paqueteplandepago.id as scpp_id', 
                        'subconcepto_paqueteplandepago.digito_referencia', 
                        'subconcepto_paqueteplandepago.descripcion_sc',
                        'subconcepto_paqueteplandepago.fecha_de_vencimiento', 
                        'subconcepto_paqueteplandepago.recargo', 
                        'subconcepto_paqueteplandepago.tipo_recargo',
                        'subconcepto_paqueteplandepago.tipos_pago',
                        'subconcepto_paqueteplandepago.recargo_acumulado'
                        )
                ->orderBy('subconcepto_paqueteplandepago.id', 'ASC')
                ->get();
        return $query;
    }

}
