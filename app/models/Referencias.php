<?php

class Referencias extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
	
	public static function adeudos() {
		return $this
				->belongsTo('adeudos', 'adeudos_id');
	}
}

?>