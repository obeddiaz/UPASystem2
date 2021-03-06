<?php

class Common_functions {
		private $config_app;
		private $sii;
		private $minutesToCache = 20;

		public function __construct() {
			$this->sii          =   new Sii();
			$this->config_app   =   Config::get('app');
		}

		public function validarPago($adeudo, $pago) {
			$registro_info["beca"]          =   ( $adeudo['beca'] ==  "N/A" ? 0 : $adeudo['beca'] );
			$registro_info["forma_pago"]    =   ( $pago["forma_de_pago"] && array_key_exists($pago['forma_de_pago'],  $this->config_app['formas_de_pago']) ? $this->config_app['formas_de_pago'][$pago['forma_de_pago']] : 1 );
			$registro_info["lugar"]         =   ( $pago["estado"] && array_key_exists($pago['estado'],$this->config_app['estados']) ? $this->config_app['estados'][$pago['estado']] : "Sin lugar de pago" );
			$registro_info["referencia_id"] =   ( isset($pago['referencia_id']) ? $pago['referencia_id']: null );

			if (  $pago['importe']  >=  $adeudo['importe']  ) {
				if ( $pago['importe'] >  $adeudo['importe'] )  {
					// Codigo para Crear Devolucion.
					Devoluciones::create(array(
						'periodo'             => $adeudo['periodo'],
						'fecha_devolucion'    => date('Y-m-d'),
						'importe'             => $pago['importe'] - $adeudo['importe'],
						'id_persona'          => $adeudo['id_persona'],
						'status_devolucion'   => 0
					));   
				}
				Adeudos::where('id', '=', $adeudo['id'])->update( array(  'status_adeudo' => 1  )  );
				$montoRecargo   =   $adeudo['recargo_no_descuento'];
				$montoImporte   =   $adeudo['importe_no_descuento'];
				$montoTotal     =   $adeudo['importe'];
			} else {
				$diffRecargo    =   $pago['importe'] - $adeudo['recargo_total'];
				$montoRecargo   =   ($adeudo['recargo_total'] == 0  ? 0 : ($diffRecargo < 0 ? $pago['importe']  : $adeudo['recargo_total'])  );
				$montoImporte   =   ( $diffRecargo  < 0 ? 0 : $diffRecargo );
				$montoTotal     =   $montoImporte + $montoRecargo;
			}

			$array_pago = array(  'tipo_pago'           =>  $pago['tipo_pago'],
														'fecha_pago'          =>  $pago['fecha_de_pago'],
														'forma_pago'          =>  $registro_info['forma_pago'],
														'lugar'               =>  $registro_info['lugar'],
														'beca'                =>  $registro_info['beca'],
														'referencia_id'       =>  $registro_info["referencia_id"],
														'cuenta_id'           =>  $adeudo['cuenta_id'],
														'adeudos_id'          =>  $adeudo['id'],
														'descuento'           =>  $adeudo['descuento'],
														'descuento_recargo'   =>  $adeudo['descuento_recargo'],
														'importe'             =>  $montoImporte,
														'importe_recargo'     =>  $montoRecargo,
														'total'               =>  $montoTotal );
			
			Pagos::create($array_pago);

			if ( $registro_info['referencia_id'] != null && $pago['tipo_pago']  ==  1 ) {  
				// Si el pago es por medio del Banco Guarda la Referencia.
				Referencia::create_referencia_pagada( array(  'id_referencia' => $registro_info['referencia_id'],
																											'fecha_de_pago' => $pago['fecha_de_pago'],
																											'importe' => $pago['importe'],
																											'estado' => $pago['estado'] ) );
			}

			$res = $this->obtener_infoAlumno_idPersona(array('id_persona' => $adeudo['id_persona']));

			return $res;
		}

