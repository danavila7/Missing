	var map;
	jQuery(document).ready(function() {

		//Login con Facebook


		$('#facelogin').click(function(){
			var url = jQuery('#baseurl').val()+"/login/fb/callback";
			window.location.href = url;
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
			jQuery.get(jQuery('#baseurl').val()+"/obtenerObjetos", function (data) {
				//json
				jsondata = eval(data);
				for(i=0; i< jsondata['objetos'].length;i++){
					info = eval(jsondata['objetos'][0]);
					  var name 		= info.name;
					  var address 	= '<p>'+ info.address +'</p>';
					  var type 		= info.type;
					  var path      =  info.path;
					  var point 	= new google.maps.LatLng(parseFloat(info.lat),parseFloat(info.lng));
					  create_marker(point, name, path, address, false, false, false, $('#baseurl').val()+"/img/pin_blue.png");
				}
			});
			jQuery.get(jQuery('#baseurl').val()+"/objetos", function (data) {
				jQuery(data).find("marker").each(function () {
					  var name 		= $(this).attr('name');
					  var address 	= '<p>'+ $(this).attr('address') +'</p>';
					  var type 		= $(this).attr('type');
					  var path      =  '';//$(this).attr('path');
					  var point 	= new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
					  create_marker(point, name, path, address, false, false, false, $('#baseurl').val()+"/img/pin_blue.png");
				});
			});	

			//escondemos el loader
			jQuery('.loader').hide();
			
			//Right Click to Drop a New Marker
			google.maps.event.addListener(map, 'rightclick', function(event) {
				//Edit form to be displayed with new marker
				var EditForm = '<p><div class="marker-edit">'+
				'<form action="ajax-save.php" method="POST" enctype="multipart/form-data" name="SaveMarker" id="SaveMarker">'+
				'<label for="pName"><span>Nombre :</span><input type="text" name="pName" class="save-name" placeholder="Ingresar Nombre" maxlength="40" /></label>'+
				'<label for="pDesc"><span>Descripci&oacute;n :</span><textarea name="pDesc" class="save-desc" placeholder="Ingresar Descripci&oacute;n" maxlength="150"></textarea></label>'+
				'<label for="pType"><span>Tipo :</span> <select name="pType" class="save-type"><option value="1">Objeto</option><option value="2">Animal</option><option value="3">Persona</option>'+
				'</select></label>'+
				'<label for="pImage"><span>Imagen :</span><input type="file" class="save-image"></label>'+
				'</form>'+
				'</div></p><button name="save-marker" class="save-marker btn btn-default">Guardar Objeto</button>';

				//Drop a new Marker with our Edit Form
				create_marker(event.latLng, 'Datos Missing', EditForm,'', true, true, true, $('#baseurl').val()+"/img/pin_green.png");
			});
										
	}


	
	
	
	//############### Create Marker Function ##############
	function create_marker(MapPos, MapTitle, Info,  MapDesc,  InfoOpenDefault, DragAble, Removable, iconPath)
	{	  	  		  
		
		//new marker
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:DragAble,
			animation: google.maps.Animation.DROP,
			title:"Hello World!",
			icon: iconPath
		});
		var contentString = '';
		if(Removable == false)
		{
			baseurl = $('#baseurl').val();
		//Content structure of info Window for the Markers
		contentString = $('<div class="marker-info-win">'+
		'<div class="marker-inner-win"><span class="info-content">'+
		'<h1 class="marker-heading">'+MapTitle+'</h1>'+
		'<img src ="'+baseurl+'/uploads/'+Info+'" alt="'+MapTitle+'" height="70" width="70" />'+
		'</span><button name="remove-marker" class="remove-marker btn btn-default" title="Borrar Missing">Borrar Missing</button>'+
		'</div></div>');	
		}else
		{
		//Content structure of info Window for the Markers
		contentString = $('<div class="marker-info-win">'+
		'<div class="marker-inner-win"><span class="info-content">'+
		'<h1 class="marker-heading">'+MapTitle+'</h1>'+
		Info+
		'</span><button name="remove-marker" class="remove-marker btn btn-default" title="Borrar Missing">Borrar Missing</button>'+
		'</div></div>');

		}
		
		//Create an infoWindow
		var infowindow = new google.maps.InfoWindow();
		//set the content of infoWindow
		infowindow.setContent(contentString[0]);

		//Find remove button in infoWindow
		var removeBtn 	= contentString.find('button.remove-marker')[0];
		var saveBtn 	= contentString.find('button.save-marker')[0];

		//add click listner to remove marker button
		google.maps.event.addDomListener(removeBtn, "click", function(event) {
			remove_marker(marker);
		});
		
		if(typeof saveBtn !== 'undefined') //continue only when save button is present
		{
			//add click listner to save marker button
			google.maps.event.addDomListener(saveBtn, "click", function(event) {
				var mReplace = contentString.find('span.info-content'); //html to be replaced after success
				var mName = contentString.find('input.save-name')[0].value; //name input field value
				var mDesc  = contentString.find('textarea.save-desc')[0].value; //description input field value
				var mType = contentString.find('select.save-type')[0].value; //type of marker
				var mImage = contentString.find('input.save-image')[0].value; //image input field value
				//alert(mImage);
				if(mName =='' || mDesc =='')
				{
					alert("Please enter Name and Description!");
				}else{
					save_marker(marker, mName, mDesc, mType, mReplace, mImage); //call save marker function
				}
			});
		}
		
		//add click listner to save marker button		 
		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker); // click on marker opens info window 
	    });
		  
		if(InfoOpenDefault) //whether info window should be open by default
		{
		  infowindow.open(map,marker);
		}
	}
	
	//############### Remove Marker Function ##############
	function remove_marker(Marker)
	{
		
		/* determine whether marker is draggable 
		new markers are draggable and saved markers are fixed */
		if(Marker.getDraggable()) 
		{
			Marker.setMap(null); //just remove new marker
		}
		else
		{
			//Remove saved marker from DB and map using jQuery Ajax
			var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
			var myData = {del : 'true', latlang : mLatLang}; //post variables
			$.ajax({
			  type: "POST",
			  url: $('#baseurl').val()+"/objetos",
			  data: myData,
			  success:function(data){
					Marker.setMap(null); 
					alert(data);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError); //throw any errors
				}
			});
		}

	}
	
	//############### Save Marker Function ##############
	function save_marker(Marker, mName, mAddress, mType, replaceWin, mImage)
	{
		alert('lala')
		//Save new marker using jQuery Ajax
		 var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
		 var myData = {name : mName, address : mAddress, latlang : mLatLang, type : mType }; //post variables
		 console.log(replaceWin);		
		 $.ajax({
		 type: "POST",
		 url: $('#baseurl').val()+"/objetos",
		 data: myData,
		  success:function(data){
				 replaceWin.html(data); //replace info window with new html
				 Marker.setDraggable(false); //set marker to fixed
				 Marker.setIcon('../img/pin_blue.png'); //replace icon
             },
             error:function (xhr, ajaxOptions, thrownError){
                 alert(thrownError); //throw any errors
             }
		 });
	}



});











