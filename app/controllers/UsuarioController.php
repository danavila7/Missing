<?php

class UsuarioController extends BaseController
{
	//siempre action_
	//$restful get y post
	protected $layout = 'layouts.layout';
    
    /**
     * Show the profile for the given user.
     */

    public function get_login()
    {
        return View::make('auth.login');
    }
    
    public function post_login()
    {
        $credentials = array(
        'usuario' => Input::json('email'),
        'password' => Input::json('password'));
        if(Auth::attempt($credentials)){
            return Response::json(array('flash'=>Auth::user()->usuario));
        }else{
        	return Response::json(array('flash'=>'Login Invalido!'),500);
        }   
    }
    
    public function get_logout()
    {
        Auth::logout();
        return Response::json(array('msg'=>'Logout'));
    }


    public function showProfile($id)
    {
        $user = Usuario::find($id);

        return View::make('usuarios.profile', array('user' => $user));
    }
    
    public function ListaUsuarios()
    {
        $usuarios = Usuario::all();
        return View::make('usuarios.listausuarios', array('usuarios'=>$usuarios));
    }
    
	public function get_index()
	{
		$users = User::all();
		return View::make('users.index')->with('users', $users);
	}
    
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
	
	public function post_create()
	{
		$user = new User;
		
		$user->usuario = Input::get("usuario");
		$user->email = Input::get("email");
		$user->password = Input::get("password");
		
		
		$user->save();
		
        //return "El formulario se creo.";
		return Redirect::to('users/listausuarios');
	}
	
	public function get_delete($user_id)
	{
		$user = User::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		
		$user->delete();
		return Redirect::to('users/listausuarios');
	}
    
    public function get_listausuarios()
	{
        $users = User::all();
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