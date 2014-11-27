<?php

class BecasController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Becas::All();
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
			'abreviatura' => Input::get('abreviatura'), 
			'importe' => Input::get('importe'), 
			'periodicidades_id' => Input::get('periodicidades_id'),
			'subcidios_id' => Input::get('subcidios_id'), 
			'tipo_importe_id' => Input::get('tipo_importe_id'), 
			'tipobeca' => Input::get('tipobeca')
		);		
		$reglas = array(
			'abreviatura' => 'required', 
			'importe' => 'required|numeric', 
			'periodicidades_id' => 'required|integer',
			'subcidios_id' => 'required|integer', 
			'tipo_importe_id' => 'required|integer', 
			'tipobeca' => 'required|integer'
		);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Becas::create($parametros);
			return json_encode(array('error' =>false,'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
	public function create_alumno()
	{
		$parametros=array(
			 'id_persona' => Input::get('id_persona') , 
			 'idbeca' => Input::get('idbeca'), 
			 'idnivel' => Input::get('idnivel'), 
			 'periodo' => Input::get('periodo'), 
			 'status' =>1
		);		
		$reglas = array(
			'id_persona' => 'required', 
			'idbeca' => 'required|numeric', 
			'idnivel' => 'required|integer',
			'periodo' => 'required|integer', 
			'status' => 'required|integer'
		);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Becas::create_beca_alumno($parametros);
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
			$res['tipo_importe']=Becas::obtenerTipoImporte();
			$res['periodicidades']=Becas::obtenerPerodicidades();
			$res['subcidios']=Becas::obtenerSubcidios();
			$res['data']=Becas::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_alumno_activo()
	{
		$parametros= array(
			'idbeca' => Input::get('idbeca'), 
			'idnivel' => Input::get('idnivel'), 
			'periodo' => Input::get('periodo'), 
			'status' =>1
		);		
		$reglas = array(
			'idbeca' => 'required|numeric', 
			'idnivel' => 'required|integer',
			'periodo' => 'required|integer', 
			'status' => 'required|integer'
		);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Becas::find($parametros['id']);
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
			'id' => Input::get('id'),
			'abreviatura' => Input::get('abreviatura'), 
			'importe' => Input::get('importe'), 
			'periodicidades_id' => Input::get('periodicidades_id'),
			'subcidios_id' => Input::get('subcidios_id'), 
			'tipo_importe_id' => Input::get('tipo_importe_id'), 
			'tipobeca' => Input::get('tipobeca')
		);		
		$reglas = array(
			'id' => 'required',
			'abreviatura' => '', 
			'importe' => 'numeric', 
			'periodicidades_id' => 'integer',
			'subcidios_id' => 'integer', 
			'tipo_importe_id' => 'integer', 
			'tipobeca' => 'integer'
		);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			Becas::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Becas::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
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
			
			Becas::destroy($parametros['id']);
			$res['data']=Becas::All();
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}


}
