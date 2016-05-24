<?php

class ProrrogasController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Prorrogas::All();
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
		$parametros= Input::get();	
		$reglas = 
			array(
				'adeudos_id'  => 'required|integer',
				'no_oficio'  => 'required|integer',
				'nombre_responsable' => 'required',
				'rason_prorroga'  => 'required',
				'fecha_limite' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$fecha_limite = $parametros['fecha_limite'];
			unset($parametros['fecha_limite']);
			Adeudos::where('id','=',$parametros['adeudos_id'])->update(array('fecha_limite' => $fecha_limite));
			$res['data']=Prorrogas::create($parametros);
			$res['data']=Prorrogas::All();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
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
		//
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
		$reglas = 
			array(
				#'adeudos_id'  => 'required|integer',
				'id' => 'required|integer',
				'no_oficio'  => 'required|integer',
				'nombre_responsable' => 'required',
				'rason_prorroga'  => 'required',
				'fecha_limite' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$prorroga = Prorrogas::where('id','=',$parametros['id'])->first();
			$fecha_limite = $parametros['fecha_limite'];
			unset($parametros['fecha_limite']);
			Adeudos::where('id','=',$prorroga['adeudos_id'])->update(array('fecha_limite' => $fecha_limite));
			Prorrogas::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Prorrogas::find($parametros['id']);
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
			Prorrogas::destroy($parametros['id']);
			$res['data']=Prorrogas::All();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}




}
