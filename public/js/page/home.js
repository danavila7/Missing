jQuery(document).ready(function() {

	

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
			frase = 'Ayudame a encontrar a esta Persona llamada ';
		}
		if(missing.tipo_objeto == '1'){
			frase = 'Ayudame a encontrar este objeto ';
		}
		if(missing.tipo_objeto == '2'){
			frase = 'Ayudame a encontrar esta mascota llamada ';
		}
		var share_text = frase+'"'+missing.nombre_objeto+'"';
		var share_subtitle = "Comparte esta publicación para que más gente me pueda ayudar.";
		var share_description = missing.descripcion_objeto;
		var share_imagen = baseurl+'/uploads/'+missing.path;
		postToFacebookDialog(
			baseurl, 
			share_text, 
			share_subtitle, 
			share_description, 
			share_imagen, 
			baseurl, 
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