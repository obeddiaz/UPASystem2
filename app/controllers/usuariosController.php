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
        $params = Input::get();
        $sii = new Sii();
        //$sii->login('fernando.medrano@upa.edu.mx','upa.loopa');
        if (isset($params['u']) && isset($params['p'])) {
            $user = $sii->login($params['u'], $params['p']);
            if (isset($user['error'])) {
                return json_encode(array("error" => "User or password Incorrect"));
            } else {
                Session::put('user', $user);
                return json_encode(Session::all());
            }
        }

//        if ($user) {
//            $results[] = $user->toArray();
//            Session::put('user', $results);
//            return json_encode(Session::all());
//        } else {
//            $user = Usuarios::getUserAdminProfesor($params);
//            if ($user) {
//                $results[] = $user->toArray();
//                Session::put('user', $results);
//                return json_encode(Session::all());
//            } else {
//                return json_encode(array("error" => "User or password Incorrect"));
//            }
//        }
//        $params = Input::get();
    }

    public function show() {
        if (Session::has('user')) {
            return json_encode(array("error" => false, 'message' => "The user is not logged in", 'response' => array(Session::all(), 200)));
        } else {
            return json_encode(array("error" => true, 'message' => "The user is not logged in", 'response' => array('', 404)));
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
