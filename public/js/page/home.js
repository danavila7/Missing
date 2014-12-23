jQuery(document).ready(function() {


	$(document).on("click", ".back-app", function() {	
		var lat = $(this).attr('data-lat');
		var lng = $(this).attr('data-lng');
		sessionStorage.setItem('lat',lat);
		sessionStorage.setItem('lng',lng);
		sessionStorage.setItem('location',true);
		window.location = jQuery('#baseurl').val();
	});
	

	$(document).on("click", '#myTab a', function(e) {	
		e.preventDefault()
		var cl = $(this).attr('data-active');
		$('.tab-content').find('.tab-pane').removeClass('active');
		$('.tab-content').find('#'+cl).addClass('in active');
  		$(this).tab('show')
	});

	$('#modal-loading').modal();

	var baseurl = jQuery('#baseurl').val();

	$(document).on("click", ".close", function() {	
		//$('#login-error').hide();
		$('#login-error').fadeOut( "slow" );
	});

	$('.navmenu').offcanvas()

	$(document).on("mouseover", ".btn-siguiendo", function() {	
		$(this).text('Dejar de Seguir');
		$(this).removeClass('btn-info');
		$(this).addClass('btn-danger');
  	})

	$(document).on("mouseout", ".btn-siguiendo", function() {	
		$(this).text('Siguiendo');
    	$(this).removeClass('btn-danger');
		$(this).addClass('btn-info');
  	});

	$(document).on("click", ".iniciar-sesion", function() {	
		$('#modal-login').modal();
	}); 

	$(document).on("click", ".show-foto", function() {	
		id = $(this).attr('data-id');
		jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+id, function (data) {
			jsondata = eval(data);
			missing = eval(jsondata.missing);
			var share_imagen = baseurl+'/uploads/'+missing.path;
			$('#modal-foto').find('#foto_imagen').attr('src', share_imagen);
			$('#nombre_objero').text(missing.nombre_objeto);
		});
		$('#modal-foto').modal();
	}); 

	$(document).on("click", ".sing-up", function() {	
		$('#modal-create-usuario').modal();
	});

	if (window.location.href.indexOf('share') > 0) {
	}

    if (window.location.href.indexOf('#/_=_') > 0) {
    window.location = window.location.href.replace(/#.*/, '#/');
	}

	$(document).on("click", ".share-fb", function() {	
		var id = $(this).attr('data-id');
		var baseurl = $('#baseurl').val().replace('index.php','');
		jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+id, function (data) {
				jsondata = eval(data);
				missing = eval(jsondata.missing);
		var share_url = baseurl+"/share/"+id;
		var tipo_objeto = missing.tipo;
		var frase = '';
		if(missing.tipo_objeto == '3'){
			frase = 'Ayúdame a encontrar a esta Persona llamada ';
		}
		if(missing.tipo_objeto == '1'){
			frase = 'Ayúdame a encontrar este objeto ';
		}
		if(missing.tipo_objeto == '2'){
			frase = 'Ayúdame a encontrar esta mascota llamada ';
		}
		var share_text = frase+'"'+missing.nombre_objeto+'"';
		var share_subtitle = "Haz click en la foto para conocer más detalles.";
		var share_description = missing.descripcion_objeto;
		var share_imagen = baseurl+'/uploads/'+missing.path;
		var share_url = baseurl+'/#/share/'+id;
		postToFacebookDialog(
			share_url, 
			share_text, 
			share_subtitle, 
			share_description, 
			share_imagen, 
			share_url, 
			''
			);
			});
	});

	$(document).on("click", ".share-tw", function() {	
		
		var id = $(this).attr('data-id');


		var baseurl = $('#baseurl').val().replace('index.php','');
		jQuery.get(jQuery('#baseurl').val()+"/datosMissing/"+id, function (data) {
				jsondata = eval(data);
				missing = eval(jsondata.missing);
				var share_url = escape(window.location.href)+"share/"+id;
				var frase = '';
				if(missing.tipo_objeto == '3'){
					frase = 'Ayudame a encontrar a esta Persona llamada ';
				}
				if(missing.tipo_objeto == '1'){
					frase = 'Ayudame a encontrar este objeto ';
				}
				if(missing.tipo_objeto == '2'){
					frase = 'Ayudame a encontrar esta mascota llamada ';
				}
				var share_text = frase+'"'+missing.nombre_objeto+'"';
					window.open("https://twitter.com/share?url="+share_url+
						"&text="+share_text+'&via=missing_app', 
						'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
				});
	});

	$(document).on("click", ".share-go", function() {	
		var id = $(this).attr('data-id');
		window.open("https://plus.google.com/share?url="+escape(window.location.href)+"share/"+id+"&t="+document.title, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');

	});
});



function esMobile(){
	var valida = false;
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		valida = true;
	}
	return valida;
}