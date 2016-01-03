<?php

class Becas extends \Eloquent {

    protected $fillable = [
        'abreviatura', 'created_at', 'id', 'importe',
        #'periodicidades_id', 
        'subcidios_id', 'tipo_importe_id',
        'tipobeca', 'updated_at', 'descripcion'
    ];
    protected $table = 'becas';
    public $timestamps = true;

    public static function obtenerTipoImporte($id = null) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('tipo_importe');
        $query = $Temporaltable->select('*');
        if (isset($id)) {
            $query = $query->where('id', $id);
        }
        return $query->get();
    }

    public static function obtenerSubcidios($id = null) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('subcidios');
        $query = $Temporaltable->select('*');
        if (isset($id)) {
            $query = $query->where('id', $id);
        }
        return $query->get();
    }

    public static function obtenerAlumnosBecas($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('becas_alumno');
        $query = $Temporaltable->select('id_persona', 'status')
                ->where('periodo', '=', $data['periodo']);
            if (isset($data['idbeca'])) {
                $query=$query->where('idbeca', '=', $data['idbeca']);
            }
            if (isset($data['idnivel'])) {
                $query=$query->where('idnivel', '=', $data['idnivel']);
            }
        return $query->get();
    }

    public static function obtenerAlumnosBecasCompleto($data) {
        #DB::setFetchMode(PDO::FETCH_ASSOC);
        #$Temporaltable = DB::table('becas_alumno');
        $query = Becas::Select('becas_alumno.*',
                                        'becas.abreviatura',
                                        'becas.descripcion',
                                        'becas.importe',
                                        'tipo_importe.nombre as tipo')
                                ->join('becas_alumno', 'becas_alumno.idbeca', '=', 'becas.id')
                                ->join('tipo_importe','tipo_importe.id','=','becas.tipo_importe_id')
                                ->where('periodo', '=', $data['periodo']);
                
            if (isset($data['idbeca'])) {
                $query=$query->where('idbeca', '=', $data['idbeca']);
            }
            if (isset($data['idnivel'])) {
                $query=$query->where('idnivel', '=', $data['idnivel']);
            }
            if (isset($data['status'])) {
                $query=$query->where('becas_alumno.status', '=', $data['status']);
            }
        return $query->get()->toArray();
    }

    public static function create_beca_alumno($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('becas_alumno');
        $query = $Temporaltable->insert($data);
        return $query;
    }

    public static function delete_beca_alumno($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('becas_alumno');
        $query = $Temporaltable->where('idbeca', '=', $data['idbeca'])
                ->where('id_persona', '=', $data['id_persona'])
                ->where('periodo', '=', $data['periodo'])
                ->delete();
        return $query;
    }

    public static function update_status_beca_alumno($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('becas_alumno');
        $query = $Temporaltable
                ->where('id_persona', '=', $data['id_persona'])
                ->where('periodo', '=', $data['periodo'])
                ->update(array('status' => $data['status'], 'cancelada_por' => $data['cancelada_por'], 
                                'cancelada_fecha'=> $data['cancelada_fecha'], 
                                'cancelada_motivo'=> $data['cancelada_motivo']));
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
      } */

    public static function AlumnoBeca_Persona_Periodo($data) {
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $Temporaltable = DB::table('becas_alumno as ba');
        $query = $Temporaltable->select('ba.periodo', 'ba.status', 'b.descripcion', 'b.abreviatura', 'b.importe', 'b.tipo_importe_id')
                ->join('becas as b', 'b.id', '=', 'ba.idbeca')
                ->where('id_persona', '=', $data['id_persona'])
                ->where('status', '=', 1)
                ->where('periodo', '=', $data['periodo']);
        $beca = $query->first();
        if ($beca) {
            return $beca;
        } else {
            return FALSE;
        }
    }

}

?>