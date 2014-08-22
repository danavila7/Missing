<?php

class HomeController extends BaseController {
	protected $layout = 'layouts.layout';

	public function showHome()
	{
		$this->layout->content = View::make('objetos.index');
	}

	public function ObtenerMissingPorUsuario(){
		$UltimosMissingPorUsuario  = array();
		if (Auth::check())
		{
		$id = Auth::id();
    	$UltimosMissingPorUsuario = DB::table('objetos')
                    ->where('usuario_id', $id)
                    ->where('estado' , 1)
                    ->take(5)
                    ->orderBy('created_at', 'desc')
                    ->get();
		}
		return Response::json(array('Missing'=>$UltimosMissingPorUsuario));
	}


	//obtener los ultimos missing pordenados por fecha
	public function ObtenerTodosMissing($tipoobjeto_id = null){
		$objetos = DB::table('objetos')
					->where('estado', 1)
					->where(function($query){
						if(isset($tipoobjeto_id)){
							$query->where('tipoobjeto_id', $tipoobjeto_id);
						}
					})
					->orderBy('created_at', 'desc')
                    ->get();
    	return Response::json(array(
        "objetos"=>$objetos
    	));
	}

	public function ObtenerMissing(){
		$objetos = DB::table('objetos')
					->where('estado', 1)
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
			"direccion_objeto" => $objeto->direccion_objeto,
			"latitud_objeto"=>$objeto->latitud_objeto,
			"longitud_objeto"=>$objeto->longitud_objeto,
			"tipo"=>$obj->GetType($objeto->tipoobjeto_id),
			"fecha"=>$objeto->created_at,
			"usuario" => $usuario->usuario
			);
		return Response::json(array('missing'=>$data));

	}
}
