<?php

class ConceptosController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Conceptos::All();
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
		$parametros=array(
			'descripcion' => Input::get('descripcion'), 
			'concepto' => Input::get('concepto'),
			'banco_id'  => Input::get('banco_id'),
			'cuenta_id'  => Input::get('cuenta_id')
		);		
		$reglas = 
			array(
			    'descripcion' => 'required',
			    'concepto' => 'required|max:30',
			    'banco_id'  => 'required|integer',
				'cuenta_id'  => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Conceptos::create($parametros);
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
	public function show()
	{
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Conceptos::find($parametros['id']);
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}
	public function show_subconceptos()
	{
		$sii = new Sii();
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'nivel_id'=> 'required|integer',
			    'periodo'=> 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{	
			#$periodo_actual=Common_function::periodo_actual();
			$res['data']=Conceptos::find($parametros['id'])
									->subconceptos()
									->where('periodo','=',$parametros['periodo'])
									->where('nivel_id','=',$parametros['nivel_id'])
									->get();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}
	public function show_by_plan_de_pago()
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
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'concepto' => 'max:30',
			    'descripcion' => '',
			    'banco_id'  => 'integer',
				'cuenta_id'  => 'integer'

			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			$last_info = Conceptos::where('id','=',$parametros['id'])->first();
			Conceptos::where('id','=',$parametros['id'])->update($parametros);
			if (isset($parametros['cuenta_id'])) {
				Conceptos::where('cuenta','=',$last_info['cuenta_id'])->update(array('cuenta_pagoid' => $parametros['cuenta_id']));
			}
			$res['data'] = Conceptos::find($parametros['id']);
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
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			Conceptos::destroy($parametros['id']);
			$res['data']=Conceptos::All();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}
}
