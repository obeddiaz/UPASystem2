<?php

class  Descuentos extends \Eloquent {
	protected $fillable = ['id','tipos_importe_id','adeudos_id', 'importe', 'created_at', 'updated_at'];
	protected $table = 'descuentos';
	public $timestamps = true;
}

?>