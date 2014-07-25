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

//Route::get('/', 'ObjetoController@ObjetosIndex');
Route::get('diseño/', 'ObjetoController@ObjetosDiseñoIndex');



/************ RUTA DE USUARIO ***************/

//angular JS

Route::get("/", function()
{
    return View::make("home");
});
 
Route::get("obtenerObjetos", function()
{
    $objetos = Objeto::all();
    return Response::json(array(
        "objetos"=>$objetos
    ));
});

//Ruta Login
Route::post('/login','UsuarioController@post_login');
//Ruta Logout
Route::get('/logout','UsuarioController@get_logout');

//angular JS FIN


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
	//Config::get('facebook.secret')
	$url = URL::to('/login/fb/callback');
	FacebookSession::setDefaultApplication('1476123785965298', '367198c9c03672b99e5b206e7756ddfc');
	//$facebook = new Facebook(Config::get('facebook'));
	//$helper = new FacebookRedirectLoginHelper('your redirect URL here');
    $helper = new FacebookRedirectLoginHelper($url);
    $loginUrl = $helper->getLoginUrl();
    return Redirect::to($loginUrl);
});

Route::get('/login/fb/callback', function(){
$url = URL::to('/login/fb/callback');
FacebookSession::setDefaultApplication('1476123785965298', '367198c9c03672b99e5b206e7756ddfc');
$helper = new FacebookRedirectLoginHelper($url);
try {
    $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
    // When Facebook returns an error
    return 'facebook error '.$ex->getMessage();
} catch(\Exception $ex) {
    // When validation fails or other local issues
    // return 'validation fails '.$ex->getMessage();
}
if(isset($_SESSION['token'])){
	$session  = new FacebookSession($_SESSION['token']);

	try{
		$session->Validate('1476123785965298', '367198c9c03672b99e5b206e7756ddfc');

	}catch(FacebookAuthorizationException $ex){
		$session = '';

	}

}
if ($session) {
	$_SESSION['token'] = $session->getToken();
	$request = new FacebookRequest($session, 'GET', '/me');
	$response = $request->execute();

// Get the response typed as a GraphUser
$user = $response->getGraphObject(GraphUser::className());
// or convert the base object previously accessed
// $user = $object->cast(GraphUser::className());

// Get the response typed as a GraphLocation
$loc = $response->getGraphObject(GraphLocation::className());
return "Login User ->".$user->getName()." pais ".$loc->getCountry();
}
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
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("id", $objeto->id);
		$newnode->setAttribute("name",$objeto->nombre_objeto);
		$newnode->setAttribute("address", $objeto->descripcion_objeto);
		$newnode->setAttribute("lat", $objeto->latitud_objeto);
		$newnode->setAttribute("lng", $objeto->longitud_objeto);
		$newnode->setAttribute("type", $objeto->tipoobjeto_id);
		$newnode->setAttribute("path", $objeto->nombre_objeto."_".$objeto->id.".jpg");
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
		$objeto->usuario_id = Auth::user()->id;
		
		$objeto->save();
		$LastInsertId = $objeto->id;
		
		//ingresar una imagen (mejorar el metodo)
		//$file = Input::file('image');
		$destinationPath = 'uploads';
		// If the uploads fail due to file system, you can try doing public_path().'/uploads' 
		$filename = $objeto->nombre_objeto.'_'.$objeto->id;
		//$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension(); 
		//$upload_success = Input::file('image')->move($destinationPath, $filename);
		if( $upload_success ) {
		echo "Objeto ".$objeto->nombre_objeto." tipo ".$objeto->tipoobjeto_id." Creado con exito.";
		}else{
		echo "Quedo la caga";	
		}
		}
	}
});
