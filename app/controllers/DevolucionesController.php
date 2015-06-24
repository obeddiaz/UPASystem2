<?php

class DevolucionesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Devoluciones::All();
		return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
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
				'periodo'=>'required|integer',
			    'fecha_devolucion' => 'date_format:Y-m-d',
			    'importe' => 'required|numeric',
			    'id_persona'=>'required|integer',
			    'status_devolucion'=>'integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Devoluciones::create($parametros);
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
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id' => 'required|integer',
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Devoluciones::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
	public function show_persona_periodo()
	{
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id_persona' => 'required|integer',
			    'periodo'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Devoluciones::where('id_persona','=',$parametros['id_persona'])
									->where('periodo','=',$parametros['periodo'])
									->get();
			$res['total']=Devoluciones::where('id_persona','=',$parametros['id_persona'])
									->where('periodo','=',$parametros['periodo'])
									->sum('importe');
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_periodo()
	{
		$parametros= Input::get();	
		$reglas = 
			array(
			    'periodo'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Devoluciones::where('periodo','=',$parametros['periodo'])
									->get();
			$res['total']=Devoluciones::where('periodo','=',$parametros['periodo'])
									->sum('importe');
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_persona()
	{
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id_persona' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Devoluciones::where('id_persona','=',$parametros['id_persona'])
									->get();
			$res['total']=Devoluciones::where('id_persona','=',$parametros['id_persona'])
									->sum('importe');
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
			    'id' => 'required|integer',
			    'periodo'=>'integer',
			    'fecha_devolucion' => 'date_format:Y-m-d',
			    'importe' => 'numeric',
			    'id_persona'=>'integer',
			    'status_devolucion'=>'integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			Devoluciones::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Devoluciones::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
	public function update_status()
	{
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'status_devolucion'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			$parametros['fecha_devolucion']=date('Y-m-d');
			Devoluciones::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Devoluciones::find($parametros['id']);
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
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id' => 'required|integer',
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			Devoluciones::destroy($parametros['id']);
			$res['data']=Devoluciones::All();
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
}
