<?php

class  Cuentas extends \Eloquent {
	protected $fillable = ['id','cuenta','bancos_id','activo_cobros' ,'created_at', 'updated_at'];
	protected $table = 'cuentas';
	public $timestamps = true;
}

?>