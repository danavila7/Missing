app.controller("homeController", function($scope, $http, $location, AuthenticationService) {
	 $http.get(jQuery('#baseurl').val()+'/obtenerObjetos').success(function(data){
        $scope.datos = data.objetos;//así enviamos los posts a la vista
   	});

	$scope.login = function(){
		AuthenticationService.login($scope.credentials).success(function(){
			$location.path("/");
		});
	}

	 $scope.logout = function(){
	 	AuthenticationService.logout().success(function(){
			$location.path("/login");
		});
	 }
});
 
app.controller("loginController", function($scope, $location, AuthenticationService){
	$scope.credentials = { username:'', password:'' };
	$scope.login = function(){
		AuthenticationService.login($scope.credentials).success(function(){
			$location.path("/");
		});
	}

	$scope.logout = function(){
	 	AuthenticationService.logout().success(function(){
			$location.path("/");
		});
	 }
});

app.factory("FlashService", function($rootScope){
	//Solo para el login
	return{
		show: function(message){
			jQuery('#flash').html(message);
			$rootScope.flash = message;
		},
		clear: function(){
			$rootScope.flash = "";
		}
	}
});

app.factory("ShowService", function($rootScope, $http){
	return{
		showDataUser: function(message){
			//muestra nombre de usuario
			jQuery('#username').html("Bienvenido "+message);
			//Mostrar el logout
			//debugger;
			jQuery('.logout-home').removeClass('hide');
			
			//Cargar lista con missing del usuario
			$http.get(jQuery('#baseurl').val()+'/obtenerMissingPorUsuario').success(function(data){
			$rootScope.misDatos = data.Missing;
   			});
			//esconder el login
			jQuery('.login-home').addClass('hide');
			jQuery('#isLoggin').val(true);
			jQuery('#modal-login').modal('hide')
		},
		showDataLogin: function(){
			//mostrar el login
			jQuery('.logout-home').addClass('hide');
			jQuery('.login-home').removeClass('hide');
			jQuery('#isLoggin').val(false);
			$rootScope.misDatos ='';
		},
		errorLogin: function(){
			jQuery('#login-error').fadeIn( "slow" );
		}
	}
});

app.run(function($rootScope, $http, $location, AuthenticationService, ShowService, SessionService){

	var routasThatRequireAuth = ['/usuarios'];

	var rutasObjetos = ['/'];

	$rootScope.$on('$viewContentLoaded', function() {
		
		if(_(rutasObjetos).contains($location.path())){
			//debo ir a buscar si existe un usuario logiado dentro de laravel
			$http.get(jQuery('#baseurl').val()+'/isLoggedIn').success(function(data){
				if(data.isloggin != 'false'){
        			SessionService.set('authenticated', true);
					SessionService.setuser('username', data.isloggin);
					ShowService.showDataUser(data.isloggin);
					jQuery('#isLoggin').val(true);
				}else{
					jQuery('#isLoggin').val(false);
				}
   			});

			//loading del mapa
			jQuery('#loading').attr('src', jQuery('#baseurl').val()+'/img/ajax-loader.gif');
		}
    	if(!_(routasThatRequireAuth).contains($location.path()) && AuthenticationService.isLoggedIn()){
    		ShowService.showDataUser(SessionService.get('username'));
    	}else{
    		ShowService.showDataLogin();
    	}
	});

	$rootScope.$on('$routeChangeStart', function(event, next, current){
		//debugger;
		if(_(routasThatRequireAuth).contains($location.path()) && !AuthenticationService.isLoggedIn()){
			$location.path("/login");
		}
	});
});

app.factory("SessionService", function(){
	return {
		get: function(key){
			return sessionStorage.getItem(key);
		},
		set: function(key, val){
			return sessionStorage.setItem(key, val);
		},
		setuser: function(key, val){
			return sessionStorage.setItem(key, val);
		},
		unset: function(key){
			return sessionStorage.removeItem(key);
		},
		unsetuser: function(key){
			return sessionStorage.removeItem(key);
		},
	};
});

app.factory("AuthenticationService", function($http, $location, SessionService, FlashService, ShowService){
	var cacheSession = function(response){
		SessionService.set('authenticated', true);
		SessionService.setuser('username', response.flash);
	};
	var uncacheSession = function(){
		SessionService.unset('authenticated');
		SessionService.unset('username');
	};
	var loginError = function(response){
		ShowService.errorLogin();
		FlashService.show(response.flash);
	}
	var loginSuccess = function(response){
		ShowService.showDataUser(response.flash);
	}

	return{
		login: function (credentials){
			//debugger;
			var login = $http.post("/login", credentials);
			login.success(cacheSession);
			login.success(loginSuccess);
			login.success(FlashService.clear);
			login.error(loginError);
			return login;
		},
		logout: function (){
			var logout = $http.get("/logout");
			logout.success(uncacheSession);
			logout.success(ShowService.showDataLogin);
			return logout;

		},
		isLoggedIn: function(){
			return SessionService.get('authenticated');	
		}
	};
});