(function(document, window, $) {
'use strict';

// prevent Enter key from submitting form
$('.bh-form').keydown(function(e) {
	var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 	
 	return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
});

// green validation icons
$(document).ajaxComplete(function() {
	var wrapper = $('.wpcf7-form-control-wrap');
			
	wrapper.each(function() {
		if ( $(this).has('.wpcf7-not-valid-tip').length == 0 && $('.bh-form').hasClass('invalid') ) {
			
			$(this).append('<span class="wpcf7-valid-tip"></span>');	
		}
		else {
			
			$(this).find('.wpcf7-valid-tip').remove();;
		}
	});
});


$(function() {

	// move footer to end of form
	$('.bh-form').each(function() {
		$(this).find('.form-footer').appendTo(this);
	});		
});

})(document, window, jQuery);
