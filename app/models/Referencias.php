<?php

class Referencias extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
}

?>