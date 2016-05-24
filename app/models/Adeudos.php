<?php

class Adeudos extends \Eloquent {

    protected $fillable = [
                            'id',   'sub_concepto_id',  'id_persona',   'importe',  'fecha_limite', 'periodo',
                            'grado',    'paquete_id',   'status_adeudo',    'recargo',  'tipo_recargo', 
                            'subconcepto_paquete_id',   'digito_referencia',    'descripcion_sc',
                            'recargo_acumulado',    'aplica_beca',  'aplica_recargo',   'es_inscripcion',   
                            'es_recursamiento', 'materia_recursamiento',    'tipo_adeudo'   ,   'mes',  'matricula' ];

    protected $table = 'adeudos';
    protected $table_tipoadeudos = 'adeudo_tipopago';
    protected $table_subcoceptos = 'sub_conceptos';
    protected $table_tipopago = 'tipo_pago';
    protected $table_beca = 'beca';
    protected $table_paquete = 'paqueteplandepago';
    protected $table_referencias = 'referecias';
    
    public $timestamps = true;

    public static function agregar_adeudos($alumno, $subconceptos, $paquete) {
        //  Se obtiene los datos de el alumno desde el api por medio del id_persona 
        //  ---->
        $commond = new Common_functions();
        $persona = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $alumno));
        if (isset($persona[0])) {
            if (isset($persona[0]['grado'])) {
                $grado = $persona[0]['grado'];
            } else {
                $grado = null;
            }
            $matricula = $persona[0]['matricula'];
        } else {
            $grado = null;
            $matricula = null;
        }
        // <----
        //  Dependiendo de los subconceptos que se reciban son los adeudos que
        //  se generaran, se construye el array para la tabla
        //  ---->
        foreach ($subconceptos as $subconcepto) {
          $adeudo = array(
              "sub_concepto_id" => $subconcepto->id,
              "id_persona" => $alumno,
              "importe" => $subconcepto->importe,
              "fecha_limite" => $subconcepto->fecha_de_vencimiento,
              "periodo" => $paquete->periodo,
              "paquete_id" => $paquete->id,
              "recargo" => $subconcepto->recargo,
              "tipo_recargo" => $subconcepto->tipo_recargo,
              "subconcepto_paquete_id" => $subconcepto->idsub_paqueteplan,
              "digito_referencia" => $subconcepto->digito_referencia,
              "grado" => $grado,
              "status_adeudo" => 0,
              "descripcion_sc" => $subconcepto->descripcion_sc,
              "recargo_acumulado" => $subconcepto->recargo_acumulado,
              "aplica_beca" => $subconcepto->aplica_beca,
              "es_inscripcion" => $subconcepto->es_inscripcion,
              'tipo_adeudo' => $subconcepto->tipo_adeudo,
              'mes' => $subconcepto->mes,
              'matricula' => $matricula
          );

          $adeudo = Adeudos::create($adeudo); //  Adeudo_id

          //  Se gnera el registro de los tipos de pago que tendra el adeudo
          //  caja ó por medio del banco, se reciben en json y se descodifican
          //  ---->
          foreach (json_decode($subconcepto->tipos_pago) as $key => $value) {
              $adeudo_tipopago['adeudos_id'] = $adeudo['id'];
              $adeudo_tipopago['tipo_pago_id'] = $value;
              Adeudos_tipopago::create($adeudo_tipopago);
          }
            // <----
      }
        // <----
    }

    public static function obtener_adeudos_alumno($parametros) {
      $commond = new Common_functions();
      $adeudos   =    array();
      $now = strtotime('now'); // Se obtiene la fecha actual

      if (isset($parametros['id'])) {
          $adeudos =  Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
              ->orderBy('es_inscripcion', 'desc')
              ->orderBy('fecha_limite', 'asc')
              ->where("adeudos.id", "=", $parametros['id'])
              ->select( 'adeudos.*', 
                        DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 
                        'sc.aplica_beca',
                        'sc.sub_concepto')
              ->get()->toArray(); // Se obtienen los adeudos de una persona en el periodo solicitado

      }   else {
          $adeudos = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
              ->orderBy('es_inscripcion', 'desc')
              ->orderBy('fecha_limite', 'asc')
              ->where("adeudos.id_persona", "=", $parametros['id_persona'])
              ->where("adeudos.periodo", "=", $parametros['periodo'])
              ->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'sc.aplica_beca', 'sc.sub_concepto')
              ->get()->toArray(); // Se obtienen los adeudos de una persona en el periodo solicitado
      }
        
      $beca =   Becas::AlumnoBeca_Persona_Periodo($parametros); // Consulta beca
      $diaActual =   date('d', $now); // Dia actual
      $lock   =   false;    // blockea impresion de referencia de pago si no se pago a tiempo la inscripción
      $contadores =   array();    //  array para contadores por subconcepto
      $meses_retraso = 0;
      foreach ($adeudos as $key_adeudo => $adeudo) {
        $fecha_limite = strtotime($adeudo['fecha_limite']);
        if (isset($contadores[$adeudo['sub_concepto_id']])) {   // Busca si existe ls cantidad para el contador 
            $contadores[$adeudo['sub_concepto_id']] +=  1;  //  Aumenta Contador del sub_concepto
        }   else{
            $contadores[$adeudo['sub_concepto_id']] =   0;  //  Inicializa el contador del sub_concepto
        }
        
        $diaFechaLimite = date('d', $fecha_limite);     //  Se obtiene el No. de día de la fecha limite
        if ($diaActual > $diaFechaLimite) {     //  Se compara si el dia ya paso para agregarle un mes de reacargo
            $adeudos[$key_adeudo]['meses_retraso'] = $adeudo['meses_retraso'] + 1;
        }

        $adeudos[$key_adeudo]['contador']   =   $contadores[$adeudo['sub_concepto_id']];
        $adeudos[$key_adeudo]['tipos_pago'] =   Adeudos_tipopago::where('adeudos_id', '=', $adeudo['id'])
                                                                ->get()
                                                                ->toArray();    // Tipos de pago
        $adeudos[$key_adeudo]['importe_inicial']    =   $adeudo['importe'];

        $adeudos[$key_adeudo]['descuetos']  =   Descuentos::where('adeudos_id','=',$adeudo['id'])->get()->toArray();
        $adeudos[$key_adeudo]['descueto']   =   $commond->getDescuento($adeudo['id']);
        $adeudos[$key_adeudo]['descueto_recargo']   =   $commond->getDescuento($adeudo['id'],2);
        $adeudos[$key_adeudo]['prorrogas'] = Prorrogas::where('adeudos_id','=',$adeudo['id'])->get()->toArray();
        $adeudos[$key_adeudo]['pagos']  =   Pagos::leftJoin('referencias','pagos.referencia_id','=','referencias.id','left')
                                                    ->where('pagos.adeudos_id','=',$adeudo['id'])
                                                    ->select('pagos.*','referencias.referencia')
                                                    ->get()
                                                    ->toArray();

        $adeudos[$key_adeudo]['pago']   =   $commond->getPago($adeudo['id']);
        $adeudos[$key_adeudo]['pago_recargo']   =   $commond->getPago($adeudo['id'],2);
        $adeudos[$key_adeudo]['pago_beca']   =   $commond->getPago($adeudo['id'],3);
        $adeudos[$key_adeudo]['pago_descuento']   =   $commond->getPago($adeudo['id'],4);
        $adeudos[$key_adeudo]['pago_descuento_recargo']   =   $commond->getPago($adeudo['id'],4);

        if ($adeudos[$key_adeudo]['status_adeudo']  ==  0) {
          if ($adeudos[$key_adeudo]['meses_retraso'] == 0) {      // Si no tiene meses de  retraso no tendra recargo
            $adeudos[$key_adeudo]['recargo_total'] = 0;
            
            $adeudos[$key_adeudo]['importe']-= $commond->calcular_importe_por_tipo(    $adeudo['importe'], $beca['importe'], $beca['tipo_importe_id']);
            $adeudos[$key_adeudo]['importe']-= $adeudos[$key_adeudo]['descueto'];

            $adeudos[$key_adeudo]['beca'] = $commond->calcular_importe_por_tipo(  $adeudo['importe'], 
                                                                                  $beca['importe'], 
                                                                                  $beca['tipo_importe_id']);
            $adeudos[$key_adeudo]['recargo_no_descuento'] = 0;
            $adeudos[$key_adeudo]['recargo_total'] = 0;
          }   else   {
            if ($adeudos[$key_adeudo]['es_inscripcion'] == 1) {
                $lock   =   TRUE;
            }
            
            if ($beca) {
                $databeca = array(
                    "cancelada_motivo" => "Pago retrasado",
                    "cancelada_fecha" => date("Y-m-d"),
                    "cancelada_por" => "Sistema",
                    "id_persona" => $parametros['id_persona'],
                    "periodo" => $parametros['periodo'],
                    "status" => 0
                );
                Becas::update_status_beca_alumno($databeca);
                $beca = FALSE;
            }

            if ($adeudo['aplica_recargo'] == 1 && $adeudos[$key_adeudo]['meses_retraso'] > 0) {
                $recargo = $commond->calcular_importe_por_tipo( $adeudo['importe'], 
                                                                $adeudo['recargo'], 
                                                                $adeudo['tipo_recargo']); 
                if ($adeudo['recargo_acumulado'] == 1) {
                    $recargo *= $adeudos[$key_adeudo]['meses_retraso'];
                }
            } else {
                $recargo = 0;
            }
            
            $adeudos[$key_adeudo]['recargo_no_descuento'] = $recargo;

            $adeudos[$key_adeudo]['recargo_total'] = $recargo - $adeudos[$key_adeudo]['descueto_recargo'] - $adeudos[$key_adeudo]['pago_recargo'];

            $adeudos[$key_adeudo]['importe'] += (($adeudos[$key_adeudo]['recargo_total'] - $adeudos[$key_adeudo]['descueto']) - $adeudos[$key_adeudo]['pago']);
          }
        }   else {
          $adeudos[$key_adeudo]['recargo_total'] = $adeudos[$key_adeudo]['pago_recargo'];
          $adeudos[$key_adeudo]['beca'] = $adeudos[$key_adeudo]['pago_beca'];
          $adeudos[$key_adeudo]['descueto'] = $adeudos[$key_adeudo]['pago_descuento'];
          $adeudos[$key_adeudo]['descueto_recargo'] = $adeudos[$key_adeudo]['pago_descuento_recargo'];
          $adeudos[$key_adeudo]['importe'] = $adeudos[$key_adeudo]['pago'];
        }
        if ($lock==TRUE) {
            $adeudos[$key_adeudo]['lock'] = 1;   
        } else {
            $adeudos[$key_adeudo]['lock'] = 0;   
        }
      }



      return $adeudos;
    }

}