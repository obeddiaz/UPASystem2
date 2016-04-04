<?php

class RegistroPagoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{				 
		$res['data'] = Registros::All();
		$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = Session::all();
		$parametros= Input::get();	
		$reglas = array(
			    'importe_registro_pago' => 'required',
			    'adeudo_id' => 'required',
			    'razon' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$adeudo_info = Adeudos::find($parametros['adeudo_id']);

			if ($adeudo_info['status_adeudo']==0) {

				$adeudo_up = Adeudos::obtener_adeudos_alumno(array(
                    'id' => $adeudo_info['id'],
                    'fecha_pago' => $adeudo_info['fecha_pago'],
                    'id_persona' => $adeudo_info['id_persona'],
                    'periodo' => $adeudo_info['periodo']
                ));

				if ($adeudo_up[0]['importe'] == $parametros['importe_registro_pago']) {
					if ($adeudo_up[0]['beca'] != "N/A") {
						$beca = $adeudo_up[0]['beca'];
					} else {
						$beca = 0;
					}
					Adeudos::where('id', '=', $adeudo_info['id'])->update(
                        array(
                            'beca_pago' => $beca,
                            'recargo_pago' => $adeudo_up[0]['recargo'],
                            'importe_pago' => $parametros['importe_registro_pago'],
                            'status_adeudo' => 1,
                            'fecha_pago' => date('Y-m-d H:i:s'),
                            'tipo_pagoid' => 1
                	));
                	$res['status_pago'] = 'Pago realizado correctamente';
				}
				if ($adeudo_up[0]['importe'] > $parametros['importe_registro_pago']) {
					if ($adeudo_up[0]['beca'] != "N/A") {
						$beca = $adeudo_up[0]['beca'];
					} else {
						$beca = 0;
					}
					Devoluciones::create(array(
                        'periodo' => $adeudo_info['periodo'],
                        'fecha_devolucion' => date('Y-m-d'),
                        'importe' => $parametros['importe_registro_pago'] - $adeudo_up[0]['importe'],
                        'id_persona' => $id_persona,
                        'status_devolucion' => 0
                    ));   
                    Adeudos::where('id', '=', $adeudo_info['id'])->update(
                        array(
                            'beca_pago' => $beca,
                            'recargo_pago' => $adeudo_up[0]['recargo'],
                            'importe_pago' => $adeudo_up[0]['importe'],
                            'status_adeudo' => 1,
                            'fecha_pago' => date('Y-m-d H:i:s'),
                            'tipo_pagoid' => 1
                	));
                    $res['status_pago'] = 'Se ha pagado de mas, se ha generado una Devolucion de: $'.
                    						$parametros['importe_registro_pago'] - $adeudo_up[0]['importe'];
				}
				if ($adeudo_up[0]['importe'] < $parametros['importe_registro_pago']) {
					Adeudos::where('id', '=', $adeudo_info['id'])->update(
                        array(
                            'importe' => $adeudo_up[0]['importe'] - $parametros['importe_registro_pago'],
                            'status_adeudo' => 0,
                            'fecha_pago' => date('Y-m-d H:i:s'),
                            'tipo_pagoid' => 1
                	));
                    $res['status_pago'] = 'Pago no liberado, se pago una cantidad menor al adeudo total, se debe:'.
						$adeudo_up[0]['importe'] - $parametros['importe_registro_pago'];
				}

				$parametros['asignada_por']=$user['user']['persona']['iemail'];
				Registros::create($parametros);
				$res['data'] = Adeudos::obtener_adeudos_alumno(
					array('id_persona' =>$adeudo_info['id_persona'],'periodo' =>$adeudo_info['periodo']));
				$respuesta = json_encode(array('error' => false, 'mensaje'=>'Nuevo registro', 'respuesta'=>$res));

			} else {
				$res['data'] = Adeudos::obtener_adeudos_alumno(
					array('id_persona' =>$adeudo_info['id_persona'],'periodo' =>$adeudo_info['periodo']));
				$respuesta = json_encode(array('error' => true, 'mensaje'=>'El pago habia sido realizado correctamente anterriormente, no se realizo accion', 'respuesta'=>$res));
			}
		} else {
			$respuesta = json_encode(
				array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Registros::find($parametros['id']);
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$parametros= Input::get();	
        $user = Session::all();
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'importe_registro_pago' => 'numeric',
			    'adeudo_id' => 'integer',
			    'razon' => ''
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			Registros::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Registros::find($parametros['id']);
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id' => 'required|integer',
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			Registros::destroy($parametros['id']);
			$res['data']=Registros::All();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}
}