<?php

class Tipo_pago extends \Eloquent {
	protected $fillable = ['descripcion', 'id', 'nombre'];
	protected $table = 'tipo_pago';
	public $timestamps = true;
}

?>