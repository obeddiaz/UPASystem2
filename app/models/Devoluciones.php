<?php

class Devoluciones extends \Eloquent {
	protected $fillable = ['created_at', 
						   'fecha_devolucion', 
						   'id', 
						   'importe', 
						   'periodo',
						   'status_devolucion',
						   'id_persona',
						   'updated_at'];
	protected $table = 'devoluciones';
	public $timestamps = true;
}

?>