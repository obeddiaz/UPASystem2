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
        $parametros= Input::get();
        $reglas = array( 
            'u'  => 'required',
            'p' => 'required'
        );
        $validator = Validator::make($parametros,$reglas);

        if (!$validator->fails())
        {
            $user = $sii->login($parametros['u'], $parametros['p']);
            if (isset($user['error'])) {
                return json_encode(array('error' => true,'mensaje'=>'User or password Incorrect','respuesta'=>'' ));
            } else {
                Session::put('user', $user);
                return array("error" => false, 'message' => "Usuario autenticado", 'respuesta' => array(Session::all(), 200));
            }
        } else {
            return json_encode(array('error' =>true,'mensaje'=>'No hay parametros o estan mal.', 'respuesta'=>null ));
        }
    }

    public function show() {
        if (Session::has('user')) {
            return json_encode(array("error" => false, 'message' => "Usuario autenticado", 'respuesta' => array(Session::all(), 200)));
        } else {
            return json_encode(array("error" => true, 'message' => "Usuario no autenticado", 'respuesta' => array('', 404)));
        }
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
