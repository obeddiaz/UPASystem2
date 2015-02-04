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

    public function obtener_alumno_idPersona($personas) {
        var_dump($personas);die();
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

}

?>