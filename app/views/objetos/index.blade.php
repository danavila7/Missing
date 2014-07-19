@extends('layouts.layout')
@section('titulo')
Missing
@stop
@section('body')
home
@stop
@section('head')
@parent
{{ HTML::script('js/page/objetos_index.js') }}
@stop
@section('sidebar')
    @parent
@stop
@section('content')
<div class="row">
  <div class="span12">
    <div class="row">
      <div class="span10"><div id="google_map"></div></div>
      <div class="span2">
     	Missing Recientes
      <table class="table table-hover">
     	@foreach($objetos as $objeto)
     	<tr>
     	<td>
     	{{ $objeto->nombre_objeto }} {{ HTML::link('#', 'Ir al Missing', array('onclick' => 'buscaMissing( '.$objeto->latitud_objeto.' , '.$objeto->longitud_objeto.' )')) }}
     	</td>
     	</tr>
       @endforeach
     </table>
      </div>
      
    </div>
  </div>
</div> 
@stop