<?php

class Ingresos extends \Eloquent {
	protected $fillable = ['created_at', 
						   'fecha_pago', 
						   'id', 
						   'importe',
						   'tipo_pago', 
						   'updated_at'];
	protected $table = 'ingresos_monetarios';
	public $timestamps = true;
	public static function obtener_ingresos_banco($data) {
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$Temporaltable = DB::table('ingresos_monetarios');
    	$query = $Temporaltable
    		->where('fecha_pago','>=',$data['fecha_desde'])
    		->where('fecha_pago','<=',$data['fecha_hasta'])
    		->where('tipo_pago','=',1)
    		->sum('importe');
    	return $query;
	}
	public static function obtener_ingresos_caja($data) {
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$Temporaltable = DB::table('ingresos_monetarios');
    	$query = $Temporaltable
    		->where('fecha_pago','>=',$data['fecha_desde'])
    		->where('fecha_pago','<=',$data['fecha_hasta'])
    		->where('tipo_pago','=',2)
    		->sum('importe');
    	return $query;
	}
}

?>