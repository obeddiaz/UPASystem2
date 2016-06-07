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
		$user 			= Session::all();
		$parametros	= Input::get();	
		$reglas			= array(	'adeudos_id'				=>	'required',
													'tipo_pago'					=>	'integer',
													'asignada_por'			=>	'required',
													'importe' 					=>	'required',
													'importe_recargo'		=>	'required',
													'descuento'					=>	'required',
													'descuento_recargo'	=>	'required',
													'total'							=>	'required',								    
													'razon' 						=>	'required',
													'beca'							=>	'required',
													'fecha_pago'				=>	'required'	);

	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails()) {
			
			$adeudo = Adeudos::obtener_adeudos_alumno(array( 	'id'  	=>  $parametros['adeudos_id'],
																												'fecha' =>  $parametros['fecha_de_pago']));

			if ($parametros['total'] <= $adeudo['importe']) {
				
				$pago = Pagos::create(array(	'adeudos_id'					=>	$parametros['adeudos_id'],
																			'tipo_pago' 					=>	( isset($parametros['tipo_pago']) ? $parametros['tipo_pago'] : 1),
																			'importe'							=>	$parametros['importe'],
																			'importe_recargo' 		=>	$parametros['importe_recargo'],
																			'descuento'						=>	$parametros['descuento'],
																			'descuento_recargo'		=>	$parametros['descuento_recargo'],
																			'total'								=>	$parametros['total'],
																			'beca'								=>	$parametros['beca'],
																			'fecha_pago'					=>  $parametros['fecha_pago']	));

				unset(	$parametros['importe']						);
				unset(	$parametros['importe_recargo']		);
				unset(	$parametros['descuento']					);
				unset(	$parametros['descuento_recargo']	);
				unset(	$parametros['total']							);
				unset(	$parametros['tipo_pago']					);

				$parametros['pago_id'] = $pago['id'];
				$parametros['email_login_asignacion']=$user['user']['persona']['iemail'];
				
				Registros::create($parametros);
				
				$res['data'] = Adeudos::obtener_adeudos_alumno(array(	'id_persona'	=>	$adeudo['id_persona'],
																															'periodo' 		=>	$adeudo['periodo']	));

				$respuesta = json_encode(array('error' => false, 'mensaje'=>'Nuevo registro', 'respuesta'=>$res));

			}	else  {

				$respuesta = json_encode(array(	'error' 		=>	true,
																				'mensaje'		=>	'El total es mayor al importe del adeudo (favor de confirmar los datos).',
																				'respuesta'	=>	null 	));

			}
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null));
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
				 'asignada_por'	=>	'size:200',
				 'importe' => 'numeric',
				 'importe_recargo' => 'numeric',
				 'adeudo_id' => 'required|integer',
				 'pago_id' => 'required|integer'
				 'razon' => 'size:200',
				 'tipo_pago' => 'integer'
			);
		$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			if (isset($parametros['tipo_pago'])) {
				$parametrosPago['tipo_pago'] = $parametros['tipo_pago'];
				unset($parametros['tipo_pago']);
			}	else	{
				$parametrosPago['tipo_pago'] = 1;
			}
			$adeudo = Adeudos::where('id','=',$parametros['adeudo_id'])->first();
			$parametrosPago['importe'] = $parametros['importe'];
			$parametrosPago['importe_recargo'] = $parametros['importe_recargo'];
			unset($parametros['importe']);
			unset($parametros['importe_recargo']);
			Pagos::where('id','=',$parametros['pago_id'])->update($parametrosPago);
			Registros::where('id','=',$parametros['id'])->update($parametros);
			$res['data'] = Adeudos::obtener_adeudos_alumno(
															array(
																'id_persona' => $adeudo['id_persona'],
																'periodo' => $adeudo['periodo'])
															);
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
			$registro = Registros::where('id','=',$parametros['id'])->first();
			$adeudo = Adeudos::where('id','=',$registro['adeudo_id'])->first();

			Registros::destroy($parametros['id']);
			$res['data'] = Adeudos::obtener_adeudos_alumno(
															array(
																'id_persona' =>$adeudo['id_persona'],
																'periodo' => $adeudo['periodo'])
															);
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
		  $final_response->header('Content-Type', "application/json; charset=utf-8");

		  return $final_response;
	}
}