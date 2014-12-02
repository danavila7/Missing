app.controller("homeController", function($scope, $http, $location, AuthenticationService, CreateUserService, CreateObjetoService, SeguirObjetoService) {
	
	/**** si el usuario permite la localidad del navegador ****/
	var lat = -33.437118;
	var lng = -70.650544;
	if(navigator.geolocation) {
    browserSupportFlag = true;
    	navigator.geolocation.getCurrentPosition(function(position) {
      	var currentMapCenter = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      	lat = position.coords.latitude;
      	lng = position.coords.longitude;
    	}, function() {

    	});
  	}
	
	var radio = 100;
	$http.get(jQuery('#baseurl').val()+"/obtenerObjetosMapaProximos/"+lat+"/"+lng+"/"+radio).success(function(data){
        $scope.datos = data.objetos;//así enviamos los posts a la vista
   	});

	 //asignar modales con nginclude
    $scope.modallogin = 'templates/Modal/modal-login.html';
    $scope.modalagregadatos = 'templates/Modal/modal-agrega-datos.html';
    $scope.modalconfirmdelete = 'templates/Modal/modal-confirm-delete.html';
    $scope.modalshare = 'templates/Modal/modal-share.html';
    $scope.modaldetalles = 'templates/Modal/modal-detalles.html';
    $scope.modalloading = 'templates/Modal/modal-loading.html';
    $scope.modalcrearusuario = 'templates/Modal/modal-crear-usuario.html';
    $scope.modalcrearusuarioescreado = 'templates/Modal/modal-crear-usuario-es-creado.html';
    $scope.modalcrearusuarioescreado = 'templates/Modal/modal-confirm-seguir.html';

    //upload fotos
    $scope.upload = function(){
    	$http.post('')
    }

	$scope.login = function(){
		AuthenticationService.login(this.credentials).success(function(){
			$location.path("/");
		});
	}

	$scope.CreateUserEsCreado = function(){
		this.user.usuario = jQuery('#create_nombre').val();
		this.user.email = jQuery('#create_email').val();
		if(this.user.password == this.user.repassword){
			CreateUserService.createescreado(this.user).success(function(){
			$('#modal-create-usuario-es-creado').modal('hide');
		});
		}else{
			$(".modal-pass-alert").removeClass("hide");
		}
	}

	$scope.CreateUser = function(){
		if(this.user.password == this.user.repassword){
			CreateUserService.create(this.user).success(function(){
			$('#modal-create-usuario').modal('hide');
		});
		}else{
			$(".modal-pass-alert").removeClass("hide");
		}
	}

	$scope.CrearObjeto = function(){
		this.obj.longitud = jQuery("#modal_long_obj").val();
		this.obj.latitud = jQuery("#modal_lat_obj").val();
		this.obj.tipo_objeto = jQuery("#tipo-obj").val();
		var file = this.myFile;
		CreateObjetoService.create(file, this.obj).success(function(){
		$('#alert-obj-creado').removeClass("hide");
		//deshabilitar boton
			setTimeout(function(){
			$('#modal-datos').modal('hide');
			}, 3000);
		});
	}

	 $scope.logout = function(){
	 	AuthenticationService.logout().success(function(){
			$location.path("/login");
		});
	 }

	 $scope.confirmSeguir = function(){
	 	var id = jQuery('#confirm-seguir').find('#missing-id').val();
	 	SeguirObjetoService.seguir(id).success(function(){
	 		$('#alert-obj-seguido').removeClass("hide");
			//deshabilitar boton
			setTimeout(function(){
			$('#confirm-seguir').modal('hide');
			}, 3000);
		});
	 }

	 $scope.checkPin = function(){
	 	var objeto = jQuery('#check-objeto').prop('checked');
	 	var animal = jQuery('#check-animal').prop('checked');
	 	var persona = jQuery('#check-persona').prop('checked');
	 	if(objeto == true){
	 		objeto = 1;
	 	}
	 	if(animal == true){
	 		animal = 1;
	 	}
	 	if(persona == true){
	 		persona = 1;
	 	}
	 	//cargar datos por pin
	 	$http.get(jQuery('#baseurl').val()+'/obtenerobjetosporfiltro/'+objeto+'/'+animal+'/'+persona).success(function(data){
        	$scope.datos = data.objetos;
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
		showDataUser: function(message, avatar){
			//muestra nombre de usuario
			jQuery('#username').html("Bienvenido "+message);
			jQuery('.avatar').attr('src', avatar);
			//Mostrar el logout
			//debugger;
			jQuery('.logout-home').removeClass('hide');
			
			//Cargar lista con missing del usuario
			$http.get(jQuery('#baseurl').val()+'/obtenerMissingPorUsuario').success(function(data){
			$rootScope.misDatos = data.Missing;
   			});
   			//Cargar lista con missing seguidos
			$http.get(jQuery('#baseurl').val()+'/obtenerMissingSeguidosPorUsuario').success(function(data){
			$rootScope.misSeguidos = data.Missing;
   			});
   			$rootScope.isLoggin = true;
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
			$rootScope.misSeguidos = '';
			$rootScope.isLoggin = false;
		},
		errorLogin: function(){
			jQuery('#login-error').fadeIn( "slow" );
		}
	}
});

app.run(function($rootScope, $http, $location, $routeParams, AuthenticationService, ShowService, SessionService){

	var routesThatRequireAuth = ['/usuarios'];

	var routesGetUser = ['/createUser'];

	var routesShare = ['/share/'];

	var rutasObjetos = ['/'];

	$rootScope.$on('$viewContentLoaded', function() {

		if($routeParams.missingId != '' && $routeParams.missingId !== undefined){
			
			var Id = $routeParams.missingId;
			var baseurl = $('#baseurl').val().replace('index.php','');
			$http.get(baseurl+"/datosMissing/"+Id).success(function(data){
				var missing = eval(data.missing);
				var src = "http://maps.googleapis.com/maps/api/staticmap?center="+missing.latitud_objeto+","+missing.longitud_objeto+"&zoom=16&size=200x200&markers=color:blue%7Clabel:S%"+missing.latitud_objeto+","+missing.longitud_objeto+"&sensor=false";
				$('.back-app').attr('href', baseurl);
				$('#img_objeto').attr('src', baseurl+'/uploads/'+missing.path);
				$('#ubicacion').attr('src', src);
				$('#nom_objeto').text(missing.nombre_objeto);
				$('#desc_objeto').text(missing.descripcion_objeto);
				$('#fecha_objeto').text(missing.fecha);
				$('#dir_objeto').text(missing.direccion_objeto);
				$('#tipo').text(missing.tipo);
				$('#usuario_objeto').text(missing.usuario);
				$('.fb-comments').attr('data-href', baseurl+'/#/'+missing.id);
   			});
		}

		if(_(routesGetUser).contains($location.path())){
			$http.get(jQuery('#baseurl').val()+'/getUser').success(function(data){
				if(data.msg == 'true'){
					if(data.nombre != null){
						jQuery('#create_nombre').val(data.nombre);
					}
					if(data.email != null){
						jQuery('#create_email').val(data.email);
					}
				}
   			});
		}
		if(_(rutasObjetos).contains($location.path())){
			//debo ir a buscar si existe un usuario logiado dentro de laravel
			$http.get(jQuery('#baseurl').val()+'/isLoggedIn').success(function(data){
				if(data.isloggin != 'false'){
        			SessionService.set('authenticated', true);
					SessionService.setuser('username', data.isloggin);
					ShowService.showDataUser(data.isloggin, data.avatar);
					jQuery('#isLoggin').val(true);
					if(data.esCreado != 'true'){

						$http.get(jQuery('#baseurl').val()+'/getUser').success(function(data){
						if(data.msg == 'true'){
							if(data.nombre != null){
								jQuery('#create_nombre').val(data.nombre);
							}
							if(data.email != null){
								jQuery('#create_email').val(data.email);
							}
							if(data.esCreado == null || data.esCreado == 0){
								$('#modal-create-usuario').modal({
  								backdrop: 'static',	
  								keyboard: false
								});
							}
						}
		   				});
						//window.location = window.location.href.replace(/#.*/, '#/createUser');
					}
				}else{
					jQuery('#isLoggin').val(false);
				}
   			});

			//loading del mapa
			jQuery('#loading').attr('src', jQuery('#baseurl').val()+'/img/ajax-loader.gif');
		}
    	if(!_(routesThatRequireAuth).contains($location.path()) && AuthenticationService.isLoggedIn()){
    		ShowService.showDataUser(SessionService.get('username'));
    	}else{
    		ShowService.showDataLogin();
    	}
	});

	$rootScope.$on('$routeChangeStart', function(event, next, current){
		//debugger;
		if(_(routesThatRequireAuth).contains($location.path()) && !AuthenticationService.isLoggedIn()){
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


app.factory("CreateUserService", function($http, $location, SessionService, FlashService, ShowService){
	var createSuccess = function(response){
		SessionService.set('authenticated', true);
		SessionService.setuser('username', response.isloggin);
		ShowService.showDataUser(response.isloggin, response.avatar);
		jQuery('#isLoggin').val(true);
	};
	var createError = function(response){
	};
	return{
		create: function (user){
			var create = $http.post(jQuery('#baseurl').val()+"/createUser", user);
			create.success(createSuccess);
			return create;
		},
		createescreado: function (user){
			var create = $http.post(jQuery('#baseurl').val()+"/createUserEsCreado", user);
			return create;
		}
	};
});

app.factory("SeguirObjetoService", function($http, $location, SessionService, FlashService, ShowService){
	var seguirSuccess = function(response){
	};
	var seguirError = function(response){
	};
	return{
		seguir: function (id){
			var seguir = $http.post(jQuery('#baseurl').val()+"/seguirObjeto", { objeto_id : id });
			seguir.success(seguirSuccess);
			return seguir;
		}
	};
});

app.factory("CreateObjetoService", function($http, $location, SessionService, FlashService, ShowService){
	var createSuccess = function(response){
		alert(response.msg)
	};
	var createError = function(response){
	};
	return{
		create: function (file, obj){
			var fd = new FormData();
        	fd.append('file', file);

			var create = $http.post(jQuery('#baseurl').val()+"/createObject", obj)
			.success(function(){
				var saveImage = $http.post(jQuery('#baseurl').val()+"/cargaImagen", fd,{ 
				transformRequest:angular.identity,
				headers:{'Content-type':undefined}
				})
				.success(function(){
					//alert('cargo la imagen');
				})
				.error(function(){
					//alert('no cargo la imagen');
				});
			})
			.error(function(){
				//alert('error no cargo');
			});
			
			return create;
		}
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
			var login = $http.post(jQuery('#baseurl').val()+"/login", credentials);
			login.success(cacheSession);
			login.success(loginSuccess);
			login.success(FlashService.clear);
			login.error(loginError);
			return login;
		},
		logout: function (){
			var logout = $http.get(jQuery('#baseurl').val()+"/logout");
			logout.success(uncacheSession);
			logout.success(ShowService.showDataLogin);
			return logout;

		},
		isLoggedIn: function(){
			return SessionService.get('authenticated');	
		}
	};
});