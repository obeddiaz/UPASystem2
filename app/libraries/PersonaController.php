<?php

class PersonaController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Default Home Controller
      |--------------------------------------------------------------------------
      |
      | You may wish to use controllers instead of, or in addition to, Closure
      | based routes. That's great! Here is an example controller method to
      | get you started. To route to this controller, just add the route:
      |
      |	Route::get('/', 'HomeController@showWelcome');
      |
     */

    public function login() {
        $email = Input::get("email");
        $password = Input::get("password");
        $persona = Persona::login($email, $password)->first();
        if ($persona) {
            $token = Token::where("idpersonas", '=', $persona->idpersonas)->where("app_id", '=', Auth::user()->id)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->first();
            if (!$token) {
                $token = Token::create(array(
                            "idpersonas" => $persona->idpersonas,
                            "app_id" => Auth::user()->id,
                            "token" => Hash::make(uniqid() . $persona->idpersonas . str_random())
                ));
            }
            $persona->token = $token->token;
            //var_dump($persona);exit();
            return Response::json(array("persona" => $persona->toArray()));
        } else {
            return Response::json(array('error' => "Wrong Credentials"), 404);
        }
    }

    public function login_nocuenta() {
        $nocuenta = Input::get("nocuenta");
        $password = Input::get("password");
        $persona = Persona::loginAlumno($nocuenta, $password)->first();
        if ($persona) {
            $token = Token::where("idpersonas", '=', $persona->idpersonas)->where("app_id", '=', Auth::user()->id)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->first();
            if (!$token) {
                $token = Token::create(array(
                            "idpersonas" => $persona->idpersonas,
                            "app_id" => Auth::user()->id,
                            "token" => Hash::make(uniqid() . $persona->idpersonas . str_random())
                ));
            }
            $persona->token = $token->token;
            //var_dump($persona);exit();
            return Response::json(array("persona" => $persona->toArray()));
        } else {
            return Response::json(array('error' => "wrong credentials"), 404);
        }
    }

    public function getbytoken() {
        $token_input = Input::get("token");
        $token = Token::where("token", '=', $token_input)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->first();
        if ($token) {
            $persona = Persona::where('idpersonas', '=', $token->idpersonas)->first();
            $persona->token = $token->token;
            $token->touch();
            return Response::json(array("persona" => $persona->toArray()));
        } else {
            $error = array('error' => "Bad Token PersonaController ");
            if (Config::get('app.debug')) {
                $error['error'] = 'Bad Token at getByToken';
                $error['token'] = $token_input;
            }
            return Response::json($error, 403);
        }
    }

    public function getDocentes() {
        ini_set('memory_limit', '256M');
        ob_start("ob_gzhandler");
        $docentes = Persona::docentes()->get();

        return Response::json($docentes, 200);
    }

    public function keepalive() {
        $token_input = Input::get("token");
        $keep = Input::get("keep") == 1 ? true : false;
        $token = Token::where("token", '=', $token_input)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->select(DB::raw('*, TIMESTAMPDIFF(SECOND, TIMESTAMP(NOW()), TIMESTAMP(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE)) as secondsleft, NOW() as now'))->first();
        if ($token) {
            if ($keep) {
                $token->touch();
                $token = Token::where("token", '=', $token_input)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->select(DB::raw('*, TIMESTAMPDIFF(SECOND, TIMESTAMP(NOW()), TIMESTAMP(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE)) as secondsleft, NOW() as now,updated_at'))->first();
            }
            return Response::json(array('secondsLeft' => $token->secondsleft), 200);
        } else {
            $error = array('error' => "Bad Token - Keep alive");
            if (Config::get('app.debug')) {
                $error['error'] = 'Bad Token at keepAlive';
                $error['token'] = $token_input;
            }
            return Response::json($error, 403);
        }
    }

    public function googleLogin($action = null) {


        try {

            $client = new Google_Client();
            $client->setAuthConfigFile(storage_path() . "/credentials/client_secret.json");
            $client->setAccessType('online'); // default: offline
            $client->setRedirectUri('https://api.upa.edu.mx/1/oauth2callback/auth?hauth.done=Google');
            $client->setScopes(array(Google_Service_Drive::DRIVE_METADATA_READONLY, 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'));
            ;
            $client->setDeveloperKey('AIzaSyARhUTSZ3VQ2wYhgqnTlSacNDOycU8_V0o'); // API key
            //var_dump($client->getAccessToken());


            if (isset($_GET['logout'])) { // logout: destroy token
                unset($_SESSION['token']);
                die('Logged out.');
            }

            if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
                $client->authenticate($_GET['code']);
                $_SESSION['token'] = $client->getAccessToken();
                $service = new Google_Service_Plus($client);
                $userInfo = $service->people->get("me");
                $iemail = $userInfo['emails'][0]['value'];
                $files_service = new Google_Service_Drive($client);
                
                $pageToken = NULL;
                $i = 0;
                do {
                    try {
                        $parameters = array();
                        if ($pageToken) {
                            $parameters['pageToken'] = $pageToken;
                        }
                        $files = $files_service->files->listFiles($parameters);

                        $my_files = $files->getItems();

                        foreach ($my_files as $f) {
//                            echo $i++. " - " . $f->getTitle();
//                            echo "<br/>";
                        }

                        $pageToken = $files->getNextPageToken();
                    } catch (Exception $e) {
                        print "An error occurred: " . $e->getMessage();
                        $pageToken = NULL;
                    }
                } while ($pageToken);

                    $persona = Persona::byEmail($iemail)->first();
                    if ($persona) {
                        //var_dump($persona);
                        $token = Token::where("idpersonas", '=', $persona->idpersonas)->where("app_id", '=', 1)->whereRaw('(updated_at + INTERVAL ' . Config::get('app.session_timeout') . ' MINUTE) > NOW()')->first();
                        //var_dump($token);
                            $token = Token::create(array(
                                        "idpersonas" => $persona->idpersonas,
                                        "app_id" => 1,
                                        "token" => Hash::make(uniqid() . $persona->idpersonas . str_random())
                            ));
                        $persona->token = $token->token;
                        //var_dump($persona);exit();
                        
                        //return Response::json(array("usuario" => array("id" => $persona->idpersonas, "iemail" => $persona->iemail, "token" => $persona->token)));
                        return Redirect::to('https://intranet.upa.edu.mx/intra/validar_i_2.php?loginupp_i='.$persona->idpersonas.'&token='.$persona->token);
                     } else {
                        return Response::json(array('error' => "Wrong Credentials"), 404);
                    }
        }

            if (isset($_SESSION['token'])) { // extract token from session and configure client
                $token = $_SESSION['token'];
                $client->setAccessToken($token);
            }

            if (!$client->getAccessToken()) { // auth call to google
                $authUrl = $client->createAuthUrl();
                header("Location: " . $authUrl);
                die;
            }

            // $oauth = new Google_Service_Oauth2($client);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        //var_dump($profile);
    }

}
