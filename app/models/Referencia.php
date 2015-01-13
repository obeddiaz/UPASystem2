<?php

class Referencia extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
	
	public function Adeudos() {
		return $this->belongsTo('Adeudos', 'adeudos_id');
	}

    public static function create_referencia_pagada($data) {
	    $new_insert = DB::table('referencias_pagadas')->insert($data);
	    return $new_insert;
	}

}
