<?php

class Adeudos_tipopago extends \Eloquent {
	protected $fillable = [ 'adeudos_id', 'created_at', 'id', 'tipo_pago_id', 'updated_at'];
	protected $table = 'banco';
	public $timestamps = true;
}

?>