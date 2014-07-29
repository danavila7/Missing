<!DOCTYPE html>
<!--le decimos a nuestra página que vamos a utilizar el módulo app que hemos creado-->
<html ng-app="app">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Missing</title>
    <style type="text/css">
      body { background: #939393 !important;}
      #map { height: 100% }
    </style>
    <style type="text/css">

</style>
  <!--cdn con la version 1.2.4 de angular.js-->
  <!-- <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
 cdn con el modulo ngRoute de angular-->
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
  archivo app.js, donde hemos definido nuestro modulo app-->
  {{ HTML::script('js/lib/underscore.js') }}
  {{ HTML::script('js/AngularJS/angular.js') }}
  {{ HTML::script('js/AngularJS/angular-route.js') }}
  {{ HTML::script('js/app.js') }}
  {{ HTML::script('js/controllers.js') }}
  {{ HTML::script('js/lib/jquery.js') }}
  {{ HTML::script('js/page/home.js') }}
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>
 <script src="../js/page/objetos_index.js"></script>
 <!--   style -->
  {{ HTML::style('css/bootstrap.css'); }}
  <!--{{ HTML::style('css/bootstrap.css'); }}-->
    
</head>
<body>
  <nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="#">MISSING beta</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
      </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control span3" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Buscar</button>
      </form>
      <ul class="nav navbar-nav navbar-right login-home hide" ng-controller="loginController">
       <form class="navbar-form navbar-left" ng-submit="login()">
        <div class="form-group">
          <input type="text" name="email" class="form-control" placeholder="Ingresar el correo" ng-model="credentials.email" required/>
          <input type="password" name="password" class="form-control" placeholder="Ingresar el password" ng-model="credentials.password" required/>
        </div>
        <button type="submit" class="btn btn-default">Login</button>
      </form>
      <div class="alert alert-danger" id="login-error" style="display:none;">
        <button type="button" class="close">×</button>
        Login Invalido... Vuelve a intentarlo.
      </div>
      </ul>
      <ul class="nav navbar-nav navbar-right logout-home hide" ng-controller="loginController">
        <li><a href="#" id="username" ></a></li>
        <li><button ng-click="logout()" class="btn btn-default">Logout</button></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
    <div class="row" ng-view>

    </div>
    <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
    </body>
</html>