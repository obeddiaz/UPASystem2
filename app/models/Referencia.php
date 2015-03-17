<?php

class Referencia extends \Eloquent {
	protected $fillable = ['id','adeudos_id','cuentas_id','referencia'];
	protected $table = 'referencias';
	public $timestamps = true;
	
	public function Adeudos() {
		return $this->belongsTo('Adeudos', 'adeudos_id');
	}

    public static function create_referencia_pagada($data) {
	    $new_insert = DB::table('referencias_pagadas')->insert($data);
	    return $new_insert;
	}

	public static function obtener_ingresos($data) {
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$Temporaltable = DB::table('referencias_pagadas');
        $query = $Temporaltable
        		->where('fecha_de_pago','>=',$data['fecha_desde'])
        		->where('fecha_de_pago','<=',$data['fecha_hasta'])
        		->sum('importe');
        return $query;
	}
	public static function obtener_info_referencias_pagadas($data) {
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$Temporaltable = DB::table('referencias_pagadas');
        $query = $Temporaltable
        		->join('referencias', 'referencias.id', '=', 'id_referencia')
        		->where('fecha_de_pago','>=',$data['fecha_desde'])
        		->where('fecha_de_pago','<=',$data['fecha_hasta'])
        		->select('referencia',
        				 'referencias_pagadas.importe',
        				 'referencias_pagadas.fecha_de_pago');
        return $query->get();
	}
}
