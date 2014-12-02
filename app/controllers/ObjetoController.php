<?php

class ObjetoController extends BaseController
{
	//public $layout = 'home.index';
	//siempre action_
	//$restful get y post
	protected $layout = 'layouts.layout';

    public function post_index()
    {
        $credentials = array(
        'username' => Input::get('email'),
        'password' => Input::get('password'));
        
        if(Auth::attempt($credentials))
        {
            return Redirect::to('objetos/index');
        }
        else
        {
            return Redirect::back()->with_input();
        }   
    }
	
	public function get_create()
	{
		return View::make('users.create');
	}
	
	public function CrearObjeto()
	{
		$obj = new Objeto;
		$obj->nombre_objeto = Input::json("nombre");
		$obj->fecha_perdida = Input::json("fecha");
		$obj->descripcion_objeto = Input::json("descripcion");
		$obj->latitud_objeto = Input::json("latitud");
		$obj->longitud_objeto = Input::json("longitud");
		$obj->tipoobjeto_id = Input::json("tipo_objeto");
		$obj->contacto_objeto = Input::json("contacto");
		$obj->recompensa_objeto = Input::json("recompensa");
		$obj->tipopublicacion_id = 1;
		$obj->estado = 0;
		$obj->usuario_id = Auth::user()->id;
		$obj->save();

		$LastInsertId = $obj->id;
		$valida = false;
		if(isset($LastInsertId)){
			$valida = true;
			Session::put('saveObjectId', $LastInsertId);
		}
		return Response::json(array('msg'=>$valida));

	}

	public function SeguirObjeto()
	{
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

	public function CargaImagen(){
		$id = Session::get('saveObjectId');
		$objeto = Objeto::find($id);
		$file = Input::file('file');
		$destinationPath = '../public/uploads';
		$extension =$file->getClientOriginalExtension(); 
		$filename = $objeto->id.'.'.$extension;
		$objeto->foto_objeto = $filename;
		$objeto->save();
		$upload_success = $file->move($destinationPath, $filename);
		return Response::json(array('msg'=>'ok'));
	}

	public function get_delete($id)
	{
		$objeto = Objeto::find($id);
		
		if(is_null($objeto))
		{
			return Redirect::to('objetos/listaobjetos');
		}
		
		$objeto->delete();
		return Redirect::to('objetos/listaobjetos');
	}
    
    public function get_listaobjetos()
	{
        $objetos = Objeto::all();
		return View::make('users.listausuarios')->with('objetos', $objetos);
	}
    
    
    
    public function get_update($user_id)
    {
		$user = User::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		
        //return $user->nombre;
		return View::make('users.update')->with('user', $user);
	}
    
    public function post_update($user_id)
    {
		$user = User::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		//echo Input::get('esadmin');
        $user->nombre = Input::get('nombre');
        $user->email = Input::get('email');
		$user->esadmin = Input::get('esadmin');
        
        if(Input::has('password'))
        {
			$user->password = Input::get('password');
		}
        
        $user->save();
        
        return Redirect::to('users/listausuarios');
	}
}