		public function getPago($adeudo_id, $tipo = 1 ) {
				$pagos = Pagos::where('adeudos_id','=',$adeudo_id)
								->get()->toArray();

				if ( count($pagos) == 0 ) {
						return 0;
				}

				foreach ($pagos as $key_pago => $pago) {
						switch ($tipo) {
								case 1:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['importe'];
										}   else {
												$respuesta  =   $pago['importe'];
										}
										break;
								case 2:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['importe_recargo'];
										}   else {
												$respuesta  =   $pago['importe_recargo'];
										}
										break;
								case 3:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['beca'];
										}   else {
												$respuesta  =   $pago['beca'];
										}
										break;
								case 4:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['descuento'];
										}   else {
												$respuesta  =   $pago['descuento'];
										}
										break;
								case 4:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['descuento_recargo'];
										}   else {
												$respuesta  =   $pago['descuento_recargo'];
										}
										break;
								case 5:
										if (isset($respuesta)) {
												$respuesta  +=   $pago['total'];
										}   else {
												$respuesta  =   $pago['total'];
										}
										break;
								default:
										# code...
										break;
						}
				}

				return $respuesta;
		}

		public function getDescuento($adeudo_id, $tipo   =   1)  {
				$adeudo = Adeudos::where('id','=',$adeudo_id)->first();
				$descuentos    =    Descuentos::where('adeudos_id','=',$adeudo_id)
																		->get()->toArray();

				if ( count($descuentos) == 0 ) {
						return 0;
				}

				foreach ($descuentos as $key_descuento => $descuento) {
						switch ($tipo) {
								case 1:
										if (isset($respuesta)) {
												$respuesta  +=  $this->calcular_importe_por_tipo(   $adeudo['importe'],
																																						$descuento['importe'],
																																						$descuento['tipo_importe_id']);
										}   else {
												$respuesta  =   $this->calcular_importe_por_tipo(   $adeudo['importe'],
																																						$descuento['importe'],
																																						$descuento['tipo_importe_id']);
										}
										break;
								case 2:
										if (isset($respuesta)) {
												$respuesta  +=  $this->calcular_importe_por_tipo(   $adeudo['importe_recargo'],
																																						$descuento['importe_recargo'],
																																						$descuento['tipo_importe_id']);
										}   else {
												$respuesta  =   $this->calcular_importe_por_tipo(   $adeudo['importe_recargo'],
																																						$descuento['importe_recargo'],
																																						$descuento['tipo_importe_id']);
										}
										break;
								default:
										# code...
										break;
						}
				}
				if ($tipo == 3) {
						return trim($respuesta, '-');
				}
				return $respuesta;
		}

		public function getInfoiIDPersona($id_persona){
				$todos = $this->sii->new_request('POST', '/alumnos/all');

				foreach ($todos as $key_todos => $todos_row) {
						if (intval($todos_row['idpersonas']) == intval($id_persona)) {
								return $todos_row;
								break;
						}
				}

				return array();
		}

		public function validarInfoCrearAdeudo($alumno, $periodo,   $paquete) {
				$respuesta  =   array();
				$periodo_actual = $this->periodo_actual();
				
				$adeudos_no_pagados = Adeudos::where('id_persona', '=', $alumno)
																->where('periodo', '<', intval($periodo))
																->where('status_adeudo', '=', 0)->count();

				$asignados_paquete = Adeudos::where('paquete_id', '=',$paquete['id'])
																				->where('id_persona','=',$alumno)
																				->where('periodo','=',$periodo)
																				->count();

				//if ($persona['estatus_admin']!='EGRESADO') {
				if ($asignados_paquete > 0) {
						$respuesta['status']   =   false;
						$respuesta['motivo']    =   'Ya ha sido asignado el paquete a el alumno en el periodo seleccionado';         

						return $respuesta;
				} else {
				//  if ($adeudos_no_pagados == 0) {
						
				/*  } else {
						$persona['motivo_no_asignacion'] = 'Tiene adeudos pendientes';
						$personas_ids['no_asignados'][] = $persona;
						$count_no_asigned++;
					} */
				}
		/*  } else {
					$persona['motivo_no_asignacion'] = 'No esta ACTIVO';
					$personas_ids['no_asignados'][] = $persona;
					$count_no_asigned++;
			}*/
				$respuesta['status']    =   true;
				return $respuesta;
		}

		public function crear_key($datos, $results) {
				$datos = $this->sii->orderParamsToKeyCache($datos);
				$keyToService = md5(json_encode($datos));
				$response['key'] = $keyToService;
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
						$response['key'] = $key;
						$response['data'] = Cache::get($key);
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
				if (is_array($periodos) ) {
						foreach ($periodos as $key_periodos => $periodo) {
								foreach ($todos as $key_todos => $uno) {
										if (isset($periodo['periodo'])) {
												if (intval($periodo['periodo']) == intval($uno['idperiodo'])) {
														$periodo['periodo'] = $uno['idperiodo'];
														$periodo['periodo_descripcion'] = $uno['periodo'];
														unset($uno['periodo']);
														unset($uno['idperiodo']);
														$res[] = array_merge($periodo, $uno);
												}
										} else {
												$res[]=$periodo;
										}
								}
						}
				} else {
						return null;
				}
				return $res;
		}

		public function obtener_alumno_matricula($personas) {
				$alumnos = $this->sii->new_request('POST', '/alumnos/all/idpersona');
				$res = array();
				if (is_array($personas)) {
						foreach ($personas as $key_personas => $persona) {
								foreach ($alumnos as $key_alumnos => $alumno) {
										if ($alumno['matricula'] == $persona['matricula']) {
												$persona_info = $persona;
												if (isset($persona_info['matricula'])) {
														unset($persona_info['matricula']);
												}
												if (!isset($persona_info['nivel'])) {
														$persona_info['idnivel']=$alumno['nivel'];
												}
												$persona_info['id_persona']=$alumno['idpersonas'];
												$res[] = $persona_info;
												break;
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
												break;
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
				$per_temp=array();
				if (is_array($personas)) {
						foreach ($personas as $persona) {
								if (isset($persona['id_persona'])) {
										$per_temp[] = $persona['id_persona'];
								} else {
										if (isset($persona['idpersonas'])) {
												$per_temp[] = $persona['idpersonas'];
										}
								}
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
						$res['id_persona'] = $persona['id_persona'];
						$res['persona'] = "No hay datos sobre este alumno";
						$res['estatus_admin'] = "INACTIVO";
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

		public function actualiza_status_adeudos($id_persona, $periodo) {
				$adeudos = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
								->where("adeudos.id_persona", "=", $id_persona)
								->where("adeudos.periodo", "=", $periodo)
								->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'sc.aplica_beca', 'sc.sub_concepto')
								->get(); // Se obtienen los adeudos de una persona en el periodo solicitado
				$beca = Becas::AlumnoBeca_Persona_Periodo(
												array('id_persona' => $id_persona,
														'periodo' => $periodo)); // Consulta beca
				$retrasos = false;
				if ($beca) {
						if (intval($beca['importe']) == 100 && intval($beca['tipo_importe_id']) == 1) {
								foreach ($adeudos as $key => $adeudo) {
										if ($adeudo['aplica_beca'] == 1 && $retrasos == false) {
												if ($adeudo['fecha_limite'] >= date('Y-m-d')) {
														Adeudos::where('id', '=', $adeudos['id'])
																		->update(array('status_adeudo' => 1));
												} else {
														$retrasos = true;
												}
										}
								}
						}
				}
		}

		public function procesar_adeudos_reporte($data) {
				$alumnos = $this->sii->new_request('POST', '/alumnos/all');
				$res = array();
				$personas = array();
				$key_cunt = 0;
				$filtros["carreras"] = [];
				$filtros["sub_conceptos"] = [];
				$filtros["descripcion_sc"] = [];
				foreach ($data as $key_adeudos => $value_adeudos) {
						if (empty($res)) {
								$personas[] = intval($value_adeudos['id_persona']);
								$res[$key_cunt]['id_persona'] = $value_adeudos['id_persona'];
								unset($value_adeudos['id_persona']);
								$res[$key_cunt]['adeudos'][] = $value_adeudos;
								$key_cunt++;
						} else {
								if (in_array(intval($value_adeudos['id_persona']), $personas)) {
										$key_repetido = array_search(intval($value_adeudos['id_persona']), $personas);
										unset($value_adeudos['id_persona']);
										$res[$key_repetido]['adeudos'][] = $value_adeudos;
								} else {
										$personas[] = $value_adeudos['id_persona'];
										$res[$key_cunt]['id_persona'] = $value_adeudos['id_persona'];
										unset($value_adeudos['id_persona']);
										$res[$key_cunt]['adeudos'][] = $value_adeudos;
										$key_cunt++;
								}
						}
						if ((!in_array($value_adeudos["sub_concepto"], $filtros["sub_conceptos"])) && ($value_adeudos["sub_concepto"] != "")) {
								array_push($filtros["sub_conceptos"], $value_adeudos["sub_concepto"]);
						}
						if ((!in_array($value_adeudos["descripcion_sc"], $filtros["descripcion_sc"])) && ($value_adeudos["descripcion_sc"] != "")) {
								array_push($filtros["descripcion_sc"], $value_adeudos["descripcion_sc"]);
						}
				}

				foreach ($res as $key_personas => $persona) {
						foreach ($alumnos as $key_alumnos => $alumno) {
								if (intval($alumno['idpersonas']) == intval($persona['id_persona'])) {
										unset($alumno['idpersonas']);
										$res[$key_personas] = array_merge($alumno, $persona);
										if (!in_array($alumno["carrera"], $filtros["carreras"]) && ($alumno["carrera"] != "")) {
												array_push($filtros["carreras"], $alumno["carrera"]);
										}
								}
						}
				}
				foreach ($res as $key => $value) {
						if (!isset($value['matricula'])) {
								unset($res[$key]);
						}
				}
				return array("alumnos" => $res, "filtros" => $filtros);
		}

		public function ajustar_adeudos_pagoa_de_mas($adeudo_id, $id_persona, $periodo, $cantidad) {
				$adeudos = Adeudos::join('sub_conceptos as sc', 'sc.id', '=', 'adeudos.sub_concepto_id')
								->where("adeudos.id_persona", "=", $id_persona)
								->where("adeudos.periodo", "=", $periodo)
								->where("adeudos.id", "<>", $adeudo_id)
								->select('adeudos.*', DB::raw("period_diff(date_format(now(), '%Y%m'), date_format(`fecha_limite`, '%Y%m')) as meses_retraso"), 'sc.aplica_beca', 'sc.sub_concepto')
								->get(); // Se obtienen los adeudos de una persona en el periodo solicitado

				foreach ($adeudos as $key => $adeudo) {
						if ($adeudo->importe >= $cantidad) {
								Adeudos::where('id', '=', $adeudo->id)->update(
												array(
														'importe' => round($adeudo->importe - $cantidad, 2)
												//'status_adeudo' => 1
								));
								$cantidad = 0;
								break;
						} else {
								Adeudos::where('id', '=', $adeudo->id)->update(
												array(
														//'importe' => round(0,2),
														'status_adeudo' => 1
								));
								$cantidad = $cantidad - $adeudo->importe;
						}
				}
				if ($cantidad > 0) {
						Devoluciones::create(array(
								'periodo' => $periodo,
								'fecha_devolucion' => date('Y-m-d'),
								'importe' => $cantidad,
								'id_persona' => $id_persona,
								'status_devolucion' => 0
						));
				}
		}


		public function parseAdeudos($adeudos,$filters) {
				$data=array();    
				foreach ($adeudos['data']['alumnos'] as $key_a => $adeudo) {
							foreach ($adeudo['adeudos'] as $key_sa => $sub_adeudo) {
								if (!isset($data['periodos'])) {
									$data['periodos'][]=array('periodo'=>$sub_adeudo['periodo']);
								} else {
									$check=false;
									foreach ($data['periodos'] as $key => $value) {
										if (intval($value['periodo'])!=intval($sub_adeudo['periodo'])) {
											$check=true;
										} else {
											$check=false;
											break;
										}
									}       
									if ($check==true) {
										$data['periodos'][]=array('periodo'=> $sub_adeudo['periodo']);
									}
								}
								foreach ($data['periodos'] as $key_p => $periodo) {
										if (isset($periodo['subconceptos'])) {
												$check_s=false;
												foreach ($periodo['subconceptos'] as $key_s => $sc) {
														if (intval($sub_adeudo['periodo'])==intval($periodo['periodo'])) {
																if (intval($sub_adeudo['sub_concepto_id'])==intval($sc['sub_concepto_id'])) {
																		$check_s=false;
																		break;
																} else {
																		$check_s=true;
																}
														}   else {
																break;
														}
												}   
												if ($check_s==true) {
														$data['periodos'][$key_p]['subconceptos'][]=array(
																'sub_concepto'=>$sub_adeudo['sub_concepto'],
																'sub_concepto_id'=>$sub_adeudo['sub_concepto_id']
														);
												}
										} else {
												$data['periodos'][$key_p]['subconceptos'][]=array(
														'sub_concepto'=>$sub_adeudo['sub_concepto'],
														'sub_concepto_id'=>$sub_adeudo['sub_concepto_id']
												);
										}
								}
								$data=$this->parseAdeudos_addAdeudoInfo($data,$sub_adeudo,$adeudo);
								
							}
						}
						$data=$this->parseAdeudos_addTotalesSubconcetos($data);
						
				return $data;
		}
		public function parseAdeudos_addAdeudoInfo($data,$sub_adeudo,$adeudo) {
				if ($sub_adeudo['tipo_pagoid']!=null) {
						$tipo_pago_echo = Tipo_pago::where('id','=',$sub_adeudo['tipo_pagoid'])->first();
						$tipo_pago_echo = $tipo_pago_echo['nombre'];
				} else {
						$tipo_pago_echo = "";
				}

				if ($sub_adeudo['cuenta_pagoid']!=null) {
						$cuenta_pago_echo = Cuentas::where('id','=',$sub_adeudo['cuenta_pagoid'])->first();
						$banco_pago_echo = Bancos::where('id','=',$cuenta_pago_echo['bancos_id'])->first();
						$cuenta_pago_echo = $cuenta_pago_echo['cuenta'];
						$banco_pago_echo =  $banco_pago_echo['banco'];
				} else {
						$cuenta_pago_echo = "";
						$banco_pago_echo = "";
				}

				$recargo_total=0;
				$descuento=0;
				$descuento_recargo=0;
				$descuentos = Descuentos::obtenerDescuentoPorAdeudo($sub_adeudo['id']);   
				$descuento_officio = "";
				$descuento_descripcion = "";
				foreach ($descuentos as $key_d => $descuentodata) {
						$descuento_tmp = $this->calcular_importe_por_tipo($sub_adeudo['importe'], $descuentodata['importe'], $descuentodata['tipo_importe_id']);
						$descuento_recargo_temp = $this->calcular_importe_por_tipo($sub_adeudo['importe'], $descuentodata['importe_recargo'], $descuentodata['tipo_importe_id']);
						
						$descuento_recargo = $descuento_recargo + $descuento_recargo_temp;
						$descuento = $descuento + $descuento_tmp;
						$descuento_officio = $descuentodata['no_officio'];
						$descuento_descripcion = $descuentodata['descripcion_officio'];
				}

				$now = strtotime('now'); // Se obtiene la fecha actual
				$daynow = date('d', $now); // Dia actual
				$fecha_limite = strtotime($sub_adeudo['fecha_limite']);
				$day = date('d', $fecha_limite);
				if ($daynow > $day) {
						$sub_adeudo['meses_retraso'] = $sub_adeudo['meses_retraso'] + 1;
				}

				//verifica si tiene beca
				$tiene_beca=Becas::AlumnoBeca_Persona_Periodo(array('id_persona'=>$adeudo['id_persona'],'periodo'=>$sub_adeudo['periodo'])); // Consulta beca
				if ($tiene_beca) {
						if ($sub_adeudo['aplica_beca']==1 ) { // Verifica si al adeudo le aplica beca
								//Si aplica beca la calcula, ya que puede ser por pocentaje o importe fijo
								$beca = $this->calcular_importe_por_tipo($sub_adeudo['importe'], $tiene_beca['importe'], $tiene_beca['tipo_importe_id']);
						} else {
								$beca = 0;
						}
				}   else {
						$beca=0;
				}
				if ($sub_adeudo['meses_retraso'] <= 0) { // si no se atrazo respeta beca y no genera adeudo
						$recargo_total = 0;
						$recargo_total_no_descuento = 0;
				}
				if ($sub_adeudo['meses_retraso'] > 0) { // Si se atrazo con un pago genera adeudo y quitara beca
						if ($sub_adeudo['aplica_recargo']==1) {
								//Si aplica recago lo calcula, ya que puede ser por pocentaje o importe fijo y lo muliplica por No. de meses rerasado
								$recargo = $this->calcular_importe_por_tipo($sub_adeudo['importe'], $sub_adeudo['recargo'], $sub_adeudo['tipo_recargo']);
								if ($sub_adeudo['recargo_acumulado'] == 1) {
										$recargo_total = $recargo*$sub_adeudo['meses_retraso'];
								}  else {
										$recargo_total = $recargo;
								}
								$recargo_total_no_descuento = $recargo_total;
								$recargo_total = $recargo_total - $descuento_recargo;
						} else {
								$recargo_total = 0;
						}
//              $curr_user = Session::all();
//        $data['cancelada_por'] = $curr_user['user']['persona']['iemail'];
//        $data['cancelada_por'], 
//                                'cancelada_fecha'=> $data['cancelada_fecha'], 
//                                'cancelada_motivo'=> $data['cancelada_motivo']));
						Becas::update_status_beca_alumno(array('id_persona'=>$adeudo['id_persona'],
																									 'periodo'=>$sub_adeudo['periodo'],
																									 'status'=> 0,
																									 'cancelada_por'=>"Sistema",
																									 'cancelada_fecha'=> date('Y-m-d'),
																									 'cancelada_motivo' => "Retraso en Pago")); // Cancelar Beca en periodo 
						$beca=0;
				}
				$total_adeudo=((($sub_adeudo['importe'] + $recargo_total)-$descuento)-$beca);
				foreach ($data['periodos'] as $key_p => $periodo) {
						foreach ($periodo['subconceptos'] as $key_s => $sc) {
								if (intval($sub_adeudo['periodo'])==intval($periodo['periodo'])) {
										#echo "<pre>" ;var_dump($sub_adeudo);echo "</pre>";die();
												if (intval($sub_adeudo['sub_concepto_id'])==intval($sc['sub_concepto_id'])) {
														if ($sub_adeudo['status_adeudo']==1) {
																#var_dump(floatval($sub_adeudo['recargo_pago']));die();
																		$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info'][]=array(
																				'clave' => $adeudo['id_persona'],
																				'matricula'=>$adeudo['matricula'],
																				'nombre'=>$adeudo['nom'],
																				'apellido paterno'=>$adeudo['appat'],
																				'apellido materno'=>$adeudo['apmat'],
																				'fecha_limite' => $sub_adeudo['fecha_limite'],
																				'fecha_pago' => $sub_adeudo['fecha_pago'],
																				'carrera' => $adeudo['carrera'],
																				'importe' =>$sub_adeudo['importe'],
																				'nivel' => $adeudo['nivel'],
																				'nombre_nivel' => $adeudo['nombre_nivel'],
																				'recargo'=>floatval($sub_adeudo['recargo_pago']),
																				'beca'=>floatval($sub_adeudo['beca_pago']),
																				'descuento' => floatval($descuento),
																				'descuento_recargo' => floatval($descuento_recargo),
																				'total' => floatval($sub_adeudo['importe_pago']),
																				'descuento_officio' => $descuento_officio,
																				'descuento_descripcion_officio' => $descuento_descripcion,
																				'concepto' => $sub_adeudo['concepto'],
																				'descripcion_concepto' => $sub_adeudo['descripcion_concepto'],
																				'tipo_pago' => $tipo_pago_echo,
																				'cuenta' => $cuenta_pago_echo,
																				'banco' => $banco_pago_echo
																		);
														} else {
																$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info'][]=array(
																				'clave' => $adeudo['id_persona'],
																				'matricula'=>$adeudo['matricula'],
																				'nombre'=>$adeudo['nom'],
																				'apellido paterno'=>$adeudo['appat'],
																				'apellido materno'=>$adeudo['apmat'],
																				'fecha_limite'=>$sub_adeudo['fecha_limite'],
																				'carrera' => $adeudo['carrera'],
																				'importe'=>floatval($sub_adeudo['importe']),
																				'nivel' => $adeudo['nivel'],
																				'nombre_nivel' => $adeudo['nombre_nivel'],
																				'recargo'=>floatval($recargo_total),
																				'recargo_no_descuento'=>floatval($recargo_total_no_descuento),
																				'beca'=>floatval($beca),
																				'descuento'=>floatval($descuento),
																				'descuento_recargo' => floatval($descuento_recargo),
																				'total'=> floatval($total_adeudo),
																				'descuento_officio' => $descuento_officio,
																				'descuento_descripcion_officio' => $descuento_descripcion,
																				'concepto' => $sub_adeudo['concepto'],
																				'descripcion_concepto' => $sub_adeudo['descripcion_concepto'],
																				'cuenta' => $cuenta_pago_echo,
																				'banco' => $banco_pago_echo
																		);
														}
												}
								}
						}
				}
				return $data;
		}


		public function parseAdeudos_addTotalesSubconcetos($data){
				if (isset($data['periodos'])) {
						foreach ($data['periodos'] as $key_p => $periodo) {
								$counter=0;
								foreach ($periodo['subconceptos'] as $key_s => $sc) {
										$idpersonas=0;
										$alumnos_total=0;
										$total=0;
										$recargo_total=0;
										$beca_total=0;
										$descuento_total=0;
										$descuento_recargo_total=0;
										$importe_total=0;
										foreach ($sc['adeudo_info'] as $key_ai => $value_ai) {
						$idpersonas=intval($value_ai['clave']);
												$total=$value_ai['total'] + $total; 
												$recargo_total=$value_ai['recargo'] + $recargo_total;
												$beca_total=$value_ai['beca'] + $beca_total;
												$descuento_total=$value_ai['descuento'] + $descuento_total;
												$descuento_recargo_total=$value_ai['descuento_recargo'] + $descuento_recargo_total;
												$importe_total=$value_ai['importe'] + $importe_total;
												if ( intval($value_ai['clave'])!=$idpersonas) {
														$alumnos_total++;
												}
												
												$counter++;
										}
										$counter++;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['alumnos_total']=$alumnos_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['recargo_total']=$recargo_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['beca_total']=$beca_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['descuento_total']=$descuento_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['descuento_recargo_total']=$descuento_recargo_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['importe_total']=$importe_total;
										$data['periodos'][$key_p]['subconceptos'][$key_s]['adeudo_info']['total_subconceptos']['total']=$total;
								}
								$data['periodos'][$key_p]['num_adeudos']=$counter;
						}
				} else {
						$data = array();
				}
				
				return $data;
		}
		public function parseAdeudosToReportFront($data)    {
				$new_data=array();
				foreach ($data['periodos'] as $key_p => $periodo) {
						foreach ($periodo['subconceptos'] as $key_s => $sc) {
								foreach ($sc['adeudo_info'] as $key_ai => $value_ai) {
										if (isset($value_ai['importe_total'])) {
												$new_data[] = array(
																'periodo' => $periodo['periodo'],
																'sub_concepto' => $sc['sub_concepto'],
																'alumnos_total' => $value_ai['alumnos_total'],
																'recargo_total' => $value_ai['recargo_total'],
																'descuento_total' => $value_ai['descuento_total'],
																'beca_total' => $value_ai['beca_total'],
																'importe_total' => $value_ai['importe_total'],
																'total' => $value_ai['total']
														);
										} else {
												$new_data[]=array(
														'periodo' => $periodo['periodo'],
														'sub_concepto' => $sc['sub_concepto'],
														'clave' => $value_ai['clave'],
														'matricula'=>$value_ai['matricula'],
														'nombre'=>$value_ai['nombre'].' '.$value_ai['appat'].' '.$value_ai['apmat'],
														'fecha_limite'=>$value_ai['fecha_limite'],
														'carrera' => $value_ai['carrera'],
														'importe'=>floatval($value_ai['importe']),
														'recargo'=>floatval($value_ai['recargo']),
														'beca'=>floatval($value_ai['beca']),
														'descuento'=>floatval($value_ai['descuento']),
														'total'=> floatval($value_ai['total']),
														'alumno' => 1
												);
										}
								}
						}
				}
		}
}

?>