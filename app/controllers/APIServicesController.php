<?php

class APIServicesController extends \BaseController {

    private $sii;

    public function __construct() {
        $this->sii = new Sii();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    public function periodos() {
        $res['data'] = array();
        try {
            $res['data'] = $this->sii->new_request('POST', '/periodos');
            if (isset($res['data']) && !isset($res['data']['error'])) {
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
            }
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function alumnos_activos() {
        $res['data'] = array();
        try {
            $res['data'] = $this->sii->new_request('POST', '/alumnos');
            if (isset($res['data']) && !isset($res['data']['error'])) {
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
            }
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function alumnos_todos() {
        $res['data'] = array();
        try {
            $res['data'] = $this->sii->new_request('POST', '/alumnos/all');
            if (isset($res['data']) && !isset($res['data']['error'])) {
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
            }
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function grupos() {
        $res['data'] = array();
        try {
            $res['data'] = $this->sii->new_request('POST', '/grupos');
            if (isset($res['data']) && !isset($res['data']['error'])) {
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
            }
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function niveles() {
        $res['data'] = array();
        try {
            $res['data'] = $this->sii->new_request('POST', '/niveles');
            if (isset($res['data']) && !isset($res['data']['error'])) {
                $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
            } else {
                $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
            }
        } catch (Exception $e) {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'Algo esta mal con el servico.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }
    
    public function create_user_api() {
        $parametros=Input::get();
        $user = Sentry::createUser(array(
            'email'     => 'api.pagos@pagos.com',
            'password'  => 'pagos2015',
            'activated' => true,
        ));
        $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $user));
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;

    }

    public function alumnos_adeudos_pagados_subconcepto() {
        $parametros=Input::get();

        $reglas = array(
            'sub_concepto' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res=Adeudos::where('sub_concepto_id','=',$parametros['sub_concepto'])
                        ->where('status_adeudo','=',1)
                        ->select(
                            'id_persona',
                            'fecha_pago'
                            )
                        ->get();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
    }

    public function subconceptos_periodo() {
        $parametros=Input::get();

        $reglas = array(
            'periodo' => 'required|integer',
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $res=Sub_conceptos::where('periodo','=',$parametros['periodo'])->get();
            $respuesta = json_encode(array('error' => false, 'mensaje' => '', 'respuesta' => $res));
        } else {
            $respuesta = json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }
}
