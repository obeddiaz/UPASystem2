<?php

class Referencia extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
	
<<<<<<< HEAD:app/models/Referencias.php
	public function adeudos() {
		return $this
				->belongsTo('adeudos', 'adeudos_id');
=======
	public function Adeudos() {
		return $this->belongsTo('Adeudos', 'adeudos_id');
>>>>>>> master:app/models/Referencia.php
	}
}
