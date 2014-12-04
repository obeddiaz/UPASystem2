<?php

class Planes_de_pago extends \Eloquent {
	protected $fillable = ['clave_plan', 'created_at', 'descripcion',
							 'id', 'id_agrupaciones','updated_at'];
	protected $table = 'plan_de_pago';
	public $timestamps = true;
	public function paquete() {
		return $this->hasMany('Paquete');
	}

	public function agrupaciones() {
		return $this
				->belongsTo('Agrupaciones', 'id_agrupaciones');
	}
	public static function paquetes($data) {
		DB::setFetchMode(PDO::FETCH_ASSOC);
	    $query=Planes_de_pago::join('paqueteplandepago','plan_de_pago.id','=','paqueteplandepago.id_plandepago')
						->where('id_plandepago','=',$data['id'])
						->where('periodo','=',$data['periodo'])->first();
		return $query;
	}
}