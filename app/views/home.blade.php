<!DOCTYPE html>
<!--le decimos a nuestra página que vamos a utilizar el módulo app que hemos creado-->
<html ng-app="app">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Missing</title>
    <style type="text/css">
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
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>
 <script src="../js/page/objetos_index.js"></script>
 <!--   style -->
  {{ HTML::style('css/fundation.css'); }}
  {{ HTML::style('css/normalize.css'); }}
  <!--{{ HTML::style('css/bootstrap.css'); }}-->
    
</head>
<body>
  <nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="{{ URL::to('/');}}">MISSING beta</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li>
      <p id="username" style="color: white;"></p>
      </li>
       <!--<li class="has-dropdown">
        <a href="#">Right Button Dropdown</a>
        <ul class="dropdown">
          <li><a href="#">First link in dropdown</a></li>
        </ul>
      </li>-->
    </ul>
    <!-- Left Nav Section -->
    <ul class="left">
      <!--<li><a href="#">Left Nav Button</a></li>-->
    </ul>
  </section>
</nav>
      <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
    <div class="row" ng-view>

    </div>

    </body>
</html>