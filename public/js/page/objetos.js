	var map;
	var infowindow = new google.maps.InfoWindow();
	jQuery(document).ready(function() {

		//modal loading
  		$('#modal-loading').modal({
  			backdrop: 'static',
	  		show: false,
	  		closable: false
  		});
		/**** datepicker para la fecha de perdida ****/
		$(".input-date").datepicker({
	   			 todayHighlight: true
		});

		/***** click para esconder el texto de recompensa en el formulario ****/
		$(document).on("click", "#recompensa_check", function() {
			if($(this).is(':checked')){
				$('.div-recompensa').removeClass('hide');
			}else{
				$('.div-recompensa').addClass('hide');
			}
		});
		

		/***** click y buscar en el mapa ***/
		$(document).on("click", ".buscamissing", function() {
		var baseurl = $('#baseurl').val().replace('index.php','');
  		var lat = $(this).attr('data-lat');
		var lng = $(this).attr('data-lng');
		var nombre = $(this).attr('data-nombre');
		var desc = $(this).attr('data-desc');
		var img_path = $(this).attr('data-img');
		var tipo = $(this).attr('data-tipo');
		var Id = $(this).attr('data-id');
		var usuario_id = $(this).attr('data-usuario-id');
		$(".buscamissing").each(function( index ) {
			$(this).removeClass('active');
		});
		$(this).addClass('active');

		if (infowindow) {
        	infowindow.close();
    	}

    	switch(tipo) {
				case 'Objeto':
				   	pin = baseurl+"/img/pin-1_blue.png"
				break;
				case 'Animal':
				    pin = baseurl+"/img/pin-1_green.png"
				break;
				case 'Persona':
				   	pin = baseurl+"/img/pin-1_orange.png"
				break;
				default:
					pin = baseurl+"/img/pin-1_blue.png"
		}



		var marker = new google.maps.Marker({ 
			position: new google.maps.LatLng(lat, lng), 
			map: map,
			icon: pin
		});


		//Create una nueva ventana de informacion
		var contentString = muestraInfo(
							nombre,
							tipo,
							baseurl+"/uploads/"+img_path,
							desc,
							Id
							);

		

		//Find remove button in infoWindow
		var showDetalle 	= contentString.find('button#show-detalle')[0];
		var removeBtn 	= contentString.find('.remove-marker')[0];
		
		if($('#isLoggin').val() == 'true'){
			if(parseInt($('#usuario_id').val()) == parseInt(usuario_id)){
			contentString.find('.remove-marker').removeClass('hide');
			}
		}

		google.maps.event.addDomListener(removeBtn, "click", function(event) {
				remove_marker(Id,nombre);
			});

		google.maps.event.addDomListener(showDetalle, "click", function(event) {
			cargaDatos(Id);
		});
		//accion al hacer click en un pin
		google.maps.event.addListener(marker, 'click', function() {
				if (infowindow) {
        			infowindow.close();
    			}
				infowindow.open(map,marker); // abre la ventana de informacion
	    });
		//agregarle el contenido
		infowindow.setContent(contentString[0]);
		infowindow.open(map,marker); //abre la ventana de info/**/
		});


		/****Auto complete de lugares en el buscador ***/
		autocomplete = new google.maps.places.Autocomplete(
			      (document.getElementById('buscalugar')),
			      {});
		//selecciono un lugar de la lista
		google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
		function onPlaceChanged() {	
			//obtengo el address by Geocoder
			var geocoder = new google.maps.Geocoder();
			  var place = autocomplete.getPlace();
			  if (place.geometry) {
				  lat = place.geometry.location.lat();
				  lng = place.geometry.location.lng();
			  	var mapCenter = new google.maps.LatLng(lat,lng); //Google map Coordinates
				map_initialize(mapCenter,lat,lng); // initialize google map
			  }

			}

	/**** si el usuario permite la localidad del navegador ****/
	function load_map(){
		var $modal = $('#modal-loading'),
	    $bar = $modal.find('.progress-bar');
		$modal.modal('show');
		$bar.addClass('animate');
		if(navigator.geolocation) {
	    browserSupportFlag = true;
	    	navigator.geolocation.getCurrentPosition(function(position) {
	      	var currentMapCenter = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
	      	map_initialize(currentMapCenter,position.coords.latitude,position.coords.longitude);
	    	}, function() {
	    	});
	  	}else{
	  		var mapCenter = new google.maps.LatLng(-33.437118,-70.650544); //Google map Coordinates	
	  		map_initialize(mapCenter,-33.437118,-70.650544); // initialize google map
	  	}
	  	$('#icon-btn-map').addClass('fa-refresh');
  		$('#icon-btn-map').removeClass('fa-spinner');
  	}
  	load_map();

  	

  	$(document).on("click", "#reload_map", function() {
  		$('#icon-btn-map').removeClass('fa-refresh');
  		$('#icon-btn-map').addClass('fa-spinner');
  		
  		load_map();
  	});


		 
	
	
	//############### Google Map Initialize ##############
	function map_initialize(mapCenter,lat,lng){

			// Create an array of styles.
			  var styles = [
			    {
			      stylers: [
			        { hue: "#00ffe6" },
			        { saturation: -20 }
			      ]
			    },{
			      featureType: "road",
			      elementType: "geometry",
			      stylers: [
			        { lightness: 100 },
			        { visibility: "simplified" }
			      ]
			    },{
			      featureType: "road",
			      elementType: "labels",
			      stylers: [
			        { visibility: "off" }
			      ]
			    }
			  ];

			  // Create a new StyledMapType object, passing it the array of styles,
			  // as well as the name to be displayed on the map type control.
			  var styledMap = new google.maps.StyledMapType(styles,
			    {name: "Styled Map"});


			var googleMapOptions = 
			{ 
				center: mapCenter, // map center
				zoom: 17, //zoom level, 0 = earth view to higher value
				maxZoom: 18,
				minZoom: 10,
				mapTypeControlOptions: {
			      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			    },
				zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL //zoom control size
			},
				scaleControl: true, // enable scale control
				mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
			};
		
		   	map = new google.maps.Map(document.getElementById("map"), googleMapOptions);	
		   	//Associate the styled map with the MapTypeId and set it to display.
			  map.mapTypes.set('map_style', styledMap);
			  map.setMapTypeId('map_style');

			jQuery.get(jQuery('#baseurl').val()+"/obtenerTodosMissing", function (data) {
				var baseurl = $('#baseurl').val().replace('index.php','');
				//json
				jsondata = eval(data);
				for(i=0; i< jsondata['objetos'].length;i++){
						info = eval(jsondata['objetos'][i]);
						var id = info.id;
						var  usuario_id = info.usuario_id;
						var nombre_objeto 	= info.nombre_objeto;
					  	var descripcion_objeto 	= info.descripcion_objeto;
					  	var tipo 		= info.tipo;
					  	var tipoobjeto_id 	= info.tipoobjeto_id;
					  	var path      =  info.foto_objeto;
					  	var point 	= new google.maps.LatLng(parseFloat(info.latitud_objeto),parseFloat(info.longitud_objeto));
					  	var pin = "/img/pin-1_blue.png";
					  	switch(tipoobjeto_id) {
						    case '1':
				    			pin = baseurl+"/img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = baseurl+"/img/pin-1_green.png"
				    			break;
				    		case '3':
				    			pin = baseurl+"/img/pin-1_orange.png"
				    			break;
						    default:
						        pin = baseurl+"/img/pin-1_blue.png"
						}
						showMarkers(id, point, nombre_objeto, path, descripcion_objeto, tipo, pin, usuario_id);
				}
			});
			//escondemos el loader
			$('#modal-loading').modal('hide');
			jQuery('.loader').hide();
			//Right Click
			google.maps.event.addListener(map, 'rightclick', function(event) {
				if($('#isLoggin').val() == 'false'){
						$('#modal-login').modal();
					}else{
					baseurl = $('#baseurl').val().replace('index.php','');
					createMarker(event.latLng, baseurl+"/img/pin-1_green.png");
					}
			});		

			

	}


	//############### crea el Marker con la info ##############
	function createMarker(MapPos, iconPath){
		//nuevo marker
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:true,
			animation: google.maps.Animation.DROP,
			icon: iconPath
		});

		var content = $('.marker-edit').clone();
		//busco el contenido
		content.removeClass('hide');
		//Creo la ventana de info
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(content[0]);

		//Find remove button in infoWindow
		var removeBtn 	= content.find('button.remove-marker')[0];
		var saveBtn 	= content.find('button.save-marker')[0];
		var datosBtn 	= content.find('button.mas-datos')[0];

		//add click listner to remove marker button
		google.maps.event.addDomListener(removeBtn, "click", function(event) {
			remove_marker(null,null,marker);
		});

		google.maps.event.addDomListener(datosBtn, "click", function(event) {
			//marker
			var mLatLang = marker.getPosition().toUrlValue(); //get marker position

		 var elem = mLatLang.split(',');
			var lat = elem[0];
			var lng = elem[1];
			$('#save-type').val();
			$('#nombre-obj').val($('.save-name').val());
			$('#descripcion-obj').val($('.save-desc').val());
			$("#modal_long_obj").val(lng);
			$("#modal_lat_obj").val(lat);
			$('#modal-datos').modal();
		});
		
		if(typeof saveBtn !== 'undefined') //continue only when save button is present
		{
			//add click listner to save marker button
			google.maps.event.addDomListener(saveBtn, "click", function(event) {
				var mReplace = content.find('div.pepe'); //html to be replaced after success
				var mName = content.find('input.save-name')[0].value; //name input field value
				var mDesc  = content.find('textarea.save-desc')[0].value; //description input field value
				var mType = content.find('select.save-type')[0].value; //type of marker
				var mTipo = '';
				switch(mType) {
						    case '1':
				    			mTipo = "Objeto"
				    			break;
				    		case '2':
				    			mTipo = "Animal"
				    			break;
				    		case '3':
				    			mTipo = "Persona"
				    			break;
						    default:
						        mTipo = "Objeto"
						}
				//var mImage = content.find('input.save-image')[0].value; //type of marker
				$('.nombre-alert').removeClass('has-error');
				$('.desc-alert').removeClass('has-error');
				$('.tipo-alert').removeClass('has-error');

				if(mName =='')
				{
					$('.nombre-alert').addClass('has-error');
				}else{
					if(mDesc == '')
					{
						$('.desc-alert').addClass('has-error');
					}else{
						if(mType == '0')
						{
							//agregar una alerta 
							$('.tipo-alert').addClass('has-error');
						}else{
							save_marker(marker, mName, mDesc, mType, mTipo, mReplace); //call save marker function
						}
					}
				} 
			});
		}
		
		//add click listner to save marker button		 
		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker); // click on marker opens info window 
	    });
		  infowindow.open(map,marker);
	}


	
	
	
	//############### Create Marker Function ##############
	function showMarkers(Id, MapPos, MapTitle, Path,  MapDesc, Type, iconPath, usuario_id){
		var baseurl = $('#baseurl').val().replace('index.php','');
		//crea el marker
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:false,
			animation: google.maps.Animation.DROP,
			icon: iconPath
		});

		//Content con la estructura html de la ventana q aparecera
		var contentString = muestraInfo(
							MapTitle,
							Type,
							baseurl+'/uploads/'+Path,
							MapDesc,
							Id
							);
		
		//Create una nueva ventana de informacion
		var infowindow = new google.maps.InfoWindow();
		//agregarle el contenido
		infowindow.setContent(contentString[0]);

		//Find remove button in infoWindow
		var showDetalle 	= contentString.find('button#show-detalle')[0];
		var removeBtn 	= contentString.find('.remove-marker')[0];
		if($('#isLoggin').val() == 'true'){
			if(parseInt($('#usuario_id').val()) == parseInt(usuario_id)){
			contentString.find('.remove-marker').removeClass('hide');
			}
		}
		google.maps.event.addDomListener(removeBtn, "click", function(event) {
				remove_marker(Id,MapTitle);
		});
		google.maps.event.addDomListener(showDetalle, "click", function(event) {
			cargaDatos(Id);
		});
		
		//accion al hacer click en un pin
		google.maps.event.addListener(marker, 'click', function() {
				if (infowindow) {
        			infowindow.close();
    			}
				infowindow.open(map,marker); // abre la ventana de informacion
	    });
	}

	function cargaDatos(Id){
		var baseurl = $('#baseurl').val().replace('index.php','');
		jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+Id, function (data) {
				jsondata = eval(data);
				missing = eval(jsondata.missing);
				var src = "http://maps.googleapis.com/maps/api/staticmap?center="+missing.latitud_objeto+","+missing.longitud_objeto+"&zoom=16&size=200x200&sensor=false";
				$('#modal-detalles').find('.share').attr('data-id', missing.id);
				$('#img_objeto').attr('src', baseurl+'/uploads/'+missing.path);
				$('#ubicacion').attr('src', src);
				$('#nom_objeto').text(missing.nombre_objeto);
				$('#desc_objeto').text("Descripción: "+missing.descripcion_objeto);
				$('#fecha_objeto').text("Fecha: "+missing.fecha);
				$('#dir_objeto').text("Dirección: "+missing.direccion_objeto);
				$('#tipo').text("Tipo: "+missing.tipo);
				$('#usuario_objeto').text("Creado por: "+missing.usuario);
			});
		$('#modal-detalles').modal();
	}


	$(document).on("click", "#carga_perfil", function() {
		var baseurl = $('#baseurl').val().replace('index.php','');
		/*jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+Id, function (data) {
				jsondata = eval(data);
				missing = eval(jsondata.missing);
				fecha = eval(missing.fecha);
				var src = "http://maps.googleapis.com/maps/api/staticmap?center="+missing.latitud_objeto+","+missing.longitud_objeto+"&zoom=16&size=200x200&sensor=false";
				$('#modal-detalles').find('.share').attr('data-id', missing.id);
				$('#img_objeto').attr('src', baseurl+'/uploads/'+missing.path);
				$('#ubicacion').attr('src', src);
				$('#nom_objeto').text(missing.nombre_objeto);
				$('#desc_objeto').text(missing.descripcion_objeto);
				$('#fecha_objeto').text(fecha.date);
				$('#dir_objeto').text(missing.direccion_objeto);
				$('#tipo').text(missing.tipo);
				$('#usuario_objeto').text(missing.usuario);
			});*/
		$('#modal-editar-perfil').modal();
	});
	
	//############### Remove Marker Function ##############
	function remove_marker(Id,nombre){
			$('.confirma-borrar').attr('data-id',Id);
			$('.nombre_missing').text(nombre);
			$('#confirm-delete-share').modal();
	}

	/*$(document).on("click", ".btn-seguir", function(){
		var nom_objeto = $(this).attr('data-nombre');
		var id = $(this).attr('data-id');
		$('#confirm-seguir').find('.nombre_missing').html(nom_objeto);
		$('#confirm-seguir').find('#missing-id').val(id);
		$('#confirm-seguir').modal();
	});*/

	$(document).on("click", ".btn-remove", function(){
		var nom_objeto = $(this).attr('data-nombre');
		var id = $(this).attr('data-id');
		remove_marker(id, nom_objeto);
	});
	

	$(document).on("click", ".confirma-borrar", function() {
		var Id = $(this).attr('data-id');	
		var myData = { id : Id };
		$.ajax({
			  type: "POST",
			  url: $('#baseurl').val()+"/borrarobjeto",
			  data: myData,
			  success:function(data){
			  	$('#confirm-delete-share').modal('hide');
			  		jQuery('#reload_map').click();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('error al borrar '+thrownError); //throw any errors
				}
			});
	}); 
	
	//############### Save Marker Function ##############
	function save_marker(Marker, mName, mDesc, mType, mTipo, replaceWin){
		//Save new marker using jQuery Ajax
		 var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
		 var baseurl = $('#baseurl').val().replace('index.php','');
		 var elem = mLatLang.split(',');
			var lat = elem[0];
			var lng = elem[1];
		var address = '';

		 $.ajax({
    			url: 'http://maps.google.com/maps/api/geocode/json?latlng='+lat+','+lng+'&sensor=false&callback=parseme',
    			dataType:'json',
    			success: function (response) {
    				jsondata = eval(response);
    				place = eval(jsondata.results[0]);
    				if (place.address_components) {
						  $.each(place.address_components, function(index, value) {
			                  country = '';
							  if (place.address_components[index].types[0] == 'country') {
			                      country = place.address_components[index].long_name;
			                  }
			                  if ((place.address_components[index].types[0] == 'locality' ) || (place.address_components[index].types[0] == 'administrative_area_level_3' && city == '' ) || (place.address_components[index].types[0] == 'postal_town' && city == ''  ) || (place.address_components[index].types[0] == 'administrative_area_level_1' && city == ''  )  || (place.address_components[index].types[0] == 'political' && city == ''  ) ) {
			                      city = place.address_components[index].long_name;
			                  }
			                  if (place.address_components[index].types[0] == 'administrative_area_level_1') {
			                      state = place.address_components[index].short_name;
			                  }
						  });
		    		}
		    	 address = city+','+state+', '+country;
		    	 var myData = {name : mName, desc : mDesc, address : address, latlang : mLatLang, type : mType }; //post variables	
				 $.ajax({
				 type: "POST",
				 url: baseurl+"/creaobjetosimple",
				 data: myData,
				 dataType:'json',
				  success:function(data){
				  		id = eval(data.id);
				  		//Content con la estructura html de la ventana q aparecera
						var contentString = muestraInfo(
							mName,
							mTipo,
							baseurl+'/uploads/default.png',
							mDesc,
							id
							);
				  		$('#modal-share').modal();
				  		$('#modal-share').find('.share').attr('data-id', id);
						 $('.marker-edit').html(contentString); // reemplazo el html con el nuevo html
						 $('.marker-edit').removeClass('marker-edit');
						 switch(mType) {
						    case '1':
				    			pin = baseurl+"/img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = baseurl+"/img/pin-1_green.png"
				    			break;
				    		case '3':
				    			pin = baseurl+"/img/pin-1_orange.png"
				    			break;
						    default:
						        pin = baseurl+"/img/pin-1_blue.png"
						}
						 Marker.setDraggable(false); //set marker to fixed
						 Marker.setIcon(pin); //replace icon

						 $('#show-detalle-succes').click(function(){
		             		cargaDatos(id);
		             	});

		             },
		             error:function (xhr, ajaxOptions, thrownError){
		                // alert('error al guardar'+thrownError); //throw any errors
		             },
		             complete: function(){
		             	
		             }
				 });
    			},
    			error: function(response){
    			}
   				});	 
	}

	var muestraInfo = function(nombre, tipo, img_path, desc, id){
		var contentString = $('.marker-info-win').clone();	
		contentString.removeClass('hide');
		contentString.find('.show-foto').attr('data-id', id);
		contentString.find('.title-info').html(nombre+' <small>'+tipo+'</small>');
		contentString.find('.img-info').attr('alt',nombre);
		contentString.find('.img-info').attr('src', img_path);
		contentString.find('.desc-info').text(desc);
		return contentString;
	}
});










