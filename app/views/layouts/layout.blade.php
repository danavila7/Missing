<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield('titulo')</title>
    {{ HTML::script('bootstrap-3.2.0/js/jquery.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-transition.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-alert.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-modal.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-dropdown.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-scrollspy.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-tab.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-tooltip.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-popover.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-button.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-collapse.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-carousel.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-typeahead.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/bootstrap-fileinput.js') }}
    {{ HTML::script('bootstrap-3.2.0/js/holder.js') }}
  @section('headMap')
  <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <!-- Script Google Maps -->
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB3kJGreQqizzCxAH9zZWcfvL4i7Trox8g&sensor=false">
  </script>



  @show

   <!-- Le styles -->
    {{ HTML::style('bootstrap-3.2.0/css/bootstrap.css') }}
    <!--<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">-->
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <!--<link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">-->
    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->
   

</head>
    <body>
        @section('sidebar')
        <div class="navbar navbar-inverse navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container">
              <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
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