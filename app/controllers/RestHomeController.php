<?php

class RestHomeController extends BaseController {

	public function ObtieneMissingProximos($lng,$lat,$num){

		$objetos = DB::table('objetos')->select(DB::raw("objetos.*, 
			( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latitud_objeto ) ) 
            * cos( radians(longitud_objeto) - radians(".$lng.")) + sin(radians(".$lat.")) 
            * sin( radians(latitud_objeto)))) AS distance,
			CASE objetos.tipoobjeto_id WHEN 1 THEN 'Objeto' WHEN 2 THEN 'Animal' WHEN 3 THEN 'Persona' END AS tipo"))
				->where('estado', '1')
				->take($num)
				->orderBy('distance', 'desc')
				->get();

		//INNER JOIN objetos on objetos.id = location.from_id

		return Response::json(array('missing'=>$objetos));

	}

	public function ObtieneMissingPorUsuarioId($id){
		$objetos = DB::table('objetos')
		->where('usuario_id',$id)
		->select('id', 'nombre_objeto','tipoobjeto_id')->get();
		$data = array();
		$obj = new Objeto;
		foreach ($objetos as $value) {
			$data[] = array(
				'id'=>$value->id,
				'nombre_objeto'=>$value->nombre_objeto,
				'tipo'=>$obj->GetType($value->tipoobjeto_id),
				);
		}

		return Response::json(array('missing'=>$data));

	}

	public function ObtieneMissingPorMissingId($id){
		$objeto = Objeto::find($id);
		$usuario = Usuario::find($objeto->usuario_id);
		$obj = new Objeto;
		$path = "default.png";
		if(isset($objeto->foto_objeto) && $objeto->foto_objeto != ""){
			$path = $objeto->foto_objeto;
		}
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
			"fecha"=>$objeto->created_at,
			"usuario" => $usuario->usuario
			);
		return Response::json(array('missing'=>$data));

	}
}
