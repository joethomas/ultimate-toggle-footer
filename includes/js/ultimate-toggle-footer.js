jQuery(document).ready(function($) {
	var duration = 800;
	$('.joe_utf-trigger-wrap #trigger').click(function() {
		$('#joe_utf').slideToggle(duration);
		if (!$(this).hasClass('open') ) {
  			$('html, body').animate( {
  				scrollTop: $('#joe_utf').offset().top - 0
  			}, duration);
  		};
  		$(this).toggleClass('open');
	});
});