<?php

class ObjetoController extends BaseController
{
	//public $layout = 'home.index';
	//siempre action_
	//$restful get y post
	protected $layout = 'layouts.layout';

	public function ObjetosIndex(){
		return "hola mundo 1";
		/*$objetos = DB::table('objetos')
						->orderBy('created_at', 'desc')
						->take(5)
						->get();
		return View::make('objetos.index')->with('objetos', $objetos);*/
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
		
		$user->nombre = Input::get("nombre");
		$user->email = Input::get("email");
		$user->esadmin = Input::get("esadmin");
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
    
    public function get_listaobjetos()
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