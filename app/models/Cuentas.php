<?php

class  Cuentas extends \Eloquent {
	protected $fillable = ['id','cuenta','bancos_id'];
	protected $table = 'cuentas';
	public $timestamps = true;
}

?>