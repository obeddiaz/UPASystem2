<?php

class  Descuentos extends \Eloquent {
	protected $fillable = ['id','adeudos_id','porcentaje'];
	protected $table = 'descuentos';
	public $timestamps = true;
}

?>