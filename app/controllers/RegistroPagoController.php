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
				'tipo_pago' => 'integer',
				'asignada_por'	=>	'required',
			    'importe' => 'required',
			    'importe_recargo'	=> 'required',
			    'adeudo_id' => 'required',
			    'razon' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails()) {
			
			$adeudo = Adeudos::where('id','=',$parametros['adeudo_id'])->first();
			
			if (isset($parametros['tipo_pago'])) {
				$tipo_pago = $parametros['tipo_pago'];
				unset($parametros['tipo_pago']);
			}	else	{
				$tipo_pago = 1;
			}
			
			$pago = Pagos::create(array(
				'tipo_pago' => $tipo_pago,
				'importe' => $parametros['importe'],
				'importe_recargo' => $parametros['importe_recargo']
				'fecha_pago'	=>  date('Y-m-d H:i:s',date(strtotime('now')))
				));

			unset($parametros['importe']);
			unset($parametros['importe_recargo']);

			$parametros['pago_id'] = $pago['id'];
			$parametros['email_login_asignacion']=$user['user']['persona']['iemail'];
			
			Registros::create($parametros);
			
			$res['data'] = Adeudos::obtener_adeudos_alumno(array('id_persona' =>$adeudo['id_persona'],
							'periodo' => $adeudo['periodo']));
			$respuesta = json_encode(array('error' => false, 'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
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