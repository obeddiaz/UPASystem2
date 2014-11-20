<?php

class  Adeudos extends \Eloquent {
	protected $fillable = ['becas_id','fecha_limite', 'id', 'id_persona', 'importe', 
							'periodo', 'status_adeudo', 'status_beca', 'sub_conceprto_id', 'tipo_adeudo_id'];
	protected $table = 'adeudos';
	protected $table = 'adeudo_tipopago';
	public $timestamps = true;
}

?>
	