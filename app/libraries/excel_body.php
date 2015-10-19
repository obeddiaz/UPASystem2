<?php

class Excel_body{
	private $config;
	private $meses;
	private $default_columns=array("Alumnos","Importe","Recargos",
                        "Beca","Descuentos","Total");
	public function __construct($config = null) {
		if (!$config) {
            $config = Config::get('excel_styles');
        } 
        $this->meses = $config['meses'];
        unset($config['meses']);
        $this->config = $config;
	}
	public function generar_titulos_array($selected=array()) {
		$data_excel=array();
		$info_titulo=array("Año","Mes","",date('Y',strtotime('now')),$this->meses[date('m', strtotime('now'))-1]);
		foreach ($info_titulo as $key => $titulo) {
			$data_excel[$key]=array();
			for ($i=0; $i <=count($selected) ; $i++) { 
				if ($i==count($selected)) {
					$data_excel[$key][]=$titulo;	
				} else {
					$data_excel[$key][]="";	
				}
			}
		}
		$data_excel[]=array_merge($selected,$this->default_columns);
		return $data_excel;
	}
	public function estilo_celda_general($cells) {
	        $cells->setBackground($this->config['generalSettings']['background']);
	        $cells->setFontColor($this->config['generalSettings']['fontColor']);
	        $cells->setFontFamily($this->config['generalSettings']['fontFamily']);
	        $cells->setFontSize($this->config['generalSettings']['fontSize']);
	        $cells->setBorder($this->config['borderStyleSolid']);
	        $cells->setAlignment($this->config['generalSettings']['Alignament']);  
	        $cells->setValignment($this->config['generalSettings']['VAlignament']);
	        $cells->setFontWeight($this->config['generalSettings']['fontWeight']);

	        return $cells;
	}
	public function estilo_celdas_titulos($sheet,$start_cell,$final_cell) {
		$sheet->cells($start_cell.':'.$final_cell, function($cells) {
			$cells=$this->estilo_celda_general($cells);
      	});
      	return $sheet;
	}
	public function crear_reporte_adeudos_pagos($adeudos,$selected) {
		Excel::create('Reporte '.date('Y-m-d'), function($excel) use($adeudos,$selected) {
            $excel->sheet('Adeudos', function($sheet) use($adeudos,$selected){
	            $sheet->setStyle($this->config['fontDefault']);
	            $sheet=$this->estilo_celdas_titulos($sheet,
	            									$this->config['alphabeth'][count($selected)]."1",
	            									$this->config['alphabeth'][count($selected)]."2");
	            $sheet=$this->estilo_celdas_titulos($sheet,
	            									"A6",
	            									$this->config['alphabeth'][((count($selected)-1)+6)]."6");
    	        $data_excel=$this->generar_titulos_array($selected);
              foreach ($adeudos['periodos'] as $key_p => $periodo) {
                  $c_p=0;                  
                  foreach ($periodo['subconceptos'] as $key_s => $sc) {
                      $c_s=0;
                      $persona_ant="0";
                      foreach ($sc['adeudo_info'] as $key_ai => $value_ai) {
                        if ($c_p==0) {
                        	/// Fila Periodo 
                          $data_excel[]=array(
                              $periodo['periodo'],
                              $sc['sub_concepto'],
                              $value_ai['clave'],
                              $value_ai['matricula'],
                              $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                              $this->meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                              "1",
                              number_format(floatval($value_ai['importe']), 2, '.', ''),
                              number_format(floatval($value_ai['recargo']), 2, '.', ''),
                              number_format(floatval($value_ai['beca']), 2, '.', ''),
                              number_format(floatval($value_ai['descuento']), 2, '.', ''),
                              number_format(floatval($value_ai['total']), 2, '.', '')
                            );
                        } else {
                            if ($c_s==0) {
                            	/// Fila Subconcepto
                                $data_excel[]=array(
                                "",
                                $sc['sub_concepto'],
                                $value_ai['clave'],
                                $value_ai['matricula'],
                                $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                                $this->meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                "1",
                                number_format(floatval($value_ai['importe']), 2, '.', ''),
                                number_format(floatval($value_ai['recargo']), 2, '.', ''),
                                number_format(floatval($value_ai['beca']), 2, '.', ''),
                                number_format(floatval($value_ai['descuento']), 2, '.', ''),
                                number_format(floatval($value_ai['total']), 2, '.', '')
                              );
                            } else {
                              if (isset($value_ai['importe_total'])) {
                              	/// Fila Total
                                $data_excel[]=array(
                                  "",
                                  "",
                                  "Total",
                                  "",
                                  "",
                                  "",
                                  json_encode($value_ai['alumnos_total']),
                                  number_format(floatval($value_ai['importe_total']), 2, '.', ''),
                                  number_format(floatval($value_ai['recargo_total']), 2, '.', ''),
                                  number_format(floatval($value_ai['beca_total']), 2, '.', ''),
                                  number_format(floatval($value_ai['descuento_total']), 2, '.', ''),
                                  number_format(floatval($value_ai['total']), 2, '.', '')
                                );
                              } else {
                                if (intval($persona_ant)==intval($value_ai['clave'])) {
                                	/// Fila adeudo Alumno Repetido
                                  $data_excel[]=array(
                                    "",
                                    "",
                                    "",
                                    "",
                                    "",
                                    $this->meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                    "1",
                                    number_format(floatval($value_ai['importe']), 2, '.', ''),
                                    number_format(floatval($value_ai['recargo']), 2, '.', ''),
                                    number_format(floatval($value_ai['beca']), 2, '.', ''),
                                    number_format(floatval($value_ai['descuento']), 2, '.', ''),
                                    number_format(floatval($value_ai['total']), 2, '.', '')
                                  );
                                } else {
                                	/// Fila adeudo Alumno No Repetido
                                  $data_excel[]=array(
                                    "",
                                    "",
                                    $value_ai['clave'],
                                    $value_ai['matricula'],
                                    $value_ai['nombre'].' '.$value_ai['apellido paterno'].' '.$value_ai['apellido materno'],
                                    $this->meses[date('m', strtotime($value_ai['fecha_limite']))-1],
                                    "1",
                                    number_format(floatval($value_ai['importe']), 2, '.', ''),
                                    number_format(floatval($value_ai['recargo']), 2, '.', ''),
                                    number_format(floatval($value_ai['beca']), 2, '.', ''),
                                    number_format(floatval($value_ai['descuento']), 2, '.', ''),
                                    number_format(floatval($value_ai['total']), 2, '.', '')
                                  );
                                  $persona_ant=$value_ai['clave'];
                                }
                              }
                            }
                        }
                        $c_s++;
                        $c_p++;
                      }
                  }
              }
              $sheet->fromArray($data_excel, null, 'A1', false,false);
            });
          })->download('xls');
	}
}

?>