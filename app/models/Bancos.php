<?php

class Bancos extends \Eloquent {
	protected $fillable = ['banco', 'created_at', 'descripcion', 'id', 'updated_at'];
	protected $table = 'bancos';
	public $timestamps = true;
}

?>