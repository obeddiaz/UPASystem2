<?php

class Referencias {
    public function generar($carrera, $matricula, $concepto, $monto, $fecha) {
        
        $referencia = sprintf("%02d", $carrera) . $matricula . $concepto;
        $fecha_condenzada = $this->fecha_condensada($fecha);
//        return $carrera;

//    5. De derecha a izquierda se van multiplicando cada uno de los dígitos del Importe por los números 7, 3 y 1, siempre iniciando la secuencia con el número 7, aun cuando el número a multiplicar sea 0 deberá tomarse en cuenta.
//
//4 2 5 6 5 0
//* * * * * *
//1 3 7 1 3 7
//     
//4 6 35 6 15 0
        $paso5 = str_split($monto);
//    $monto_filtrado = "";
        $secuencia = array(7, 3, 1);
        $si = 0;
        $paso6 = 0;
        for ($i = count($paso5); $i >= 0; $i--) {
            if (array_key_exists($i, $paso5))
                if (is_numeric($paso5[$i])) {
                    $paso6 += $paso5[$i] * $secuencia[$si];
//            echo "<br>" .$paso6;
                    $si = ($si == 2) ? 0 : $si + 1;
//            $monto_filtrado = $paso5[$i] . $monto_filtrado;
                }
        }

//    $monto_sdecimales = substr($monto_filtrado,0,-2);
//    $monto_final = sprintf("%04d", $monto_sdecimales);
//    $concepto_final = sprintf("%02d", $concepto);
//    7. El resultado de la suma indicada en el punto 6, se divide entre 10. Y el residuo que se obtenga será el importe condensado.
// 
//Importe Condensado: 6
        $paso7 = $paso6 % 10;
//        echo "<br>importe condensado " . $paso7;
//        8. A la derecha de la Referencia se le agrega la fecha condensada, y a
//        la derecha de estos el importe condensado y la constante 2,  quedando 
//        el siguiente formato:  RRRRRRRRRRRRFFFFI2
//
//067591119700349462
//            $paso8 = sprintf("%s%s%s%s%s2",$matricula,$concepto_final,$monto_final,$fecha_condenzada,$paso7);
        $paso8 = sprintf("%s%04s%s%s", $referencia, $fecha_condenzada, $paso7, "2");

//            9. A la Referencia resultantes del punto 8, se les multiplica por 
//            los ponderadores 11, 13, 17, 19 y 23, de derecha a izquierda y 
//            siempre iniciando la secuencia con el número 11, aun cuando el 
//            número a multiplicar sea 0 deberá tomarse en cuenta.
// 
//0 6 7 5 9 1 1 1 9 7 0 0 3 4 9 4 6 2
//* * * * * * * * * * * * * * * * * *
//17 13 11 23 19 17 13 11 23 19 17 13 11 23 19 17 13 11
//0 78 77 115 171 17 13 11 207 133 0 0 33 92 171 68 78 22

        $paso9 = str_split($paso8);
//            $paso9 = array(0, 6, 7, 5, 9, 1, 1, 1, 9, 7, 0, 0, 3, 4, 9, 4, 6, 2);
        $secuencia = array(11, 13, 17, 19, 23);
        $si = 0;
        $paso10 = 0;
        for ($i = count($paso9); $i >= 0; $i--) {
            if (array_key_exists($i, $paso9))
                if (is_numeric($paso9[$i])) {
                    $paso10 += $paso9[$i] * $secuencia[$si];
//            echo "<br >-" . $paso9[$i] * $secuencia[$si];
                    $si = ($si == 4) ? 0 : $si + 1;
                }
        }

//    10. Se suman todos los resultados de las multiplicaciones del punto 9.
//
//0 + 78 + 77 + 115 + 171 + 17 + 13 + 11 + 207 + 133 + 0 + 0 + 33 + 92 + 171 + 68 + 78 + 22 = 1286
//    11. El resultado de la suma indicada en el punto 10, se divide entre 97 y 
//    al residuo se le suma 1.

        $paso11 = ($paso10 % (97)) + 1;
        //echo $paso11."<br>";
//    echo "<br>" . $paso10;
//    El dígito verificador estará formado por los cuatro dígitos de la fecha 
//    condensada, el dígito del importe condensado, la constante 2 y los dos 
//    dígitos del punto 11
//
//Dígito Verificador: 34946226

        $verificador = sprintf("%04s%s2%02s", $fecha_condenzada, $paso7, $paso11);

//    12. A la referencia se le agregara el dígito verificador y esa será la 
//    línea de captura que recibirá el cajero en ventanilla.

        $paso12 = sprintf("%s%s", $referencia, $verificador);
//    echo "<br>2 " . $paso12;

        return $paso12;
    }

    public function fecha_condensada($fecha) {

        $fecha = explode("-", $fecha);
        $date["dia"] = $fecha[0];
        $date["mes"] = $fecha[1];
        $date["ano"] = $fecha[2];

//    1. Al año se le resta el número 1988 y se multiplica por el número 372
//( 1997 - 1988 ) * 372 = 3348

        $paso1 = ($date["ano"] - 1988) * 372;

//    2. Al mes se le resta la unidad y se multiplica por 31
//( 5 - 1 ) * 31 = 124
//3. Al día se le resta la unidad
//
//23 - 1 = 22

        $paso2 = ($date["mes"] - 1) * 31;

//    3. Al día se le resta la unidad 
//
//23 - 1 = 22

        $paso3 = ($date["dia"] - 1);

//    4. Se suman los resultados del punto 1,2 y 3 y el resultado es la fecha Condensada. Si la longitud de la fecha condensada es menor a 4 posiciones se agregaran ceros a la izquierda hasta acompletarla.
//
//3348 + 124 + 22 = 3494
//Fecha Condensada: 3494
//    echo "<br />Fecha condensada ". sprintf("%04d", ($paso1+$paso2+$paso3));

        return sprintf("%04d", ($paso1 + $paso2 + $paso3));
    }

}