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

/************ RUTA DE HOME ***************/



/************ RUTA DE USUARIO ***************/

Route::get("/", function()
{
    return View::make("home");
});
 //Obtiene los ultimos Missing
Route::get("obtenerObjetos", 'HomeController@ObtenerMissing');
//Obtiene los missing por Usuario
Route::get("obtenerMissingPorUsuario", 'HomeController@ObtenerMissingPorUsuario');
//Muestra datos de un Objeto
Route::get('datosMissing/{id}', 'HomeController@ObtieneMissingPorId');

//Ruta Login
Route::post('/login','UsuarioController@post_login');
//Ruta Logout
Route::get('/logout','UsuarioController@get_logout');
//Esta logeado?
Route::get('/isLoggedIn','UsuarioController@isLoggedIn');


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
//si existe
$perfil = Perfiles::where('username', '=', $user->getId())->first();
if(isset($perfil)){
	 $user = Usuario::find($perfil->usuario_id);
	 Auth::loginUsingId($user->id);
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
//obtengo el id y voy a login para iniciar session
//si no existe guardo los datos en el perfil e inicio session
//(String)$user.getProperty("email")
//return "Login User ->".$user->getMiddleName();



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
	
	// Select all the rows in the markers table
	$objetos = Objeto::all();
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
		$newnode->setAttribute("name",$objeto->nombre_objeto);
		$newnode->setAttribute("address", $objeto->descripcion_objeto);
		$newnode->setAttribute("lat", $objeto->latitud_objeto);
		$newnode->setAttribute("lng", $objeto->longitud_objeto);
		$newnode->setAttribute("type", $obj->GetType($objeto->tipoobjeto_id));
		$newnode->setAttribute("typeid", $objeto->tipoobjeto_id);
		if(isset($objeto->foto_objeto) || $objeto->foto_objeto != ""){
			$newnode->setAttribute("path", $objeto->foto_objeto);
		}else{
			$newnode->setAttribute("path", "default.png");
		}

		
	}
	
	echo $dom->saveXML();
	
	
	
});

Route::post('objetos/', function()
{
	if(Request::ajax()){
		
		if(Input::get('del'))
		{
			//eliminar un objeto (mejorar este metodo)
			$mLatLang	= explode(',', Input::get('latlang'));
			$objeto = Objeto::where('latitud_objeto','=', $mLatLang[0], 'AND')
			->where('longitud_objeto', '=', $mLatLang[1])->first();
			
					
			$objeto->delete();
			echo "Eliminado con exito.";
		}else{
		
		$objeto = new Objeto;
		list($lat, $long) = explode(",", (Input::get('latlang')));
		
		
		$objeto->nombre_objeto = (Input::get('name'));
		$objeto->descripcion_objeto = (Input::get('address'));
		$objeto->tipoobjeto_id = (Input::get('type'));
		$objeto->latitud_objeto = $lat;
		$objeto->longitud_objeto = $long;
		$objeto->tipopublicacion_id = 1;
		$objeto->foto_objeto = '';
		$objeto->usuario_id = Auth::user()->id;
		
		$objeto->save();
		$LastInsertId = $objeto->id;
		echo "Objeto ".$objeto->nombre_objeto." tipo ".$objeto->tipoobjeto_id." Creado con exito.";
		/*
		//ingresar una imagen (mejorar el metodo)
		//$file = Input::file('image');
		$destinationPath = 'uploads';
		// If the uploads fail due to file system, you can try doing public_path().'/uploads' 
		$filename = $objeto->nombre_objeto.'_'.$objeto->id;
		//$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension(); 
		//$upload_success = Input::file('image')->move($destinationPath, $filename);
		if( $upload_success ) {
		
		}else{
		echo "Quedo la caga";	
		}*/
		}
	}
});
