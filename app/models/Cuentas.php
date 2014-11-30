<?php

class  Cuentas extends \Eloquent {
	protected $fillable = ['id','cuenta','bancos_id', 'created_at', 'updated_at'];
	protected $table = 'cuentas';
	public $timestamps = true;
}

?>