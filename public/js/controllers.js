app.controller("homeController", ['$scope','$http', function($scope,$http,AuthenticationService) {
	 $http.get(jQuery('#baseurl').val()+'/obtenerObjetos').success(function(data) 
    {
        $scope.datos = data.objetos;//así enviamos los posts a la vista
   });

	 $scope.logout = function(){
	 	AuthenticationService.logout().success(function(){
			$location.path("/login");
		});
	 };
}]);


app.factory("AuthenticationService", function($http, $location){
	return{
		login: function (credentials){
			return $http.post("/login", credentials);
		},
		logout: function (){
			return $http.get("/logout");
		}
	};
});
 
app.controller("loginController", function($scope, $location, AuthenticationService){
	$scope.credentials = { username:'', password:'' };
	$scope.login = function(){
		AuthenticationService.login($scope.credentials).success(function(){
			$location.path("/");
		});
	}
});
