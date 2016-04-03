<?php

class Registros extends Eloquent {
	protected $fillable = ['id','importe_registro_pago','adeudo_id','asignada_por','razon'];
	protected $table = 'registro_pago';
	public $timestamps = true;
}

?>