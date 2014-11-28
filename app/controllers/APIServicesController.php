<?php

class APIServicesController extends \BaseController {

	private $sii;
	public function __construct() {
		$this->sii= new Sii();
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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

	public function periodos() {
		$res['data']=array();
		try {
			$res['data']=$this->sii->new_request('POST','/periodos');	
			if (isset($res['data']) && !isset($res['data']['error'])) {
				return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
			} else {
				return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
			}
		} catch (Exception $e) {
			return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
		}
	}

	public function alumnos() {
		$res['data']=array();
		try {
			$res['data']=$this->sii->new_request('POST','/alumnos');	
			if (isset($res['data']) && !isset($res['data']['error'])) {
				return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
			} else {
				return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
			}
		} catch (Exception $e) {
			return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
		}
	}
	public function grupos() {
		$res['data']=array();
		try {
			$res['data']=$this->sii->new_request('POST','/grupos');	
			if (isset($res['data']) && !isset($res['data']['error'])) {
				return json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
			} else {
				return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
			}
		} catch (Exception $e) {
			return json_encode(array('error' =>true,'mensaje'=>'Algo esta mal con el servico.', 'respuesta'=>null ));
		}
	}
}
