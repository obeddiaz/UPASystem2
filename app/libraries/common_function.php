<?php

class Common_functions {

    private $sii;
    private $minutesToCache = 20;

    public function __construct() {
        $this->sii = new Sii();
    }

    public function crear_key($datos,$results) {
      $datos=$this->sii->orderParamsToKeyCache($datos);
      $keyToService = md5(json_encode($datos));
      $response['key']=$keyToService;
      if (Cache::has($keyToService)) {
        $response['data'] = Cache::get($keyToService);
      } else {
        Cache::add($keyToService, $results, $this->minutesToCache);
        $response['data'] = $results;
      }
      return $response;
    }

    public function get_by_key($key) {
      if (Cache::has($key)) {
        $response['key']=$key;
        $response['data']=Cache::get($key);
        return $response;
      } else {
        return false;
      }
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

    public function obtener_infoAlumno_idPersona_no_merge($persona) {
      $alumnos = $this->sii->new_request('POST', '/alumnos/all');
      $res = array();
      
      if (is_array($persona)) {
        foreach ($alumnos as $key_alumnos => $alumno) {
          if ($alumno['idpersonas'] == intval($persona['id_persona'])) {
            $res = $alumno;
          }
        }
      } else {
        return null;
      }

      if (empty($res)) {
        $res['id_persona']=$persona['id_persona'];
        $res['persona']="No hay datos sobre este alumno";
        $res['estatus_admin']="INACTIVO";
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

  public function procesar_adeudos_reporte($data) {
    $alumnos = $this->sii->new_request('POST', '/alumnos/all');
    $res = array();
    $personas=array();
    $key_cunt=0;

    foreach ($data as $key_adeudos => $value_adeudos) {
      if (empty($res)) {
        $personas[]=intval($value_adeudos['id_persona']);
        $res[$key_cunt]['id_persona']=$value_adeudos['id_persona'];
        unset($value_adeudos['id_persona']);
        $res[$key_cunt]['adeudos'][]=$value_adeudos;
        $key_cunt++;
      } else {
        if (in_array(intval($value_adeudos['id_persona']),$personas) ) {
          $key_repetido=array_search(intval($value_adeudos['id_persona']),$personas);
          unset($value_adeudos['id_persona']);
          $res[$key_repetido]['adeudos'][]=$value_adeudos;
        } else {
          $personas[]=$value_adeudos['id_persona'];
          $res[$key_cunt]['id_persona']=$value_adeudos['id_persona'];
          unset($value_adeudos['id_persona']);
          $res[$key_cunt]['adeudos'][]=$value_adeudos;
          $key_cunt++;
        }
      }
    }

    foreach ($res as $key_personas => $persona) {
      foreach ($alumnos as $key_alumnos => $alumno) {
        if (intval($alumno['idpersonas']) == intval($persona['id_persona'])) {
          unset($alumno['idpersonas']);
          $res[$key_personas] = array_merge($alumno, $persona);
        }
      }
    }
    foreach ($res as $key => $value) {
      if (!isset($value['matricula'])) {
        unset($res[$key]);
      }
    }

    return $res;

  }
  public function ajustar_adeudos_pagoa_de_mas($adeudo_id,$id_persona,$periodo,$cantidad){
  $adeudos=Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
    ->where("adeudos.id_persona", "=", $id_persona)
    ->where("adeudos.periodo", "=", $periodo)
    ->where("adeudos.id", "<>", $adeudo_id)
    ->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'sc.aplica_beca', 'sc.sub_concepto')
        ->get(); // Se obtienen los adeudos de una persona en el periodo solicitado

  foreach ($adeudos as $key => $adeudo) {
    if ($adeudo->importe>=$cantidad) {
      Adeudos::where('id','=',$adeudo->id)->update(
        array(
            'importe' => round($adeudo->importe-$cantidad,2)
            //'status_adeudo' => 1
        ));
      $cantidad=0;
      break;
    } else {
      Adeudos::where('id','=',$adeudo->id)->update(
        array(
            //'importe' => round(0,2),
            'status_adeudo' => 1
        ));
      $cantidad=$cantidad-$adeudo->importe;
    }
  }
  if ($cantidad>0) {
    Devoluciones::create(array(
        'periodo'=>$periodo,
        'fecha_devolucion' => date('Y-m-d'),
        'importe' => $cantidad,
        'id_persona'=>$id_persona,
        'status_devolucion'=>0
      ));
  }
  }

}

?>