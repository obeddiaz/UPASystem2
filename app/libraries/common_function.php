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
        $alumnos = $this->sii->new_request('POST', '/alumnos');
        $res = array();

        if (is_array($personas)) {
            foreach ($personas as $key_personas => $persona) {
                foreach ($alumnos as $key_alumnos => $alumno) {
                    if ($alumno['id'] == intval($persona['id_persona'])) {
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

}

?>