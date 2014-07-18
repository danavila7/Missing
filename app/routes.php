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

/************ RUTA DE HOME ***************/

Route::get('/', 'ObjetoController@ObjetosIndex');




/************ RUTA DE USUARIO ***************/

//Ruta Login
Route::post('login','HomeController@post_login');
//Ruta Logout
Route::get('logout','HomeController@get_logout');
//AUTH
Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});
//lista de usuarios
Route::get('usuarios/', 'UsuarioController@ListaUsuarios');
//mostrar profile de un usuario
Route::get('usuarios/{id}', 'UsuarioController@showProfile');


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
		$objeto->tipoobjeto_id = (Input::get('type'));;
		$objeto->latitud_objeto = $lat;
		$objeto->longitud_objeto = $long;
		$objeto->tipopublicacion_id = 1;
		$objeto->usuario_id = Auth::user()->id;
		
		$objeto->save();
		$LastInsertId = $objeto->id;
		
		//ingresar una imagen (mejorar el metodo)
		$file = Input::file('image');
		$destinationPath = 'uploads';
		// If the uploads fail due to file system, you can try doing public_path().'/uploads' 
		$filename = $objeto->nombre_objeto.'_'.$objeto->id;
		//$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension(); 
		$upload_success = Input::file('image')->move($destinationPath, $filename);
		if( $upload_success ) {
		echo "Objeto ".$objeto->nombre_objeto." tipo ".$objeto->tipoobjeto_id." Creado con exito.";
		}else{
		echo "Quedo la caga";	
		}
		}
	}
});
