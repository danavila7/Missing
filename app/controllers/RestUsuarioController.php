<?php

class RestUsuarioController extends BaseController
{
    public function ValidaLogin($email, $password)
    {
        $credentials = array(
        'email' => $email,
        'password' => $password);
        if(Auth::attempt($credentials)){
            $user = Usuario::where('email', '=', $email)->firstOrFail();
            return Response::json(array('msg'=>'1','id'=>$user->id));
        }else{
        	return Response::json(array('msg'=>'0'));
        }   
    }    

}