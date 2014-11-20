<?php

class Respuesta_bancaria extends \Eloquent {
	protected $fillable = ['estado', 'fecha', 'id', 'monto', 'referencias_adeudos_id', 'referencias_id'];
	protected $table = 'respuesta_bancaria';
	public $timestamps = true;
}

?>