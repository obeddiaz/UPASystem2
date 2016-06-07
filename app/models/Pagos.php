<?php

class Pagos extends \Eloquent {

    protected $fillable = [
							'id',	'importe',	'fecha_pago',	'tipo_pago',	'forma_pago',	'importe_recargo',
							'adeudos_id',	'referencia_id',	'beca',	'cueta_id',	'descuento',	'lugar', 'descuento_recargo',
							'total'	];

    protected $table = 'pagos';
    public $timestamps = true;

    public function createPagoComplete($data)	{
		    	
    }
}