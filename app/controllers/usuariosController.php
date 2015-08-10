<?php

class UsuariosController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /usuarios
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     * GET /usuarios/create
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /usuarios
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     * GET /usuarios/{id}
     *
     * @param  string  $nocuenta
     * @param  string  $password
     * @return Response
     */
    public function login() {
        $sii = new Sii();
        $parametros = Input::get();
        $reglas = array(
            'u' => 'required',
            'p' => 'required'
        );
        $validator = Validator::make($parametros, $reglas);

        if (!$validator->fails()) {
            $user = $sii->login($parametros['u'], $parametros['p']);

            if (isset($user['error'])) {
                $respuesta= json_encode(array('error' => true, 'mensaje' => 'User or password Incorrect', 'respuesta' => ''));
            } else {
                Session::put('user', $user);
                if (isset($user['persona']['alumno']) && intval($user['persona']['alumno']) == 1) {
                    $commond = new Common_functions();
                    $grado = $commond->obtener_infoAlumno_idPersona(array('id_persona' => $user['persona']['idpersonas']));
                    if (isset($grado[0])) {
                        $user['persona']['grado'] = $grado[0]['grado'];
                    } else {
                        $user['persona']['grado'] = null;
                    }
                }
                $respuesta= array("error" => false, 'message' => "Usuario autenticado", 'respuesta' => array(Session::all(), 200));
            }
        } else {
            $respuesta= json_encode(array('error' => true, 'mensaje' => 'No hay parametros o estan mal.', 'respuesta' => null));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    public function show() {
        if (Session::has('user')) {
            $respuesta= json_encode(array("error" => false, 'message' => "Usuario autenticado", 'respuesta' => array(Session::all(), 200)));
        } else {
            $respuesta= json_encode(array("error" => true, 'message' => "Usuario no autenticado", 'respuesta' => array('', 404)));
        }
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
    }

    /**
     * Show the form for editing the specified resource.
     * GET /usuarios/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /usuarios/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /usuarios/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
