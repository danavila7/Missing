<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield('titulo')</title>
    {{ HTML::script('js/angular.min.js') }}
    {{ HTML::script('js/app.js') }}
    {{ HTML::script('js/jquery.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-fileinput.js') }}
  @section('head')
  <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <style type="text/css">
h1.heading{padding:0px;margin: 0px 0px 10px 0px;text-align:center;font: 18px Georgia, "Times New Roman", Times, serif;}

/* width and height of google map */
#google_map {width: 90%; height: 500px;margin-top:0px;margin-left:auto;margin-right:auto;}

/* Marker Edit form */
.marker-edit label{display:block;margin-bottom: 5px;}
.marker-edit label span {width: 100px;float: left;}
.marker-edit label input, .marker-edit label select{height: 24px;}
.marker-edit label textarea{height: 60px;}
.marker-edit label input, .marker-edit label select, .marker-edit label textarea {width: 60%;margin:0px;padding-left: 5px;border: 1px solid #DDD;border-radius: 3px;}

/* Marker Info Window */
h1.marker-heading{color: #585858;margin: 0px;padding: 0px;font: 18px "Trebuchet MS", Arial;border-bottom: 1px dotted #D8D8D8;}
div.marker-info-win {max-width: 300px;margin-right: -20px;}
div.marker-info-win p{padding: 0px;margin: 10px 0px 10px 0;}
div.marker-inner-win{padding: 5px;}
button.save-marker, button.remove-marker{border: none;background: rgba(0, 0, 0, 0);color: #00F;padding: 0px;text-decoration: underline;margin-right: 10px;cursor: pointer;
}
</style>
    <!-- Script Google Maps -->
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>



  @show

   <!-- Le styles -->
    {{ HTML::style('bootstrap-3.2.0/css/bootstrap.css') }}
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
   

</head>
    <body>
      <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
        @section('sidebar')
        <div class="navbar navbar-inverse navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container">
              @if(Auth::check())
                <p class="pull-right" style="color: white;"> Bienvenido {{ Auth::user()->usuario }}- {{ HTML::link('users/update/'.Auth::user()->id,'Configuración  ',array( 'type' => 'button', 'class' => 'btn btn-default')) }} - {{ HTML::link('logout','Cerrar Sesión',array( 'type' => 'button', 'class' => 'btn btn-default')) }}</p>
              @else
            {{ Form::open(array('url' => 'login'))  }}
            <div class="pull-right">
              <input class="span2" name="email" type="text" placeholder="Email">
              <input class="span2" name="password" type="password" placeholder="Password">
              <button type="submit" class="btn">Iniciar Sesión</button>
            </div>
            {{ Form::close() }}
            @endif
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active">{{ HTML::link('home','Volver al sitio') }}</li>
              @if(Auth::check())
              <li>{{ HTML::link('users/listausuarios','Usuarios') }}</li>
              @endif
              @if(Auth::check())
              <li>{{ HTML::link('examenes/listapreguntas','Examenes') }}</li>
              @endif
              @if(Auth::check())
              <li>{{ HTML::link('examenes/listaexamenes','Examenes') }}</li>
              @endif
              <li>
              
              </li>
            </ul>
             
              </div><!--/.nav-collapse -->
            </div>
            </div>
          </div>
  @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>