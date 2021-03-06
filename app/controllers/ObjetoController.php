<?php

class ObjetoController extends BaseController
{
	//$restful get y post
	protected $layout = 'layouts.layout';

    public function post_index(){
        $credentials = array(
        'username' => Input::get('email'),
        'password' => Input::get('password'));
        if(Auth::attempt($credentials)){
            return Redirect::to('objetos/index');
        }
        else{
            return Redirect::back()->with_input();
        }   
    }
	

	public function get_create(){
		return View::make('users.create');
	}

	public function CreaObjetoSimple(){
		if(Request::ajax()){
			$objeto = new Objeto;
			list($lat, $long) = explode(",", (Input::get('latlang')));
			
			
			$objeto->nombre_objeto = (Input::get('name'));
			$objeto->descripcion_objeto = (Input::get('desc'));
			$objeto->direccion_objeto = (Input::get('address'));
			$objeto->tipoobjeto_id = (Input::get('type'));
			$objeto->latitud_objeto = $lat;
			$objeto->longitud_objeto = $long;
			$objeto->tipopublicacion_id = 1;
			$objeto->foto_objeto = '';
			$objeto->usuario_id = Auth::user()->id;
			$objeto->estado = 0;
			$objeto->foto_objeto = 'default.png';
			
			$objeto->save();
			$LastInsertId = $objeto->id;

			$imgmap = new Imagenes;
			$imgmap->to_id = $LastInsertId;
			$imgmap->url = "http://maps.googleapis.com/maps/api/staticmap?center=".Input::json("latitud").",".Input::json("longitud")."&zoom=16&size=200x200&sensor=false";
			$imgmap->save();

			$imgstreetv = new Imagenes;
			$imgstreetv->to_id = $LastInsertId;
			$imgstreetv->url = 'http://maps.googleapis.com/maps/api/streetview?size=300x300&location='.Input::json("latitud").','.Input::json("longitud").'&fov=90&heading=235&pitch=10&sensor=false';
			$imgstreetv->save();

			return Response::json(array('id'=>$LastInsertId));
		}
	}


	
	public function CrearObjeto(){
		$obj = new Objeto;
		$obj->nombre_objeto = Input::json("nombre");
		$obj->fecha_perdida = Input::json("fecha");
		$obj->descripcion_objeto = Input::json("descripcion");
		$obj->latitud_objeto = Input::json("latitud");
		$obj->longitud_objeto = Input::json("longitud");
		$obj->tipoobjeto_id = Input::json("tipo_objeto");
		$obj->contacto_objeto = Input::json("contacto");
		$obj->recompensa_objeto = Input::json("recompensa");
		$obj->direccion_objeto = Input::json("direccion_objeto");
		$obj->tipopublicacion_id = 1;
		$obj->estado = 0;
		$obj->usuario_id = Auth::user()->id;
		$obj->save();

		$LastInsertId = $obj->id;

		$imgmap = new Imagenes;
		$imgmap->to_id = $LastInsertId;
		$imgmap->tipo = 1;
		$imgmap->url = "http://maps.googleapis.com/maps/api/staticmap?center=".Input::json("latitud").",".Input::json("longitud")."&zoom=16&size=200x200&sensor=false";
		$imgmap->save();

		$imgstreetv = new Imagenes;
		$imgstreetv->to_id = $LastInsertId;
		$imgstreetv->tipo = 2;
		$imgstreetv->url = 'http://maps.googleapis.com/maps/api/streetview?size=300x300&location='.Input::json("latitud").','.Input::json("longitud").'&fov=90&heading=235&pitch=10&sensor=false';
		$imgstreetv->save();

		$valida = false;
		if(isset($LastInsertId)){
			$valida = true;
			Session::put('saveObjectId', $LastInsertId);
		}
		return Response::json(array('msg'=>$valida));

	}

	public function SeguirObjeto(){
		$sig = new Siguiendo;
		$sig->usuario_id = Auth::user()->id;
		$sig->objeto_id = Input::json("objeto_id");
		$sig->save();

		$LastInsertId = $sig->id;
		$valida = false;
		if(isset($LastInsertId)){
			$valida = true;
		}
		return Response::json(array('msg'=>Input::json("objeto_id")));

	}

	public function DejarSeguirObjeto(){
		$siguiendo = Siguiendo::where('usuario_id', Auth::user()->id)
                    ->where('objeto_id',Input::json("objeto_id"))
                    ->firstOrFail();
		$siguiendo->delete();
		return Response::json(array('msg'=>'ok'));
	}

	public function CargaImagen(){
		if (Input::hasFile('file')){
			$id = Session::get('saveObjectId');
			$objeto = Objeto::find($id);
			$file = Input::file('file');
			$destinationPath = '../public/uploads';
			$extension =$file->getClientOriginalExtension(); 
			$filename = $objeto->id.'.'.$extension;
			$objeto->foto_objeto = $filename;
			$objeto->save();
			$upload_success = $file->move($destinationPath, $filename);
			
			$url = asset('/');
			$img = new Imagenes;
			$img->to_id = $id;
			$img->tipo = 3;
			$img->url = $url.'public/uploads/'.$filename;
			$img->save();
		}
		return Response::json(array('msg'=>'ok'));
	}

	public function BorrarObjeto(){
		if(Request::ajax()){
		$id	=  Input::get('id');
		$objeto = Objeto::find($id);
		//0 normal, 1 eliminado, 2 encontrado
		$objeto->estado = 1;
		$objeto->save();
		echo $objeto->nombre_objeto;
		}
	}

	public function get_delete($id){
		$objeto = Objeto::find($id);
		
		if(is_null($objeto)){
			return Redirect::to('objetos/listaobjetos');
		}
		$objeto->delete();
		return Redirect::to('objetos/listaobjetos');
	}
    
    public function get_listaobjetos(){
        $objetos = Objeto::all();
		return View::make('users.listausuarios')->with('objetos', $objetos);
	}
    
    
    
    public function get_update($user_id){
		$user = User::find($user_id);
		if(is_null($user)){
			return Redirect::to('users/listausuarios');
		}
		return View::make('users.update')->with('user', $user);
	}
    
    public function post_update($user_id){
		$user = User::find($user_id);
		
		if(is_null($user)){
			return Redirect::to('users/listausuarios');
		}
		
        $user->nombre = Input::get('nombre');
        $user->email = Input::get('email');
		$user->esadmin = Input::get('esadmin');
        
        if(Input::has('password')){
			$user->password = Input::get('password');
		}
        
        $user->save();
        
        return Redirect::to('users/listausuarios');
	}
}