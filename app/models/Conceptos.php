<?php

class Conceptos extends Eloquent {
	protected $fillable = ['id','descripcion','concepto','banco_id','cuenta_id'];
	protected $table = 'conceptos';
	public $timestamps = true;
	
	public function subconceptos() {
		return $this->hasMany('Sub_conceptos');
	}
}

?>