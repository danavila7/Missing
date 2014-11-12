<!DOCTYPE html>
<!--le decimos a nuestra página que vamos a utilizar el módulo app que hemos creado-->
<html ng-app="app">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Missing</title>
    <style type="text/css">
      body { background: #D5D0D0 !important;}
      #map { height: 100% }
    </style>
    <style type="text/css">
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
</head>
<body>
  <div id="fb-root"></div>
  <nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="brand" href="{{ URL::to('/');}}"> 
        <img  src="img/logo-missing1.png" height="45px" width="45px">
      </a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <!--<ul class="nav navbar-nav">
        <li><a href="http://www.missing.cl/">Missing</a></li>
      </ul>-->
      <ul class="nav navbar-nav navbar-left">
        <li>
          <form class="navbar-form navbar-left">
          <div class="input-group">
          <div class="input-group-addon">Buscar</div>
          <input class="form-control" type="text" id="buscalugar" placeholder="Buscar un lugar">
          </div>
        </form>
        </li>
      </ul>
      <!--<ul class="nav navbar-nav navbar-right login-home hide" ng-controller="loginController">
        <li><button class="btn btn-primary facelogin">Login with Facebook</button></li>
      </ul>-->
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
              <li class="pointer"><a>Editar Perfil</a></li>
              <li class="divider"></li>
              <li class="pointer"><a>Mis Missing</a></li>
              <li class="divider"></li>
              <li class="pointer"><a>Configuración</a></li>
              <li><a ng-click="logout()" class="pointer">Cerrar Sesión</a></li>
          </ul>
          </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
    <input type="hidden" id="usuario_id" value="@if (Auth::check()) {{Auth::user()->id}} @endif" />
    <div class="row container-fluid"  ng-view>
    </div>
    <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
    <input type="hidden" id="isLoggin" value="false" />
    </body>
</html>