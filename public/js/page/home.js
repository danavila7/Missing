jQuery(document).ready(function() {

	$('#modal-loading').modal();

	var baseurl = jQuery('#baseurl').val();

	$(document).on("click", ".close", function() {	
		//$('#login-error').hide();
		$('#login-error').fadeOut( "slow" );
	});

	$('.navmenu').offcanvas()

	$(document).on("click", ".iniciar-sesion", function() {	
		$('#modal-login').modal();
	}); 

	$(document).on("click", ".sing-up", function() {	
		$('#modal-create-usuario').modal();
	}); 
	
	$(document).on("click", ".facelogin", function() {	
		var url = jQuery('#baseurl').val()+"/login/fb";
			window.location.href = url;
	});

	if (window.location.href.indexOf('share') > 0) {
	}

    if (window.location.href.indexOf('#/_=_') > 0) {
    window.location = window.location.href.replace(/#.*/, '#/');
	}
});