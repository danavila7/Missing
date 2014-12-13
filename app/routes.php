<?php
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphLocation;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphSessionInfo;
session_start();
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

/************ RUTA RESTFULL ***************/
//USUARIOS
Route::get('/validalogin/{email}/{pass}','RestUsuarioController@ValidaLogin');







//MISSING


//Obtiene los missing por locacion
Route::get('datosmissingproximos/{lng}/{lat}/{num}', 'RestHomeController@ObtieneMissingProximos');
Route::get('datosmissingmios/{id}', 'RestHomeController@ObtieneMissingPorUsuarioId');


















/************ FIN RUTA RESTFULL ***************/






/************ RUTA DE MISSING ***************/


//MISSING
Route::get("/", function()
{
    return View::make("home");
});
 //Obtiene  todos los objetos
Route::get("obtenerTodosMissing/", 'HomeController@ObtenerTodosMissing');
 //Obtiene los ultimos Missing
Route::get("obtenerObjetosMapaProximos/{lat}/{lng}/{radio}", 'HomeController@ObtenerTodosMissingMapaProximos');
 //Obtiene los ultimos Missing por Filtro
Route::get("obtenerobjetosporfiltro/{objeto}/{animal}/{persona}/{lat}/{lng}/{radio}", 'HomeController@ObtenerTodosMissingPorFiltro');
//Obtiene los missing por Usuario
Route::get("obtenerMissingPorUsuario", 'HomeController@ObtenerMissingPorUsuario');
//Obtiene los missing seguidos por Usuario
Route::get("obtenerMissingSeguidosPorUsuario", 'HomeController@ObtenerMissingSeguidosPorUsuario');
//Muestra datos de un Objeto
Route::get('datosMissing/{id}', 'HomeController@ObtieneMissingPorId');
//Ruta Crear Objeto
Route::post('/createObject','ObjetoController@CrearObjeto');
//Ruta Crear Objeto
Route::post('/creaobjetosimple','ObjetoController@CreaObjetoSimple');
//Ruta Seguir Objeto
Route::post('/seguirObjeto','ObjetoController@SeguirObjeto');
//Ruta Dejar Seguir Objeto
Route::post('/dejarSeguirObjeto','ObjetoController@DejarSeguirObjeto');
//Ruta Carga Imagen
Route::post('/cargaImagen','ObjetoController@CargaImagen');




//USUARIO
//Ruta Crear Usuario
Route::post('/createUser','UsuarioController@CreateUser');
//Ruta Crear Usuario Facebook
Route::post('/createUserEsCreado','UsuarioController@CreateUserEsCreado');
//Ruta Login
Route::post('/login','UsuarioController@post_login');
//Ruta Logout
Route::get('/logout','UsuarioController@get_logout');
//Esta logeado?
Route::get('/isLoggedIn','UsuarioController@isLoggedIn');
//Crear usuario logeado por facebook
Route::get('/getUser','UsuarioController@GetUser');




//AUTH
Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

//lista de usuarios
Route::get('usuarios/', 'UsuarioController@ListaUsuarios');
//mostrar profile de un usuario
Route::get('usuarios/{id}', 'UsuarioController@showProfile');

//facebook
Route::get('login/fb', function() {
	//return Config::get('facebook.secret');
	//Config::get('facebook.secret')
	$url = URL::to('/login/fb/callback');
	FacebookSession::setDefaultApplication(Config::get('facebook.appId'), Config::get('facebook.secret'));
	//$facebook = new Facebook(Config::get('facebook'));
	//$helper = new FacebookRedirectLoginHelper('your redirect URL here');
    $helper = new FacebookRedirectLoginHelper($url);
    $loginUrl = $helper->getLoginUrl();
    return Redirect::to($loginUrl);
});

