<?php

class Conceptos extends \Eloquent {
	protected $fillable = ['id','descripcion','concepto'];
	protected $table = 'conceptos';
	public $timestamps = true;
}

?>