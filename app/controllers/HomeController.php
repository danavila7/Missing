<?php

class HomeController extends BaseController {
	protected $layout = 'layouts.layout';
    protected $class = 'home';

	public function showHome()
	{
        View::share('title', $class);
		$this->layout->content = View::make('objetos.index');
	}

	public function get_login()
    {
        return View::make('auth.login');
    }
    
    public function post_login()
    {
        $credentials = array(
        'usuario' => Input::get('email'),
        'password' => Input::get('password'));
        
        if(Auth::attempt($credentials))
        {
            return Redirect::to('/');
        }
        else
        {
        	return Redirect::to('/');
        }   
    }
    
    public function get_logout()
    {
        Auth::logout();
        return Redirect::back();
    }
}
