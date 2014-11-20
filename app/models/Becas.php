<?php

class  Becas extends \Eloquent {
	protected $fillable = ['id','idtipo','porcentaje'];
	protected $table = 'Becas';
	public $timestamps = true;
}

?>