<?php

class Planes_de_pago extends \Eloquent {
	protected $fillable = ['clave_plan', 'created_at', 'descripcion',
							 'id', 'id_agrupaciones','updated_at'];
	protected $table = 'plan_de_pago';
	public $timestamps = true;
	public function paquete() {
		return $this->hasMany('Paquete');
	}

	public function agrupaciones() {
		return $this
				->belongsTo('Agrupaciones', 'id_agrupaciones');
	}
}

?>