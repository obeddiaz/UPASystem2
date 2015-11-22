<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request) {
    if (Request::getMethod() == "OPTIONS") {
        $headers = array(
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization, X-Auth-Token',);
        return Response::make('', 200, $headers);
    }
});

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('permisos', function() {
  $user=Session::get('user');
  if (intval($user['persona']['alumno'])==1) {
    return json_encode(array('error' => true, 'message' => 'Este Usuario no tiene permisos para consumir este servicio','respuesta'=>'','error_type'=>1));
  }
});

Route::filter('auth', function()
{
  $user=Session::get('user');
	$sii= new Sii();
  if (!Session::has('user')) {
          return response::json(['error' => true, 'message' => 'Usuario no autenticado','respuesta'=>'','error_type'=>0]);
  } else {
    $keepalive=$sii->keepAlive($user["persona"]["token"],1);
    if (isset($keepalive['response']['error']))  {
      if ($keepalive['response']['error']=='Bad Token at keepAlive') {
        $respuesta = array('error' => true, 'message' => 'Usuario no autenticado','respuesta'=>'','error_type'=>0);
        $final_response = Response::make($respuesta, 200);
        $final_response->header('Content-Type', "application/json; charset=utf-8");

        return $final_response;
      }    
    }
  }
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('auth.token', function($route, $request)
{
    $payload = $request->header('X-Auth-Token');

    $userModel = Sentry::getUserProvider()->createModel();

    $user =  $userModel
              ->where('api_token',$payload)
              ->select(DB::raw('updated_at + INTERVAL ' . Config::get('app.token_life') . ' MINUTE as limit_life'),'users.*')
              ->first();

    if(!$payload || !$user || ($user['limit_life']<=date('Y-m-d H:i:s'))) {

        $response = Response::json([
            'error' => true,
            'message' => 'Not authenticated',
            'code' => 401],
            401
        );

        $response->header('Content-Type', 'application/json');
    return $response;
    } else {
      $date=date('Y-m-d H:i:s');
      DB::table('users')
      ->where('api_token',$user['token'])
      ->update(array('updated_at' => $date));
    }

});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
