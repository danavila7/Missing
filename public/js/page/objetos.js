	var map;
	jQuery(document).ready(function() {

		$('#modal-loading').modal();
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
				map_initialize(mapCenter,lat,lng); // initialize google map
			  }

			}

	/**** si el usuario permite la localidad del navegador ****/
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
	
	
	
	//############### Google Map Initialize ##############
	function map_initialize(mapCenter,lat,lng){

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
			jQuery.get(jQuery('#baseurl').val()+"/obtenerTodosMissing", function (data) {
				//json
				jsondata = eval(data);
				for(i=0; i< jsondata['objetos'].length;i++){
						info = eval(jsondata['objetos'][i]);
						var id = info.id;
						var  usuario_id = info.usuario_id;
						var nombre_objeto 	= info.nombre_objeto;
					  	var descripcion_objeto 	= '<p>'+ info.descripcion_objeto +'</p>';
					  	var tipo 		= info.tipo;
					  	var tipoobjeto_id 	= info.tipoobjeto_id;
					  	var path      =  info.foto_objeto;
					  	var point 	= new google.maps.LatLng(parseFloat(info.latitud_objeto),parseFloat(info.longitud_objeto));
					  	var pin = "/img/pin-1_blue.png";
					  	switch(tipoobjeto_id) {
						    case '1':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_green.png"
				    			break;;
				    		case '3':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_orange.png"
				    			break;
						    default:
						        pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_blue.png"
						}
						showMarkers(id, point, nombre_objeto, path, descripcion_objeto, tipo, pin, usuario_id);
				}
			});
			//escondemos el loader
			//$('#modal-loading').modal('hide')
			jQuery('.loader').hide();
			//Right Click
			google.maps.event.addListener(map, 'rightclick', function(event) {
				if($('#isLoggin').val() == 'false'){
						$('#modal-login').modal();
					}else{
					createMarker(event.latLng, "http://appmissing.missing.cl/Missing/public/img/pin-1_green.png");
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
	function showMarkers(Id, MapPos, MapTitle, Path,  MapDesc, Type, iconPath, usuario_id)
	{
		var baseurl = $('#baseurl').val().replace('index.php','');
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
		var button = '';
		if($('#isLoggin').val() == 'true'){
			if(parseInt($('#usuario_id').val()) == parseInt(usuario_id)){
				button = '<button type="button" class="remove-marker btn btn-default">Borrar</button>';
			}
		}
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
		button+
		'</div>'+
		'</div>'+
		'</div>');	
		
		//Create an infoWindow
		var infowindow = new google.maps.InfoWindow();
		//set the content of infoWindow
		infowindow.setContent(contentString[0]);

		//Find remove button in infoWindow
		var showDetalle 	= contentString.find('button#show-detalle')[0];
		if($('#isLoggin').val() == 'true'){
			//alert($('#usuario_id').val())
			//alert(usuario_id)
			if($('#usuario_id').val() == usuario_id){
			var removeBtn 	= contentString.find('button.remove-marker')[0];
			//add click listner to remove marker button
			google.maps.event.addDomListener(removeBtn, "click", function(event) {
				remove_marker(Id,MapTitle,marker);
			});
			}
		}
		google.maps.event.addDomListener(showDetalle, "click", function(event) {
			cargaDatos(Id);
		});
		
		//add click listner to save marker button		 
		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker); // click on marker opens info window 
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
				$('#desc_objeto').text(missing.descripcion_objeto);
				$('#fecha_objeto').text(missing.fecha);
				$('#dir_objeto').text(missing.direccion_objeto);
				$('#tipo').text(missing.tipo);
				$('#usuario_objeto').text(missing.usuario);
			});
		$('#modal-detalles').modal();
	}


	function cargaPerfil(Id){
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

	/*$(document).on("click", ".btn-seguir", function(){
		var nom_objeto = $(this).attr('data-nombre');
		var id = $(this).attr('data-id');
		$('#confirm-seguir').find('.nombre_missing').html(nom_objeto);
		$('#confirm-seguir').find('#missing-id').val(id);
		$('#confirm-seguir').modal();
	});*/

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
						'<img src ="http://appmissing.missing.cl/Missing/public/uploads/default.png" alt="'+mName+'" class="img-rounded" height="70" width="70" />'+
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
				  		$('#modal-share').find('.share').attr('data-id', id);
						 $('.marker-edit').html(html); //replace info window with new html
						 $('.marker-edit').removeClass('marker-edit');
						 switch(mType) {
						    case '1':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_blue.png"
				    			break;
				    		case '2':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_green.png"
				    			break;
				    		case '3':
				    			pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_orange.png"
				    			break;
						    default:
						        pin = "http://appmissing.missing.cl/Missing/public/img/pin-1_blue.png"
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










