<?php

class Planes_de_pagoController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Planes_de_pago::All();
		return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$parametros=Input::all();		
		$reglas = 
		array(
			'descripcion' => 'required',
		    'clave_plan' => 'required|max:6',
		    'id_agrupaciones' => 'required|integer'
		);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Planes_de_pago::create($parametros);
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
			$res['data']=Planes_de_pago::find($parametros['id']);
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_byAgrupaciones()
	{
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id_agrupaciones' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Planes_de_pago::where('id_agrupaciones','=',$parametros['id_agrupaciones'])->get();
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_paquete_alumno()
	{
		$commond = new Common_functions();        
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',	
			    'periodo'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$paquete=Planes_de_pago::paquetes($parametros);
			if ($paquete || !empty($paquete)) {			
				$res['paquete']=$paquete;
				$res['data']=Paquete::personasPaquete($paquete['id']);
				if ($res['data'] || !empty($res['data'])) {
					$res['data']=$commond->obtener_alumno_idPersona($res['data']);
				} else {
					$res['data']=array();
				}
			} else {
				return json_encode(array('error' =>true,'mensaje'=>'No existe paquete en periodo actual.', 'respuesta'=>null ));	
			}
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}

	public function show_no_paquete_alumno()
	{
		$commond = new Common_functions();        
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',	
			    'periodo'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$paquete=Planes_de_pago::paquetes($parametros);
			if ($paquete || !empty($paquete)) {			
				$res['paquete']=$paquete;
				$res['data']=Paquete::personasPaquete($paquete['id']);
				if ($res['data'] || !empty($res['data'])) {
					$res['data']=$commond->obtener_alumno_No_idPersona($res['data']);
				} else {
					$res['data']=array();
				}
			} else {
				return json_encode(array('error' =>true,'mensaje'=>'No existe paquete en periodo actual.', 'respuesta'=>null ));	
			}
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}
	
	public function show_subconceptos()
	{
		$commond = new Common_functions();        
		$parametros=Input::get();		
		$reglas = 
			array(
			    'id' => 'required|integer',	
			    'periodo'=>'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Planes_de_pago::sub_conceptos($parametros);
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
		$parametros=Input::get();
		$reglas = 
			array(
			    'id' => 'required|integer',
			    'clave_plan' => 'max:6',
			    'id_agrupaciones' => 'integer',
			    'descripcion' => ''
			);
    	$validator = Validator::make($parametros,$reglas);
		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			Planes_de_pago::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Planes_de_pago::find($parametros['id']);
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
			Planes_de_pago::destroy($parametros['id']);
			$res['data']=Planes_de_pago::All();
			return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
	}


}
