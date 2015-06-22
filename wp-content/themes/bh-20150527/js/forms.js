var $ = jQuery;

$(function() {

	$('.bh-form').each(function() {
		$(this).find('.form-footer').appendTo(this);
	});		
});