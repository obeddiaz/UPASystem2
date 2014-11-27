<?php

class Tipo_pago extends \Eloquent {
	protected $fillable = ['created_at', 'descripcion', 'id', 'nombre', 'updated_at'];
	protected $table = 'tipo_pago';
	public $timestamps = true;

	public function adeudos() {
		return $this
				->belongsTo('Adeudos_tipopago', 'tipo_pago_id');
	}
}

?>