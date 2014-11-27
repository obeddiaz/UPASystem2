<?php

class Tipo_adeudo extends \Eloquent {
	protected $fillable = ['created_at', 'descripcion', 'id', 'nombre', 'updated_at'];
	protected $table = 'tipo_adeudo';
	public $timestamps = true;
}

?>