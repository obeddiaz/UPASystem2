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
                ->where('idbeca', '=', $data['idbeca'])
                ->where('idnivel', '=', $data['idnivel'])
                ->where('periodo', '=', $data['periodo']);
        return $query->get();
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