<?php

class Bancos extends \Eloquent {
	protected $fillable = ['id','banco','descripcion'];
	protected $table = 'banco';
	public $timestamps = true;
}

?>