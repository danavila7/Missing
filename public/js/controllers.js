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
	return{
		show: function(message){
			jQuery('#flash').html(message);
			$rootScope.flash = message;
		},
		showuser: function(message){
			jQuery('.login-home').hide();
			jQuery('#username').html(message);
		},
		clear: function(){
			$rootScope.flash = "";
		},
		clearuser: function(){
			jQuery('#username').html("");
			$rootScope.flash = message;
		}
	}
});

app.run(function($rootScope, $location, AuthenticationService){

	var routasThatRequireAuth = ['/'];

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
		unset: function(key){
			return sessionStorage.removeItem(key);
		},
	};
});

app.factory("AuthenticationService", function($http, $location, SessionService, FlashService){
	var cacheSession = function(){
		SessionService.set('authenticated', true);
	};
	var uncacheSession = function(){
		SessionService.unset('authenticated');
	};
	var loginError = function(response){
		FlashService.show(response.flash);
	}
	var loginSuccess = function(response){
		FlashService.showuser(response.flash);
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
			logout.success(FlashService.clearuser);
			return logout;

		},
		isLoggedIn: function(){
			return SessionService.get('authenticated');	
		}
	};
});