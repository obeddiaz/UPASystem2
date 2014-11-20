<?php

class Planes_de_pago extends \Eloquent {
	protected $fillable = ['id','idnivel','nivel','periodo','clave_plan','descripcion'];
	protected $table = 'plan_de_pago';
	public $timestamps = true;
}

?>