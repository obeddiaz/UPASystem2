<?php

class Prorrogas extends \Eloquent {
	protected $fillable = ['id',
		'adeudos_id',
		'no_oficio',
		'nombre_responsable',
		'rason_prorroga'];
	protected $table = 'prorrogas';
	public $timestamps = true;
}

?>