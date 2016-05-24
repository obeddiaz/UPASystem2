<?php

class Registros extends Eloquent {
	protected $fillable = [	'id',	'asignada_por',	'razon',	'email_login_asignacion',	'adeudo_id',	'pago_id'	];
	protected $table = 'registro_pago';
	public $timestamps = true;
}

?>