<?php

class Common_functions {

    private $sii;

    public function __construct() {
        $this->sii = new Sii();
    }

    public function periodo_actual() {
        $periodos = $this->sii->new_request('POST', '/periodos');
        $res = array();
        foreach ($periodos as $key => $value) {
            if ($value['actual'] == '1') {
                $res = $value;
            }
        }
        return $res;
    }

    public function obtener_periodo_id($periodos) {
        $todos = $this->sii->new_request('POST', '/periodos');
        $res = array();
        if (is_array($periodos)) {
            foreach ($periodos as $key_periodos => $periodo) {
                foreach ($todos as $key_todos => $uno) {
                    if (intval($periodo['periodo']) == intval($uno['idperiodo'])) {
                        $periodo['periodo']=$uno['idperiodo'];
                        $periodo['periodo_descripcion']=$uno['periodo'];
                        unset($uno['periodo']);
                        unset($uno['idperiodo']);
                        $res[] = array_merge($periodo, $uno);
                    }
                }
            }
        } else {
            return null;
        }
        return $res;
    }

    public function obtener_alumno_idPersona($personas) {
        $alumnos = $this->sii->new_request('POST', '/alumnos/all');
        $res = array();
        if (is_array($personas)) {
            foreach ($personas as $key_personas => $persona) {
                foreach ($alumnos as $key_alumnos => $alumno) {
                    if (intval($alumno['idpersonas']) == intval($persona['id_persona'])) {
                        $persona_info = $persona;

                        unset($persona_info['id_persona']);
                        $res[] = array_merge($alumno, $persona_info);
                    }
                }
            }
        } else {
            return null;
        }
        return $res;
    }

    public function obtener_alumno_No_idPersona($personas) {
        $alumnos = $this->sii->new_request('POST', '/alumnos/all');
        $res = array();
        if (is_array($personas)) {
            foreach ($personas as $persona) {
                $per_temp[] = $persona['id_persona'];
            }
            if (isset($per_temp)) {
                foreach ($alumnos as $alumno) {
                    if (!in_array($alumno['idpersonas'], $per_temp)) {
                        $res[] = $alumno;
                    }
                }
            } else {
                $res = $alumnos;
            }
        } else {
            return null;
        }
        return $res;
    }

    public function obtener_infoAlumno_idPersona($persona) {
      $alumnos = $this->sii->new_request('POST', '/alumnos/all');
      $res = array();
      if (is_array($persona)) {
        foreach ($alumnos as $key_alumnos => $alumno) {
          if ($alumno['idpersonas'] == intval($persona['id_persona'])) {
            $persona_info = $persona;
            unset($persona_info['id_persona']);
            $res[] = array_merge($alumno, $persona_info);
          }
        }
      } else {
        return null;
      }
      return $res;
    }

  public function calcular_importe_por_tipo($importe, $rob, $tipo) {
    $res = null;
    if ($tipo == 1) {
      $res = $rob / 100;
      $res = $importe * $res;
    } elseif ($tipo == 2) {
      return $rob;
    }
   return $res;
  }
  public function actualiza_status_adeudos($id_persona,$periodo) {
    $adeudos = 
      Adeudos::join('sub_conceptos as sc', 
                    'sc.id', '=', 
                    'adeudos.sub_concepto_id')
              ->where("adeudos.id_persona", "=", $id_persona)
              ->where("adeudos.periodo", "=", $periodo)
              ->select('adeudos.*', 
                        DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 
                        'sc.aplica_beca', 
                        'sc.sub_concepto')
              ->get(); // Se obtienen los adeudos de una persona en el periodo solicitado
    $beca = 
      Becas::AlumnoBeca_Persona_Periodo(
            array('id_persona' => $id_persona,
                  'periodo'=>$periodo)); // Consulta beca
      $retrasos=false;
    if ($beca) {
      if (intval($beca['importe'])==100 && intval($beca['tipo_importe_id'])==1) {
        foreach ($adeudos as $key => $adeudo) {
          if ($adeudo['aplica_beca'] == 1 && $retrasos==false) {
            if ($adeudo['fecha_limite']>= date('Y-m-d')) {
              Adeudos::where('id', '=', $adeudos['id'])
              ->update(array('status_adeudo' =>1));

            } else {
              $retrasos=true;
            }
          }
        }
      }
    }
  }
}

?>