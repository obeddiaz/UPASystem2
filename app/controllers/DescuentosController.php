<?php

class DescuentosController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$res['data']=Descuentos::All();
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
			    'tipo_importe_id' => 'required|integer',
			    'adeudos_id' => 'required|integer',
			    'importe' => 'required',
			    'importe_recargo' => 'required',
			    'no_officio' => 'required',
			    'descripcion_officio' => 'required'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Descuentos::create($parametros);
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
	 * Display the  resource by period or date.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show_reporte()
	{
		ini_set('max_execution_time', 300);
        $commond = new Common_functions();
        $parametros = Input::get();
        $reglas = array(
            'fecha_desde' => 'date_format:Y-m-d',
            'fecha_hasta' => 'date_format:Y-m-d',
            'periodo' => 'integer'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
        	if (isset($parametros['periodo'])) {
	        	$res = DB::table('descuentos')->join('alumnos', 'alumnos.id', '=', 'descuentos.adeudos_id')
                	->where('alumnos.periodo','=',$parametros['periodo'])
                	->select('descuentos.*')
                	->get();
        	} else {
        		$res = DB::table('descuentos')->join('alumnos', 'alumnos.id', '=', 'descuentos.adeudos_id')
                	->where("adeudos.fecha_limite", ">=", $parametros['fecha_desde'])
                    ->where("adeudos.fecha_limite", "<=", $parametros['fecha_hasta'])
                	->select('descuentos.*')
                	->get();
        	}
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o no están mal', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
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
			    'importe' => 'numeric',
			    'importe_recargo' => 'numeric',
			    'id' => 'required|integer'
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			foreach ($parametros as $key => $value) {
				if (!array_key_exists($key,$reglas)) {
					unset($parametros[$key]);	
				}
			}
			Descuentos::where('id','=',$parametros['id'])->update($parametros);
			$res['data']=Descuentos::find($parametros['id']);
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
			Descuentos::destroy($parametros['id']);
			$res['data']=Descuentos::All();
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}

	public static function expediente(){
		$parametros= Input::get();	
		$reglas = 
			array(
			    'id' => 'integer',
			);
    	$validator = Validator::make($parametros,$reglas);

		if (!$validator->fails())
		{
			$res['data']=Descuentos::obtenerAdeudosDeDescuentos($parametros['id']);
			$respuesta = json_encode(array('error' =>false,'mensaje'=>'Nuevo registro', 'respuesta'=>$res));
		} else {
			$respuesta = json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
		}	
		$final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
	}


}
