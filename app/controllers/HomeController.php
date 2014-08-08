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
		$usuario = Usuario::find($objeto->usuario_id);
		$obj = new Objeto;
		$data  = array(
			"id" => $objeto->id,
			"nombre_objeto" => $objeto->nombre_objeto,
			"descripcion_objeto" =>$objeto->descripcion_objeto,
			"latitud_objeto"=>$objeto->latitud_objeto,
			"longitud_objeto"=>$objeto->longitud_objeto,
			"tipo"=>$obj->GetType($objeto->tipoobjeto_id),
			"fecha"=>$objeto->created_at,
			"usuario" => $usuario->usuario
			);
		return Response::json(array('missing'=>$data));

	}
}
