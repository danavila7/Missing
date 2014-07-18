@extends('layouts.layout')
@section('head')
@stop
@section('titulo')
Missing/Lista de Usuarios
@stop
@section('sidebar')
    @parent
@stop
@section('content')
<h1> Usuarios</h1>
<br/>
{{ HTML::link('users/create','Crear Usuario',array( 'type' => 'button', 'class' => 'btn btn-default')) }}
<br/>
<br/>
<table class="table table-hover">
<tr>
<th>
Nombre Usuario
</th>
<th>
Correo
</th>
<th>
Permiso
</th>
<th>
Eliminar
</th>
<th>
Editar
</th>
</tr>
@if($usuarios != null)
	<lu>
    @foreach($usuarios as $user)
    <tr>
<td>
{{ $user->nombre }} 
</td>
<td>
{{ $user->email }} 
</td>
<td>
@if($user->esadmin == 1)
Administrador
@else
Alumno
@endif
</td>
<td>
{{ HTML::link('users/delete/'.$user->id,'Borrar Usuario',array( 'type' => 'button', 'class' => 'btn btn-default')) }}
</td>
<td>
{{ HTML::link('users/update/'.$user->id,'Editar Usuario',array( 'type' => 'button', 'class' => 'btn btn-default')) }}
</td>
</td>
</tr>
        
    @endforeach
	</lu>
@else
No existen usuarios conectados
@endif
</table>
@stop