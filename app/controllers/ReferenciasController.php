<?php

class ReferenciasController extends \BaseController {

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

	public function leer_archivo_banco() {
            $file=Input::file('referencia_archivo');
            $fp = file($file);
            var_dump($fp);
            //var_dump(strlen($fp[0]));
            //var_dump(strlen($fp[0])==337);
            foreach ($fp as $line){
                if (strlen($line)==143){
//                    $explode_data=explode('.',substr($line,57));
//                    var_dump(substr($line,0,27));
//                    var_dump(substr($explode_data[3],11,10));
//                    $referencia_data[]=$explode_data;
//                    var_dump(substr($line,57));
//                    var_dump($explode_data[0]);
//                    var_dump($explode_data[1]);
//                    var_dump($explode_data[2]);
//                    var_dump(substr($explode_data[3],21));
                    
                }
               // var_dump(strlen($line));
            }
            //echo json_encode($referencia_data);
	}
}
