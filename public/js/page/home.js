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

			FB.ui({
		method: 'send',
		name: 'lalala',
		link: jQuery('#baseurl').val()+"share/"+id,
	});
			/*FB.ui(
			  {
			    method: 'share',
			    href: jQuery('#baseurl').val()+"share/"+id,
			  },
			  function(response) {
			    if (response && !response.error_code) {
			      alert('Posting completed.');
			    } else {
			      alert('Error while posting.');
			    }
			  }
			);*/

		
    	//window.open("https://www.facebook.com/sharer/sharer.php?u="+escape(window.location.href)+"share/"+id+"&t="+document.title, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
	});

	$(document).on("click", ".share-tw", function() {	
		var id = $(this).attr('data-id');
		window.open("https://twitter.com/share?url="+escape(window.location.href)+"share/"+id+"&t="+document.title, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
	});

	$(document).on("click", ".share-go", function() {	
		var id = $(this).attr('data-id');
		window.open("https://plus.google.com/share?url="+escape(window.location.href)+"share/"+id+"&t="+document.title, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');

});
});