<?php

class Respuesta_bancaria extends \Eloquent {
	protected $fillable = ['id', 'fecha', 'monto', 'created_at', 'updated_at', 'no_transacciones', 'cobro_inmediato', 'comisiones_creadas', 'remesas', 'comisiones_remesas', 'abonado', 'nombre_archivo'];
	protected $table = 'respuesta_bancaria';
	public $timestamps = true;
}

?>