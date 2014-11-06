<?php

class RestUsuarioController extends BaseController
{
    public function post_login($email, $password)
    {
        $credentials = array(
        'email' => $email,
        'password' => $password);
        if(Auth::attempt($credentials)){
            return Response::json(array('msg'=>Auth::user()->usuario));
        }else{
        	return Response::json(array('flash'=>Hash::make(Input::json('password'))),500);
        }   
    }    

}