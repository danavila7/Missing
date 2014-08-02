<?php

class HomeController extends BaseController {
	protected $layout = 'layouts.layout';

	public function showHome()
	{
		$this->layout->content = View::make('objetos.index');
	}

	public function ObtenerMissingPorUsuario(){
		$Ultimos5MissingPorUsuario  = array();
		if (Auth::check())
		{
		$id = Auth::id();
    	$Ultimos5MissingPorUsuario = DB::table('objetos')
                    ->where('usuario_id', $id)
                    ->take(5)
                    ->orderBy('created_at', 'desc')
                    ->get();
		}
		return Response::json(array('Missing'=>$Ultimos5MissingPorUsuario));
	}

	public function ObtenerMissing(){
		$objetos = DB::table('objetos')
					->take(5)
					->orderBy('created_at', 'desc')
                    ->get();
    	return Response::json(array(
        "objetos"=>$objetos
    	));
	}

	public function ObtieneMissingPorId($id){
		$objeto = Objeto::find($id);
		return Response::json(array('missing'=>$objeto));

	}
}
