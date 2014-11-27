<?php

class  Becas extends \Eloquent {
	protected $fillable = [
			'abreviatura', 'created_at', 'id', 'importe', 
			'periodicidades_id', 'subcidios_id', 'tipo_importe_id', 
			'tipobeca', 'updated_at'
		];
	protected $table = 'becas';
	public $timestamps = true;

	public static function obtenerTipoImporte($id=null) {
		$Temporaltable=DB::Temporaltable('tipo_importe');
		$query=$Temporaltable->select('*');
		if (isset($id)) {
			$query=$query->where('id',$id);
		}
	    return $query->get();
	}
	public static function obtenerPerodicidades($id=null) {
		$Temporaltable=DB::Temporaltable('periodicidades');
		$query=$Temporaltable->select('*');
		if (isset($id)) {
			$query=$query->where('id',$id);
		}
	    return $query->get();
	}
	public static function obtenerSubcidios($id=null) {
		$Temporaltable=DB::Temporaltable('subcidios');
		$query=$Temporaltable->select('*');
		if (isset($id)) {
			$query=$query->where('id',$id);
		}
	    return $query->get();
	}
	public static function obtenerAlumnosBecas($data) {
		$Temporaltable=DB::Temporaltable('becas_alumno');
		$query=$Temporaltable->select('*')
						->where('idbeca','=',$data['idbeca'])
	    				->where('idnivel','=',$data['idnivel'])
	    				->where('periodo','=',$data['periodo']);
	    return $query->get();
	}
	public static function create_beca_alumno($data) {
		$Temporaltable=DB::Temporaltable('becas_alumno');
        $query=$Temporaltable->insert($data);
        return $query;
    }
    public static function delete_beca_alumno($data) {
    	$Temporaltable=DB::$Temporaltable('becas_alumno');
    	$query=$Temporaltable->where('idbeca','=',$data['idbeca'])
    				 ->where('id_persona','=',$data['id_persona'])
    				 ->where('periodo','=',$data['periodo'])
    				 ->delete();
    	return $query;
    }
    public static function update_status_beca_alumno ($data) {
    	$Temporaltable=DB::$Temporaltable('becas_alumno');
    	$query=$Temporaltable->where('idbeca','=',$data['idbeca'])
    				 ->where('id_persona','=',$data['id_persona'])
    				 ->where('periodo','=',$data['periodo'])
    				 ->update(array('status' => $data['status']));
    	return $query;
    }
/*
	public static function obtenerBecaDetails($id=null) {
		
		$query=Becas::Select('')
			->join('tipo_importe', 'tipo_importe.id', '=', 'becas.tipo_importe_id')
			->join('periodicidades', 'periodicidades.id', '=', 'becas.periodicidades_id' )
			->join('subcidios', 'subcidios.id', '=', 'becas.subcidios_id' );
		if (isset($id)) {
			$query=$query->where('id',$id);
			$res=$query->first();
		} else {
			$res=$query->get();
		}
	    return $res;
	}*/
}

?>