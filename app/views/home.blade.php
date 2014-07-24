<!DOCTYPE html>
<!--le decimos a nuestra página que vamos a utilizar el módulo app que hemos creado-->
<html ng-app="app">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Missing Angular</title>
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <style type="text/css">

</style>
  <!--cdn con la version 1.2.4 de angular.js-->
  <!-- <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
 cdn con el modulo ngRoute de angular-->
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
  archivo app.js, donde hemos definido nuestro modulo app-->
  {{ HTML::script('js/AngularJS/angular.js') }}
  {{ HTML::script('js/AngularJS/angular-route.js') }}
  {{ HTML::script('js/app.js') }}
  {{ HTML::script('js/controllers.js') }}
  {{ HTML::script('js/lib/jquery.js') }}
      <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>
  <script src="../js/page/objetos_index.js"></script>
    <!-- style -->
  {{ HTML::style('css/fundation.css'); }}
  {{ HTML::style('css/normalize.css'); }}
  <!--{{ HTML::style('css/bootstrap.css'); }}-->
    
</head>
    <body>
      <input type="hidden" id="baseurl" value="{{ URL::to('/');}}" />
    <!--creamos el div con la directiva ng-view, aquí será donde
    carguen todas las vistas-->
    <div class="row" ng-view></div>

    </body>
</html>