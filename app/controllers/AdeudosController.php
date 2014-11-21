<?php

class AdeudosController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
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
	 * Muestra los adeudos por periodos.
	 *
	 * @param  int  $id_periodo
	 * @return Response
	 */
	public function show_by_periodo()
	{
		$parametros=Input::get();		
		$reglas = 
			array(
			    'periodo' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			Adeudos::All()->where('periodo','=',$parametros['periodo']);
		} else {
			echo json_encode(array('error' =>true,'mensaje'=>'No hay parametros o no están mal', 'respuesta'=>null ));
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
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
