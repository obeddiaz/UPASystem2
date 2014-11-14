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
            $this->client = new Buzz\Client\FileGetContents();
            //$this->client = new Buzz\Client\Curl();
            $this->client->setTimeout(60);
            $this->response = new Buzz\Message\Response();
            Log::useFiles($this->logFile, (App::environment('local', 'staging') ? 'debug' : 'critical'));
        }
        //$this->client = $buzz;
    }

    public function login($email, $password) {
        $content = array("email" => $email, "password" => $password);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/persona/login");
        $this->response = $this->send($this->client, $this->request);
        Log::info($this->response);
        return json_decode($this->response->getContent(), true);
    }

    public function new_request($type, $service, $datos = null) {
        $this->request = new Request($type);
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        if ($datos) {
            $content = array($datos);
            $this->request->setContent(http_build_query($content));
        }
        $this->request->fromUrl($this->url . $service);
        $this->response = $this->send($this->client, $this->request);
        Log::info($this->response);
        return json_decode($this->response->getContent(), true);
    }

    public function getPersonaByToken($token) {
        $content = array("token" => $token);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        var_dump(http_build_query($content));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/persona/getbytoken");
        $this->response = $this->send($this->client, $this->request);

        return json_decode($this->response->getContent(), true);
        // Do things with data, etc etc
    }

    public function getAlumnosActivos() {
        $content = array("token" => User::$token);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/alumnos");
        $this->response = $this->send($this->client, $this->request);

        return json_decode($this->response->getContent(), true);
        // Do things with data, etc etc
    }

    public function getAllPeriodos() {
        $content = array("token" => User::$token);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/periodos");
        $this->response = $this->send($this->client, $this->request);

        return json_decode($this->response->getContent(), true);
        // Do things with data, etc etc
    }

    public function getDocentes() {
        $content = array("token" => User::$token);
        $this->request = new Request("POST");
        $this->request->addHeader('Authorization: Basic ' . base64_encode($this->name . ':' . $this->password));
        $this->request->setContent(http_build_query($content));
        $this->request->fromUrl($this->url . "/persona/getdocentes");
        $this->response = $this->send($this->client, $this->request);

        return json_decode($this->response->getContent(), true);
        // Do things with data, etc etc
    }

    public function status() {
        $this->request = new Request("GET");
        //$this->request->addHeader('Authorization: Basic '.base64_encode($this->name.':'.$this->password));
        $this->request->fromUrl($this->url . "/status");
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
