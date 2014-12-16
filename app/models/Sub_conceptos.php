<?php

class Sub_conceptos extends \Eloquent {

    protected $fillable = ['id', 'descripcion', 'sub_concepto', 'conceptos_id', 'importe', 'periodo', 'nivel_id','tipo_adeudo'];
    protected $table = 'sub_conceptos';
    protected $table_plandepagos = "subconcepto_plandepago";
    protected $table_tipopago = "subconcepto_tipopago";
    public $timestamps = true;

    public function conceptos() {
        return $this->belongsTo('conceptos', 'conceptos_id');
    }

}

?>