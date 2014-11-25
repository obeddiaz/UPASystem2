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
			'descripcion' => Input::get('descripcion'), 
			'concepto' => Input::get('concepto')
		);		
		$reglas = 
			array(
			    'descripcion' => 'required',
			    'concepto' => 'required|max:30'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Conceptos::create($parametros);
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
			$res['data']=Conceptos::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
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
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
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
		$parametros=array(
			'id'=>Input::get('id'),
			'descripcion' => Input::get('descripcion'), 
			'concepto' => Input::get('concepto')
		);				
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'concepto' => 'required|max:30',
			    'descripcion' => 'required|alpha_dash'
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			Conceptos::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Conceptos::find($parametros['id']);
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
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
}