Route::get('/login/fb/callback', function(){
$url = URL::to('/login/fb/callback');
FacebookSession::setDefaultApplication(Config::get('facebook.appId'), Config::get('facebook.secret'));
$helper = new FacebookRedirectLoginHelper($url);
try {
    $session = $helper->getSessionFromRedirect();
    //return "sessq->".$session;
} catch(FacebookRequestException $ex) {
    // When Facebook returns an error
    return 'facebook error '.$ex->getMessage();
} catch(\Exception $ex) {
	//return 'facebook error '.$ex->getMessage();
    // When validation fails or other local issues
    // return 'validation fails '.$ex->getMessage();
}
if(isset($_SESSION['token'])){
	$session  = new FacebookSession($_SESSION['token']);
	try{
		$session->Validate(Config::get('facebook.appId'), Config::get('facebook.secret'));

	}catch(FacebookAuthorizationException $ex){
		$session = '';
	}
}

if ($session) {
	$_SESSION['token'] = $session->getToken();
	$request = new FacebookRequest($session, 'GET', '/me?fields=id,first_name,email,gender,birthday,picture.url');
	$response = $request->execute();
	
	$array = $response->getResponse();
	//Foto
	$pic = $array->picture->data->url;

// Get the response typed as a GraphUser
$user = $response->getGraphObject(GraphUser::className());
// or convert the base object previously accessed
  $email = $response->getGraphObject()->getProperty('email');
  $sex = $response->getGraphObject()->getProperty('gender');
  $bday = $response->getGraphObject()->getProperty('birthday');
// Get the response typed as a GraphLocation
$loc = $response->getGraphObject(GraphLocation::className());

//debo preguntar si existe el id de facebook del usuario
$perfil = Perfiles::where('idFacebook', '=', $user->getId())->first();
if(isset($perfil)){
	 $usuario = Usuario::find($perfil->usuario_id);
	 Auth::loginUsingId($usuario->id);
	 return Redirect::to('');
}else{
	$usuario = new Usuario;
		
	$usuario->usuario = $user->getFirstName().' '.$user->getMiddleName().' '.$user->getLastName();
	$usuario->email = $email;
	$usuario->password = '';

	$usuario->save();
	$LastInsertId = $usuario->id;

	$perfil = new Perfiles;
	$perfil->usuario_id = $LastInsertId;
	$perfil->username = $user->getFirstName();
	$perfil->idFacebook =  $user->getId();
	$perfil->link = $user->getLink();
	$perfil->birthday = $user->getBirthday();
	$perfil->avatar_path = $pic;
	$perfil->genero = $sex;
		
	$perfil->save();
	Auth::loginUsingId($LastInsertId);
	return Redirect::to('');	
}
return Redirect::to('');
}
return Redirect::to('');
});


/************ RUTA DE OBJETOS ***************/

Route::get('objetos/', function()
{
	
	//Create a new DOMDocument object
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers"); //Create new element node
	$parnode = $dom->appendChild($node); //make the node show up
	
	// Select all the rows in the markers table  DB::table('objetos')->where('tipoobjeto_id', '2');
	$objetos = Objeto::where('estado', '=', 1)->get();


	if (!$objetos) {
		header('HTTP/1.1 500 Error: no se encontraron objetos!');
		exit();
	}
	
	//set document header to text/xml
	header("Content-type: text/xml");
	
	// Iterate through the rows, adding XML nodes for each
	foreach($objetos as $objeto)
	{	
		$obj = new Objeto;
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", $objeto->id);
		$newnode->setAttribute("usuario_id", $objeto->usuario_id);
		$newnode->setAttribute("name",$objeto->nombre_objeto);
		$newnode->setAttribute("lat", $objeto->latitud_objeto);
		$newnode->setAttribute("lng", $objeto->longitud_objeto);
		$newnode->setAttribute("desc", $objeto->descripcion_objeto);
		$newnode->setAttribute("type", $obj->GetType($objeto->tipoobjeto_id));
		$newnode->setAttribute("typeid", $objeto->tipoobjeto_id);
		if(isset($objeto->foto_objeto) && $objeto->foto_objeto != ""){
			$newnode->setAttribute("path", $objeto->foto_objeto);
		}else{
			$newnode->setAttribute("path", "default.png");
		}

		
	}
	
	echo $dom->saveXML();
	
	
	
});

Route::post('borrarObjeto/', function(){
	if(Request::ajax()){
		
		$id	=  Input::get('id');
		$objeto = Objeto::find($id);
		//0 borrado, 1 activo, 2 encontrado
		$objeto->estado = 0;
		$objeto->save();
		echo $objeto->nombre_objeto;
		}
});

Route::post('Crearobjetos/', function()
{
});
