//creamos el modulo y le inyectamos el modulo ngRoute
var app = angular.module("app", ['ngRoute']);
 
//las rutas siguen trabajando de la misma forma
app.config(function($routeProvider)
{
    $routeProvider.when("/", {
        templateUrl : "templates/Home/home.html",
        controller : "homeController"
    })
    .when("/login", {
        templateUrl : "templates/Login/login.html",
        controller : "loginController"
    })
    .when("/createUser", {
        templateUrl : "templates/Login/create-user-logged.html",
        controller : "loginController"
    })
    .when("/share/:missingId", {
        templateUrl : "templates/Home/share.html",
        controller : "homeController"
    })
    .otherwise({ reditrectTo : "/" });
});

