<?php

class Sub_conceptos extends \Eloquent {
	protected $fillable = ['id','descripcion','sub_concepto','concepto_id','importe'];
	protected $table = 'sub_conceptos';
	protected $table_plandepagos="subconcepto_plandepago";
	protected $table_tipopago="subconcepto_tipopago";
	public $timestamps = true;
}

?>