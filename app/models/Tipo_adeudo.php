<?php

class Tipo_adeudo extends \Eloquent {
	protected $fillable = ['descripcion', 'id', 'nombre'];
	protected $table = 'tipo_adeudo';
	public $timestamps = true;
}

?>