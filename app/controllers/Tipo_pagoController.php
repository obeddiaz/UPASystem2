<?php

class Tipo_pagoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Tipo_pago::All();
		return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));	
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$parametros=array(
			'nombre' => Input::get('nombre'), 
			'descripcion' => Input::get('descripcion')
		);		
		$reglas = 
			array(
			    'nombre' => 'required',
			    'descripcion' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Tipo_pago::create($parametros);
			return json_encode(array('error' =>false,'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}	
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
			$res['data']=Tipo_pago::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
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
		$parametros=array(
			'id'=>Input::get('id'),
			'descripcion' => Input::get('descripcion'), 
			'nombre' => Input::get('nombre')
		);				
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'nombre' => 'max:30',
			    'descripcion' => ''
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			Tipo_pago::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Tipo_pago::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			Tipo_pago::destroy($parametros['id']);
			$res['data']=Tipo_pago::All();
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}			}


}
