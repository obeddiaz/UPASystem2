<?php 
class  Agrupaciones extends \Eloquent {
	protected $fillable = [ 'created_at', 'descripcion', 'id', 'nombre', 'updated_at'];
	protected $table = 'agrupaciones';
	public $timestamps = true;
}

?>