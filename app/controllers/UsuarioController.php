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

    public function isLoggedIn()
    {
        if(Auth::check()){
            $perfil = DB::table('perfiles')
                    ->where('usuario_id', Auth::user()->id)
                    ->first();
            return Response::json(array('isloggin'=>Auth::user()->usuario,
                'esCreado'=>Auth::user()->esCreado,'email'=>Auth::user()->email,'avatar'=>$perfil->avatar_path));
        }else{
        	return Response::json(array('isloggin'=>'false'));
        }   
    }
    
    public function get_logout()
    {
        Auth::logout();
        return Response::json(array('msg'=>'Logout'));
    }

    public function get_getUser(){
        if(Auth::check()){
            $user = Usuario::find(Auth::user()->id);
            $nombre = $user->usuario;
            $email = $user->email;
            $esCreado = $user->esCreado;
            $id = $user->id;
            return Response::json(array('msg'=>'true','nombre'=>$nombre,'email'=>$email, 'esCreado'=>$esCreado));
        }else{
            return Response::json(array('msg'=>'false'));
        }  
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
		$users = Usuario::all();
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
	
	public function post_create_user()
	{

		$user = Usuario::find(Auth::user()->id);
		$user->password = Hash::make(Input::json("password"));
        $user->realpassword = Input::json("password");
        $user->esCreado = 1;
		$user->save();
		
         return Response::json(array('msg'=>Auth::user()->usuario));
	}
	
	public function get_delete($user_id)
	{
		$user = Usuario::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		
		$user->delete();
		return Redirect::to('users/listausuarios');
	}
    
    public function get_listausuarios()
	{
        $users = Usuario::all();
		return View::make('users.listausuarios')->with('users', $users);
	}
    
    
    
    public function get_update($user_id)
    {
		$user = Usuario::find($user_id);
		
		if(is_null($user))
		{
			return Redirect::to('users/listausuarios');
		}
		
        //return $user->nombre;
		return View::make('users.update')->with('user', $user);
	}
    
    public function post_update($user_id)
    {
		$user = Usuario::find($user_id);
		
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