<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
$env = "/api";
Route::group(array('prefix' => '1'), function()
{
    Route::get("oauth2callback/{auth?}", array("as"=>"googleAuth", "uses"=>"PersonaController@googleLogin"));
    Route::get('/status', 'CheckController@status');
	Route::get('/check/auth', array('before' => 'basic.once', 'uses'=>'CheckController@basic'));
	Route::post('/persona/login', array('before' => 'basic.once', 'uses'=>'PersonaController@login'));
	Route::post('/persona/login_alumno', array('before' => 'basic.once', 'uses'=>'PersonaController@login_nocuenta'));
	Route::post('/persona/getbytoken', array('before' => 'basic.once', 'uses'=>'PersonaController@getbytoken'));
	Route::post('/persona/getdocentes', array('before' => ['basic.once','by.token'], 'uses'=>'PersonaController@getDocentes'));
	Route::post('/persona/keepalive', array('before' => ['basic.once'], 'uses'=>'PersonaController@keepalive'));
	Route::post('/alumnos', array('before' => ['basic.once','by.token'], 'uses'=>'AlumnosController@getActivos'));
	Route::post('/grupos', array('before' => ['basic.once','by.token'], 'uses'=>'GruposController@getActivos'));
	Route::post('/alumnos/all', array('before' => ['basic.once','by.token'], 'uses'=>'AlumnosController@getAll'));
	Route::post('/alumnos/all/persona', array('before' => ['basic.once','by.token'], 'uses'=>'AlumnosController@getAllPersona'));
	Route::post('/cursos', array('before' => ['basic.once','by.token'], 'uses'=>'CursosController@getAll'));
	Route::post('/periodos', array('before' => ['basic.once','by.token'], 'uses'=>'PeriodosController@getAll'));
	Route::post('/grupos', array('before' => ['basic.once','by.token'], 'uses'=>'GruposController@getActivos'));
	Route::post('/niveles', array('before' => ['basic.once','by.token'], 'uses'=>'NivelesController@getAll'));
       
        Route::get('/curp/{curp}', array('before' => 'allow', 'uses'=>'RenapoController@getByCurp'));
        Route::get('/curp2/{curp}', array('before' => 'allow', 'uses'=>'RenapoController@getByCurp2'));
        
	Route::get('/calificaciones/{idcarrera}/{idperiodo}', 'CalificacionesController@get');
	Route::get('/alumnos', 'AlumnosController@getAll');
        
});

Route::get('/', function()
{
	return Response::json(array("api"=>"ok"));
});

Route::get('/create/pass', function(){
	//return Hash::make('DosmilCatorce.2014');
	return Hash::make('upa.loopa');
});

Route::filter('basic.once', function()
{
    return Auth::onceBasic('username');
});
