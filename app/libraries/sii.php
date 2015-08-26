<?php

use Buzz\Client\BatchClientInterface;
use Buzz\Client\ClientInterface;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use Buzz\Client\MultiCurl;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Request;
use Buzz\Message\RequestInterface;
use Buzz\Message\Response;

//$request->addHeader('Authorization: Basic '.base64_encode($username.':'.$password));
class Sii {

    private $url;
    private $name;
    private $password;
    private $client;
    private $request;
    private $response;
    private $logFile;
    private $token;
    private $minutesToCache = 20;

    public function __construct($config = null) {
        //var_dump($config["url"]);exit
        if (!$config) {
            $config = Config::get('api');
        }
        $this->logFile = base_path() . '/logs/sii-api.log';
        if (!is_array($config)) {
            throw new Exception("Error: no configuration for Sii");
        } elseif (!array_key_exists("url", $config)) {
            throw new Exception("Error: no url was found");
        } elseif (!array_key_exists("name", $config)) {
            throw new Exception("Error: No name for app was found");
        } elseif (!array_key_exists("password", $config)) {
            throw new Exception("Error: No password for app was found");
        } else {
            $this->url = $config["url"];
            $this->name = $config["name"];
            $this->password = $config["password"];
            $curr_user = Session::get('user');
            $this->token = $curr_user['persona']['token'];
            //$this->client = new Buzz\Client\FileGetContents();
            $this->client = new Buzz\Client\Curl();
            $this->client->setTimeout(60);
            $this->response = new Buzz\Message\Response();

            Log::useFiles($this->logFile, (App::environment('local', 'staging') ? 'debug' : 'critical'));
        }
        //$this->client = $buzz;
    }

    public function login($user, $password) {

        $parametros = array(
            'user'=>$user
        );
        $reglas = array(
            'user'=> 'required|email'
        );
        $email = Validator::make($parametros,$reglas);

        if (!$email->fails())
        {
            $content = array("email" => $user, "password" => $password);
            $this->request = new Request("POST");
            $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
            $this->request->setContent(http_build_query($content));
            $this->request->fromUrl($this->url . "/persona/login");
            $this->response = $this->send($this->client, $this->request);
            Log::info($this->response);
        } else {
            
            $content = array("nocuenta" => $user, "password" => $password);
            $this->request = new Request("POST");
            $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
            $this->request->setContent(http_build_query($content));
            $this->request->fromUrl($this->url . "/persona/login_alumno");
            $this->response = $this->send($this->client, $this->request);
            
            #$this->response=array('error' => true);
            Log::info($this->response);
        }
        
        //$user_token=  json_decode($this->response->getContent());
        //User::$token = $user_token->persona->token;

        return json_decode($this->response->getContent(), true);
    }

    public function orderParamsToKeyCache($datos) {
        ksort($datos);
        foreach ($datos as $key => $value) {
            if (is_array($value)) {
                $datos[$key] = $this->orderParamsToKeyCache($value);
            } else {
                sort($datos);
            }
        }
        return $datos;
    }

    public function new_request($type, $service, $datos = null) {
        $this->request = new Request($type);
        if ($datos && is_array($datos)) {
            $datos = $this->orderParamsToKeyCache($datos);
        }
        $datosToCache['datos'] = $datos;
        $datosToCache['service'] = $service;
        $keyToService = md5(json_encode($datosToCache));

        if (Cache::has($keyToService)) {
            $response = Cache::get($keyToService);
        } else {
            $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
            $datos['token'] = $this->token;
            if ($datos) {
                $this->request->setContent(http_build_query($datos));
            }
            $this->request->fromUrl($this->url . $service);
            $this->response = $this->send($this->client, $this->request);
            Log::info($this->response);
            $response = json_decode($this->response->getContent(), true);
            #var_dump($response['error']);die();
            if (!isset($response['error'])){
                Cache::put($keyToService, $response, $this->minutesToCache);
            } else {
                $respuesta = json_encode(array('error' => true,'message'=> $response['error'],'response'=>''));
                #$final_response = Response::make($respuesta, 200);
                #$final_response->header('Content-Type', "application/json; charset=utf-8");
                header('Content-Type: application/json; charset=utf-8'); 
                echo  $respuesta;
                die();
            }
        }
        return $response;
    }

    public function getPersonaByToken($token) {
        $content = array("token" => $token);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/persona/getbytoken");
        $this->response = $this->send($this->client, $this->request);

        return json_decode($this->response->getContent(), true);
        // Do things with data, etc etc
    }

    public function keepAlive($token, $keep = false) {
        $content = array("token" => $token, 'keep' => $keep);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/persona/keepalive");
        $this->response = $this->send($this->client, $this->request);
        Log::info(print_r($this->response, true));
        return array('response' => json_decode($this->response->getContent(), true), 'statuscode' => $this->response->getStatusCode());
    }

    private function send(ClientInterface $client, RequestInterface $request) {
        $response = new Response();
        $client->send($request, $response);


        if ($client instanceof BatchClientInterface) {
            $client->flush();
        }
        Log::info(print_r($request, true));
        return $response;
    }

}
