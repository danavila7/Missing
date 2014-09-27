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
	
	public function post_create_object()
	{
		$obj = new Objeto;
		
		$obj->nombre_objeto = Input::json("nombre");
		//$obj->fecha_perdida = Input::json("fecha");
		$obj->descripcion_objeto = Input::json("descripcion");
		$obj->latitud_objeto = Input::json("latitud");
		$obj->longitud_objeto = Input::json("longitud");
		//$obj->foto_objeto = Input::json("foto");
		$obj->tipoobjeto_id = Input::json("tipo_objeto");
		$obj->contacto_objeto = Input::json("contacto");
		$obj->recompensa_objeto = Input::json("recompensa");
		$obj->tipopublicacion_id = 1;
		$obj->estado = 0;

		$obj->save();


	}
	
	public function get_delete($user_id)
	{
		$user = Objeto::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		
		$user->delete();
		return Redirect::to('users/listausuarios');
	}
    
    public function get_listaobjetos()
	{
        $users = Objeto::all();
		return View::make('users.listausuarios')->with('users', $users);
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