app.controller("homeController", ['$scope','$http', function($scope,$http) {
	 $http.get('http://test.missingangular.org/obtenerObjetos').success(function(data) 
    {
        $scope.datos = data.objetos;//así enviamos los posts a la vista
   });

	 $scope.buscaMissing = function (lat,lng) {
	 	var map = angular.element( document.querySelector( '#map' ) );
	 	var marker2 = new google.maps.Marker({ position: new google.maps.LatLng(lat, lng), map: map, title: 'my 2nd title',setMap : map});
		//map.panTo(marker2.getPosition());
	}
}]);
 
app.controller("loginController", function($scope)
{
 
});