<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"/> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><html lang="en" class="no-js"><![endif]-->
<!-- AGREGANDO EL MODULO APP (ANGULARJS) -->
<html ng-app="app">
<head>
<title>Missing | El Waze de los objetos perdidos.</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="description" content="Weza de objetos perdidos.">
<meta name="keywords" content="missing, perdidas, robos, busquedas, animales, personas, objetos, perdido
Chile, niños, perdida, encontrar, buscar, publicar, compartir, busca, encuentra">
<meta name="author" content="Davila">
<meta property="og:title" content="Publica, Comparte y encuentra"/>
<meta property="og:image" content="http://appmissing.missing.cl/Missing/public/img/logo-missing3.png"/>
<meta property="og:description" content="Ayudame a encontrar lo que perdí "/>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<!-- GOOGLE MAPS API -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en"></script>

 

  <!-- JS -->
  {{ HTML::script('js/lib/underscore.js') }}
  {{ HTML::script('js/AngularJS/angular.js') }}
  {{ HTML::script('js/AngularJS/angular-route.js') }}
  {{ HTML::script('js/app.js') }}
  {{ HTML::script('js/controllers.js') }}
  {{ HTML::script('js/lib/jquery.js') }}
  {{ HTML::script('js/lib/bootstrap.js') }}
  {{ HTML::script('js/lib/jasny-bootstrap.min.js') }}
  {{ HTML::script('js/lib/bootstrap-datepicker.js') }}
  {{ HTML::script('js/lib/bootstrap-fileupload.js') }}
  <!--{{ HTML::script('js/lib/jquery.mobile.js') }}-->
  {{ HTML::script('js/page/login.js') }}
  {{ HTML::script('js/page/home.js') }}
  {{ HTML::script('js/page/objetos.js') }}

  <!-- CSS -->
  {{ HTML::style('css/jasny-bootstrap.min.css'); }}
  {{ HTML::style('css/marker.css'); }}
  {{ HTML::style('css/font-awesome-4.2.0/css/font-awesome.css'); }}
  <!--{{ HTML::style('css/jquery.mobile.css'); }}-->
  {{ HTML::style('css/home.css'); }}
  {{ HTML::style('css/bootstrap.css'); }}
  {{ HTML::style('css/docs.min.css'); }}
  {{ HTML::style('css/datepicker.css'); }}
  {{ HTML::style('css/bootstrap-responsive.css'); }}
  {{ HTML::style('css/bootstrap-fileupload.css'); }}

<!-- script especiales -->
  {{ HTML::script('js/yepnope.js') }}
  {{ HTML::script('js/rulesJS.js') }}

<!-- FACEBOOK API -->
{{ HTML::script('js/functions/facebook.js') }}
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_CL/sdk.js#xfbml=1&appId=1476123785965298&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script src="http://connect.facebook.net/en_ES/all.js"></script>

<!-- TWITTER API -->
<script type="text/javascript">
window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
</script>

<!-- GOOGLE+ API -->
<script src="https://apis.google.com/js/platform.js" async defer>
  {lang: 'es'}
</script>
</head>
<body>
  <!--[if lt IE 7]>
  <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
  <![endif]-->
  <div id="fb-root"></div>
  <!-- Docs master nav -->
<header class="navbar navbar-static-top bs-docs-nav azul" id="top" role="banner">
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
          <div class="input-group-addon"><i class="fa fa-search"></i></div>
          <input class="form-control" type="text" id="buscalugar" placeholder="Ingresa una dirección...">
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
      <li><a class="sing-up pointer nav-bar-text">Crear Cuenta</a></li>
       <li><a class="iniciar-sesion pointer nav-bar-text">Iniciar Sesión</a></li>
      <div class="alert alert-danger" id="login-error" style="display:none;">
        <button type="button" class="close">×</button>
        Login Invalido... Vuelve a intentarlo.
      </div>
      </ul>
      <ul class="nav navbar-nav navbar-right logout-home hide" ng-controller="loginController">
        <li><a class="nav-bar-text" id="username" ></a></li>
        <li><a class="brand"> 
          <img class="avatar img-circle" src="img/avatar-default.jpeg" height="30px" width="30px"></a></li>
          <li>
            <a class="dropdown-toggle pointer" data-toggle="dropdown">
            <img  src="img/glyphicons/png/glyphicons_136_cogwheel.png" height="25px" width="25px"/>
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li class="pointer" onclick="cargaPerfil(@if (Auth::check()) {{Auth::user()->id}} @endif)">
                <a class="dropdown-menu-text">Editar Perfil</a>
              </li>
              <li class="pointer dropdown-menu-text"><a class="dropdown-menu-text">Configuración</a></li>
              <li class="divider"></li>
              <li><a ng-click="logout()" class="pointer dropdown-menu-text">Cerrar Sesión</a></li>
          </ul>
          </li>
      </ul>
    </nav>
  </div>
</header>
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
<main id="content" role="main">
    <div class="container container-view" ng-view>
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
      <li>
        <a class="twitter-share-button"
            href="https://twitter.com/share"
            data-url="{{ URL::to('/');}}"
            data-via="missing_app"
            data-text="Publica, comparte y encuentra!"
            data-related="Missing App"
            data-count="vertical">
            Tweet
        </a>
      </li>
      <li>
        <div class="g-plus" data-action="share"></div>
      </li>
    </ul>
  </div>
</div>
</footer>
    </body>
</html>