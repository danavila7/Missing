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
                    ->where('estado' , 0)
                    ->take(5)
                    ->orderBy('created_at', 'desc')
                    ->get();
		}
		return Response::json(array('Missing'=>$UltimosMissingPorUsuario));
	}

	public function ObtenerTodosMissing(){
		$missingTotal  = array();
		if (Auth::check())
		{
		$id = Auth::id();
    	$missingTotal = DB::table('objetos')
    				->where('usuario_id', $id)
                    ->where('estado' , 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
		}else{
			$missingTotal = DB::table('objetos')
                    ->where('estado' , 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
		}
		return Response::json(array('objetos'=>$missingTotal));
	}

	public function ObtieneMissingProximos($lng,$lat,$num){

		$objetos = DB::table('objetos')->select(DB::raw("objetos.*, 
			( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latitud_objeto ) ) 
            * cos( radians(longitud_objeto) - radians(".$lng.")) + sin(radians(".$lat.")) 
            * sin( radians(latitud_objeto)))) AS distance,
			CASE objetos.tipoobjeto_id WHEN 1 THEN 'Objeto' WHEN 2 THEN 'Animal' WHEN 3 THEN 'Persona' END AS tipo"))
				->where('estado', '0')
				->take($num)
				->orderBy('distance', 'desc')
				->get();

		//INNER JOIN objetos on objetos.id = location.from_id

		return Response::json(array('missing'=>$objetos));

	}

	//obtener los ultimos missing pordenados por fecha
	public function ObtenerTodosMissingMapaProximos($lat,$lng){
		$objetos = DB::table('objetos')->select(DB::raw("objetos.*, 
			( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latitud_objeto ) ) 
            * cos( radians(longitud_objeto) - radians(".$lng.")) + sin(radians(".$lat.")) 
            * sin( radians(latitud_objeto)))) AS distance,
			CASE objetos.tipoobjeto_id WHEN 1 THEN 'Objeto' WHEN 2 THEN 'Animal' WHEN 3 THEN 'Persona' END AS tipo"))
				->where('estado', '1')
					->where(function($query){
						if(isset($tipoobjeto_id)){
							$query->where('tipoobjeto_id', $tipoobjeto_id);
						}
					})
					->orderBy('distance', 'desc')
					->take(6)
                    ->get();
        $obj = new Objeto;            
        $object = array();
        foreach($objetos as $objeto){
        	$path = '';
        	if(isset($objeto->foto_objeto) && $objeto->foto_objeto != ""){
				$path = $objeto->foto_objeto;
			}else{
				$path = "default.png";
			}   
        	$object[] = array("id"=>$objeto->id,
        				"usuario_id"=>$objeto->usuario_id,
        				"nombre_objeto"=>$objeto->nombre_objeto,
        				"latitud_objeto"=>$objeto->latitud_objeto,
        				"longitud_objeto"=>$objeto->longitud_objeto,
        				"descripcion_objeto"=>$objeto->descripcion_objeto,
        				"tipo"=>$obj->GetType($objeto->tipoobjeto_id),
        				"tipoobjeto_id"=>$objeto->tipoobjeto_id,
        				"path"=>$path
        		);       
		}
    	return Response::json(array(
        "objetos"=>$object
    	));
	}

	//obtener los ultimos missing por filtro ordenados por fecha
	public function ObtenerTodosMissingPorFiltro($objeto,$animal,$persona){


		$query = DB::table('objetos')
					->where('estado', 0);

		$datos = array();
		if($objeto == 1){
			$datos[] = 1;
		}
		if($animal == 1){
			$datos[] = 2;
 		}
 		if($persona == 1){
 			$datos[] = 3;
 		}
		
		if(count($datos) > 0){
			$query	= $query->whereIn('tipoobjeto_id', $datos);
		}
		
		$result = $query->take(10)
			   			->orderBy('created_at', 'desc')
               			->get();


    	return Response::json(array(
        "objetos"=>$result
    	));
	}

	public function ObtenerMissing(){
		$objetos = DB::table('objetos')
					->where('estado', 0)
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
		$path = "default.png";
		if(isset($objeto->foto_objeto) && $objeto->foto_objeto != ""){
			$path = $objeto->foto_objeto;
		}

		$date = date('d M Y - H:i a',strtotime($objeto->created_at));


		$data  = array(
			"id" => $objeto->id,
			"nombre_objeto" => $objeto->nombre_objeto,
			"descripcion_objeto" =>$objeto->descripcion_objeto,
			"direccion_objeto" => $objeto->direccion_objeto,
			"latitud_objeto"=>$objeto->latitud_objeto,
			"longitud_objeto"=>$objeto->longitud_objeto,
			"path"=>$path,
			"contacto_objeto"=>$objeto->contacto_objeto,
			"recompensa_objeto"=>$objeto->recompensa_objeto,
			"fecha_perdida"=>$objeto->fecha_perdida,
			"tipo"=>$obj->GetType($objeto->tipoobjeto_id),
			"estado"=>$obj->GetStatus($objeto->estado),
			"fecha"=>$date,
			"usuario" => $usuario->usuario
			);
		return Response::json(array('missing'=>$data));

	}
}
