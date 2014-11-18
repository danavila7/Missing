<!DOCTYPE html>
<!--le decimos a nuestra página que vamos a utilizar el módulo app que hemos creado-->
<html ng-app="app">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Weza de objetos perdidos.">
<meta name="keywords" content="missing, perdidas, robos, busquedas, animales, personas, objetos">
<meta name="author" content="Daniel Avila">
  <title>Missing | El Waze de los objetos perdidos.</title>
    <style type="text/css">
      #map { 
        width:100%;
        height:100%;
      }
    </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en"></script>
  {{ HTML::script('js/lib/underscore.js') }}
  {{ HTML::script('js/AngularJS/angular.js') }}
  {{ HTML::script('js/AngularJS/angular-route.js') }}
  {{ HTML::script('js/app.js') }}
  {{ HTML::script('js/controllers.js') }}

  {{ HTML::script('js/lib/jquery.js') }}
  {{ HTML::script('js/lib/bootstrap.js') }}
  {{ HTML::script('js/lib/jasny-bootstrap.min.js') }}
  {{ HTML::script('js/lib/bootstrap-datepicker.js') }}
  <!--{{ HTML::script('js/lib/jquery.mobile.js') }}-->
  {{ HTML::script('js/page/home.js') }}
  {{ HTML::script('js/page/objetos.js') }}
  <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>-->
  {{ HTML::style('css/jasny-bootstrap.min.css'); }}
  {{ HTML::style('css/marker.css'); }}
  <!--{{ HTML::style('css/jquery.mobile.css'); }}-->
  {{ HTML::style('css/home.css'); }}
 <!--   style -->
  {{ HTML::style('css/bootstrap.css'); }}
  {{ HTML::style('css/docs.min.css'); }}
  {{ HTML::style('css/datepicker.css'); }}
  {{ HTML::style('css/datepicker3.css'); }}
  {{ HTML::style('css/bootstrap-responsive.css'); }}
  <!--{{ HTML::style('css/bootstrap.css'); }}-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_CL/sdk.js#xfbml=1&appId=1476123785965298&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script src="http://connect.facebook.net/en_ES/all.js"></script>
</head>
<body>
  <div id="fb-root"></div>
  <!-- Docs master nav -->
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ URL::to('/');}}"> 
        <img  src="img/logo-missing3.png" height="35px" width="100px">
      </a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      <ul class="nav navbar-nav">
        <li>
          <form class="navbar-form navbar-left">
          <div class="input-group">
          <div class="input-group-addon">Buscar</div>
          <input class="form-control" type="text" id="buscalugar" placeholder="Buscar un lugar">
          </div>
        </form>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right login-home hide" ng-controller="loginController">
       <!--<form class="navbar-form navbar-left" ng-submit="login()">
        <div class="form-group">
          <input type="text" name="email" class="form-control" placeholder="Ingresar el correo" ng-model="credentials.email" required/>
          <input type="password" name="password" class="form-control" placeholder="Ingresar el password" ng-model="credentials.password" required/>
        </div>
      </form>-->
      <li><a class="sing-up pointer">Crear Cuenta</a></li>
       <li><a class="iniciar-sesion pointer">Iniciar Sesión</a></li>
      <div class="alert alert-danger" id="login-error" style="display:none;">
        <button type="button" class="close">×</button>
        Login Invalido... Vuelve a intentarlo.
      </div>
      </ul>
      <ul class="nav navbar-nav navbar-right logout-home hide" ng-controller="loginController">
        <li><a id="username" ></a></li>
        <li><a class="brand"> 
          <img class="avatar img-circle" src="img/avatar-default.jpeg" height="30px" width="30px"></a></li>
          <li>
            <a class="dropdown-toggle pointer" data-toggle="dropdown">
            <img  src="img/glyphicons/png/glyphicons_136_cogwheel.png" height="25px" width="25px"/>
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li class="pointer" onclick="cargaPerfil(@if (Auth::check()) {{Auth::user()->id}} @endif)"><a>Editar Perfil</a></li>
              <li class="divider"></li>
              <li class="pointer"><a>Mis Missing</a></li>
              <li class="divider"></li>
              <li class="pointer"><a>Configuración</a></li>
              <li><a ng-click="logout()" class="pointer">Cerrar Sesión</a></li>
          </ul>
          </li>
      </ul>
    </nav>
  </div>
</header>
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
    <!-- Page content of course! -->
<main id="content" role="main">
    <div class="container" ng-view>
    </div>
    <input type="hidden" id="usuario_id" value="@if (Auth::check()) {{Auth::user()->id}} @endif" />
    <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
    <input type="hidden" id="isLoggin" value="false" />
</main>

<footer class="bs-docs-footer" role="contentinfo">
<div class="container">
  <div class="bs-docs-social">
    <ul class="bs-docs-social-buttons">
      <li>
        <div class="fb-like" data-send="true" data-width="450">
        </div>
      </li>
    </ul>
  </div>
</div>
</footer>
    </body>
</html>