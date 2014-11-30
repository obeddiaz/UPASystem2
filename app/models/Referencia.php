<?php

class Referencia extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
	
	public function Adeudos() {
		return $this->belongsTo('Adeudos', 'adeudos_id');
	}
}
