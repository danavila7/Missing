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
    .otherwise({ reditrectTo : "/" });
});

