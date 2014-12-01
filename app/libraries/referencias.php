<?php

class Referencias {
    public function ReducirLonguitud($a,$b) { 
        $d=0;
        for ($e = 0; $e < count($a); $e++) {
            $d+=intval($a[$e] * $b[$e%count($b)]);
        }
        return $d;
    }
    public function Generar($referencia,$importe,$fecha) {
        $digitos_importe=$this->GenerarDigitosImporte($importe);
        $fecha_condensada=$this->FechaCondensada($fecha);
        $referencia_reducida=$this->GenerarDigitosCondensadosReferencia($referencia,$fecha_condensada,$digitos_importe);
        return $referencia.$fecha_condensada.$digitos_importe.$referencia_reducida;
    }
    public function FechaCondensada($fecha) {
  //      var_dump(intval(date('j', strtotime($fecha)));die();
        $dia = date('j',strtotime($fecha))-1;
        $mes = (date('n', strtotime($fecha))-1)*31;
        $año = (date('Y', strtotime($fecha)) - 2014) * 372;
        $fecha_condensada=strval($dia+$mes+$año);
        while (strlen($fecha_condensada)<4) {
            $fecha_condensada="0".$fecha_condensada;
        }
        return $fecha_condensada;
    }
    public function GenerarDigitosImporte($importe) {
        $importe=$importe*100;
        $importe = str_split($importe);
        $importe=array_reverse($importe);
        $digitos_importe=($this->ReducirLonguitud($importe, array(7, 3, 1))%10)."2";
        return $digitos_importe;
    }
    public function GenerarDigitosCondensadosReferencia ($referencia,$fecha_condensada,$digitos_importe) { 
        $data_array=array_reverse(str_split($referencia));
        $data_array=array_merge(array_reverse(str_split($fecha_condensada)),$data_array);
        $data_array=array_merge(array_reverse(str_split($digitos_importe)),$data_array);
        $referencia_reducida=$this->ReducirLonguitud($data_array, array(11, 13, 17, 19, 23));
        return ($referencia_reducida%97)+1;
    }
}