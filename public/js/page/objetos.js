	var map;
	jQuery(document).ready(function() {

		/**** datepicker para la fecha de perdida ****/
		$('#fecha_perdida').datepicker({
   			 todayHighlight: true
		});

		/***** click para esconder el texto de recompensa en el formulario ****/
		$(document).on("click", "#recompensa_check", function() {
			if($(this).is('checked')){
				$('#recompensa_text').fadeOut( "slow" );
			}else{
				$('#recompensa_text').fadeIn( "slow" );
			}
		});
		

		/***** click y buscar en el mapa ***/
		$(document).on("click", ".buscamissing", function() {
  		var lat = $(this).attr('data-lat');
		var lng = $(this).attr('data-lng');
		$(".buscamissing").each(function( index ) {
			$(this).removeClass('active');
		});

		$(this).addClass('active');
		var marker2 = new google.maps.Marker({ position: new google.maps.LatLng(lat, lng), map: map, title: 'my 2nd title'});
		map.panTo(marker2.getPosition());
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
				map_initialize(mapCenter); // initialize google map
			  }

			}

	/**** si el usuario permite la localidad del navegador ****/
	if(navigator.geolocation) {
    browserSupportFlag = true;
    	navigator.geolocation.getCurrentPosition(function(position) {
      	var currentMapCenter = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
      	map_initialize(currentMapCenter);
    	}, function() {

    	});
  	}
	
	var mapCenter = new google.maps.LatLng(-33.437118,-70.650544); //Google map Coordinates

	
	//map_initialize(mapCenter); // initialize google map
	
	//############### Google Map Initialize ##############
	function map_initialize(mapCenter){
			var googleMapOptions = 
			{ 
				center: mapCenter, // map center
				zoom: 17, //zoom level, 0 = earth view to higher value
				maxZoom: 18,
				minZoom: 10,
				zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL //zoom control size
			},
				scaleControl: true, // enable scale control
				mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
			};
		
		   	map = new google.maps.Map(document.getElementById("map"), googleMapOptions);			
			//Load Markers from the XML File, Check (map_process.php)
			/*jQuery.get(jQuery('#baseurl').val()+"/obtenerObjetos", function (data) {
				//json
				jsondata = eval(data);
				for(i=0; i< jsondata['objetos'].length;i++){
					info = eval(jsondata['objetos'][0]);
					  var name 		= info.nombre_objeto;
					  var desc 	= '<p>'+ info.descripcion_objeto +'</p>';
					  var type 		= info.tipoobjeto_id;
					  var path      =  '';//info.foto_objeto;
					  var point 	= new google.maps.LatLng(parseFloat(info.latitud_objeto),parseFloat(info.longitud_objeto));
					  //alert('json'+info.tipoobjeto_id)
					  //create_marker(point, name, path, desc, false, false, false, $('#baseurl').val()+"/img/pin_blue.png");
				}
			});*/
			jQuery.get(jQuery('#baseurl').val()+"/objetos", function (data) {
				jQuery(data).find("marker").each(function () {
					//alert($(this).attr('name'))
					  var id 		= $(this).attr('id');
					  var name 		= $(this).attr('name');
					  var desc 	    = '<p>'+ $(this).attr('address') +'</p>';
					  var type 		= $(this).attr('type');
					  var typeid 		= $(this).attr('typeid');
					  var path      =  $(this).attr('path');
					  var point 	= new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
					  var pin = "/img/pin-1_blue.png";
					  switch(typeid) {
						    case '1':
				    			pin = "/img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = "/img/pin-1_green.png"
				    			break;
				    		case '3':
				    			pin = "/img/pin-1_orange.png"
				    			break;
						    default:
						        pin = "/img/pin-1_blue.png"
						}
					  showMarkers(id, point, name, path, desc, type, $('#baseurl').val()+pin);
				});
			});	

			//escondemos el loader
			jQuery('.loader').hide();
			//Right Click
			google.maps.event.addListener(map, 'rightclick', function(event) {
				if($('#isLoggin').val() == 'false'){
						$('#modal-login').modal();
					}else{
					createMarker(event.latLng, $('#baseurl').val()+"/img/pin_green.png");
					}
			});				
	}


	//############### Create Marker Function ##############
	function createMarker(MapPos, iconPath)
	{
		//new marker
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:true,
			animation: google.maps.Animation.DROP,
			icon: iconPath
		});
		var contentString = $('<div class="marker-edit">'+
				'<h4>Missing<small> Ingresar datos </small><button type="button" class="mas-datos btn btn-primary btn-xs">Más Datos</button> </h4>'+
                '<form role="form" enctype="multipart/form-data" name="SaveMarker" id="SaveMarker" >'+
                  '<div class="form-group nombre-alert">'+
                    '<label>Nombre*</label>'+
                    '<input type="text" class="form-control save-name" name="pName" placeholder="Ingresar Nombre" maxlength="40" required>'+
                  '</div>'+
                  '<div class="form-group desc-alert">'+
                    '<label>Descripci&oacute;n*</label>'+
                    '<textarea type="text" class="form-control save-desc" name="pDesc" placeholder="Ingresar Descripci&oacute;n" maxlength="250" required>'+
                    '</textarea>'+
                  '</div>'+
                  '<div class="form-group">'+
                    '<label class="col-sm-2 control-label">Tipo*</label>'+
                    '<div class="col-sm-10">'+
                    '<select name="pType" class="save-type form-control tipo-alert" required><option value="0">Selecciona una opción</option><option value="1">Objeto</option><option value="2">Animal</option><option value="3">Persona</option>'+
                    '</select>'+
                  '</div>'+
                  '</div>'+
                '</form>'+
                '<br/>'+
                '<div class="btn-group">'+
				  '<button type="button" class="save-marker btn btn-default">Guardar</button>'+
				  '<button type="button" class="remove-marker btn btn-default">Cancelar</button>'+
				'</div>'+
        		'</div>');
		
		//Create an infoWindow
		var infowindow = new google.maps.InfoWindow();
		//set the content of infoWindow
		infowindow.setContent(contentString[0]);

		//Find remove button in infoWindow
		var removeBtn 	= contentString.find('button.remove-marker')[0];
		var saveBtn 	= contentString.find('button.save-marker')[0];
		var datosBtn 	= contentString.find('button.mas-datos')[0];

		//add click listner to remove marker button
		google.maps.event.addDomListener(removeBtn, "click", function(event) {
			remove_marker(null,null,marker);
		});

		google.maps.event.addDomListener(datosBtn, "click", function(event) {
			$('#modal-datos').modal();
		});
		
		if(typeof saveBtn !== 'undefined') //continue only when save button is present
		{
			//add click listner to save marker button
			google.maps.event.addDomListener(saveBtn, "click", function(event) {
				var mReplace = contentString.find('div.pepe'); //html to be replaced after success
				var mName = contentString.find('input.save-name')[0].value; //name input field value
				var mDesc  = contentString.find('textarea.save-desc')[0].value; //description input field value
				var mType = contentString.find('select.save-type')[0].value; //type of marker
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
				//var mImage = contentString.find('input.save-image')[0].value; //type of marker
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
	function showMarkers(Id, MapPos, MapTitle, Path,  MapDesc, Type, iconPath)
	{
		var baseurl = $('#baseurl').val();
		//Marker del xml
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:false,
			animation: google.maps.Animation.DROP,
			icon: iconPath
		});
		var contentString = '';
		//Content structure of info Window for the Markers
		contentString = 
		$('<div class="marker-info-win">'+
		'<div class="row">'+
		'<div class="col-sm-5 col-sm-offset-2">'+
		'<h4> '+MapTitle+' <small> '+Type+' </small></h4>'+
		'</div>'+
		'</div>'+
		'<div class="row">'+
		'<div class="col-sm-2 col-sm-offset-1">'+
		'<img src ="'+baseurl+'/uploads/'+Path+'" alt="'+MapTitle+'" class="img-rounded" height="70" width="70" />'+
		'</div>'+
		'<div class="col-sm-6 col-sm-offset-1">'+
		'<p class="bg-info">'+MapDesc+'</p>'+
		'</div>'+
		'</div>'+
		'<div class="row">'+
		'<div class="btn-group col-sm-offset-5">'+
		'<button type="button" id="show-detalle" class="save-marker btn btn-default">Detalles</button>'+
		'<button type="button" class="remove-marker btn btn-default">Borrar</button>'+
		'</div>'+
		'</div>'+
		'</div>');	
		
		//Create an infoWindow
		var infowindow = new google.maps.InfoWindow();
		//set the content of infoWindow
		infowindow.setContent(contentString[0]);

		//Find remove button in infoWindow
		var showDetalle 	= contentString.find('button#show-detalle')[0];
		var removeBtn 	= contentString.find('button.remove-marker')[0];
		//add click listner to remove marker button
		google.maps.event.addDomListener(removeBtn, "click", function(event) {
			remove_marker(Id,MapTitle,marker);
		});
		google.maps.event.addDomListener(showDetalle, "click", function(event) {
			cargaDatos(Id);
		});
		
		//add click listner to save marker button		 
		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker); // click on marker opens info window 
	    });
	}

	function cargaDatos(Id){

		jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+Id, function (data) {

				jsondata = eval(data);
				missing = eval(jsondata.missing);
				fecha = eval(missing.fecha);
				$('#nom_objeto').text(missing.nombre_objeto);
				$('#desc_objeto').text(missing.descripcion_objeto);
				$('#fecha_objeto').text(fecha.date);
				$('#dir_objeto').text(missing.direccion_objeto);
				$('#tipo').text(missing.tipo);
				$('#usuario_objeto').text(missing.usuario);
			});
		$('#modal-detalles').modal();
	}
	
	//############### Remove Marker Function ##############
	function remove_marker(Id,nombre,Marker)
	{
		
		/* determine whether marker is draggable 
		new markers are draggable and saved markers are fixed */
		if(Marker.getDraggable()) 
		{
			Marker.setMap(null); //just remove new marker
		}
		else
		{
			if (confirm('¿Esta seguro que desea borrar este Missing?')) {
			var myData = { id : Id };
			$.ajax({
			  type: "POST",
			  url: $('#baseurl').val()+"/borrarObjeto",
			  data: myData,
			  success:function(data){
					Marker.setMap(null); 
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('error al borrar '+thrownError); //throw any errors
				}
			});
			}
			/*$('.confirma-borrar').attr('data-marker',Marker);
			$('.confirma-borrar').attr('data-id',Id);
			$('.nombre_missing').text(nombre);
			$('#confirm-delete-share').modal();*/
		}
	}

	$(document).on("click", ".confirma-borrar", function() {
		var Id = $(this).attr('data-id');	
		var myData = { id : Id };
		var Marker = $(this).data('marker');	
		$.ajax({
			  type: "POST",
			  url: $('#baseurl').val()+"/borrarObjeto",
			  data: myData,
			  success:function(data){
			  	alert(Marker)
					Marker.setMap(null); 
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('error al borrar '+thrownError); //throw any errors
				}
			});
	}); 
	
	//############### Save Marker Function ##############
	function save_marker(Marker, mName, mDesc, mType, mTipo, replaceWin)
	{
		//Save new marker using jQuery Ajax
		 var mLatLang = Marker.getPosition().toUrlValue(); //get marker position

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
				 console.log(replaceWin);		
				 $.ajax({
				 type: "POST",
				 url: $('#baseurl').val()+"/objetos",
				 data: myData,
				 dataType:'json',
				  success:function(data){
				  		id = eval(data.id);
				  		var html = $('<div class="marker-info-win">'+
						'<div class="row">'+
						'<div class="col-sm-5 col-sm-offset-2">'+
						'<h4> '+mName+' <small> '+mTipo+' </small></h4>'+
						'</div>'+
						'</div>'+
						'<div class="row">'+
						'<div class="col-sm-2 col-sm-offset-1">'+
						'<img src ="'+$('#baseurl').val()+'/uploads/default.png" alt="'+mName+'" class="img-rounded" height="70" width="70" />'+
						'</div>'+
						'<div class="col-sm-6 col-sm-offset-1">'+
						'<p>'+mDesc+'</p>'+
						'</div>'+
						'</div>'+
						'<div class="row">'+
						'<div class="btn-group col-sm-offset-5">'+
						'<button type="button" id="show-detalle-succes" class="save-marker btn btn-default">Detalles</button>'+
						'<button type="button" class="remove-marker btn btn-default">Borrar</button>'+
						'</div>'+
						'</div>'+
						'</div>');
				  		$('#modal-share').modal();
						 $('.marker-edit').html(html); //replace info window with new html
						 $('.marker-edit').removeClass('marker-edit');
						 switch(mType) {
						    case '1':
				    			pin = "../img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = "../img/pin-1_green.png"
				    			break;
				    		case '3':
				    			pin = "../img/pin-1_orange.png"
				    			break;
						    default:
						        pin = "../img/pin-1_blue.png"
						}
						 Marker.setDraggable(false); //set marker to fixed
						 Marker.setIcon(pin); //replace icon

						 $('#show-detalle-succes').click(function(){
		             		cargaDatos(id);
		             	});

		             },
		             error:function (xhr, ajaxOptions, thrownError){
		                 alert('error al guardar'+thrownError); //throw any errors
		             },
		             complete: function(){
		             	
		             }
				 });
    			},
    			error: function(response){
    			}
   				});


		 
	}



});











