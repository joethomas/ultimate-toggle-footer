jQuery(document).ready(function($) {
	var duration = 800;
	$('.gt-more-info-trigger-wrap #trigger').click(function() {
		$('#gt-more-info').slideToggle(duration);
		if (!$(this).hasClass('open') ) {
  			$('html, body').animate( {
  				scrollTop: $('#gt-more-info').offset().top - 0
  			}, duration);
  		};
  		$(this).toggleClass('open');
	});
});