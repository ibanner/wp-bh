(function(document, window, $) {
'use strict';

$(function() {

	if ( ! $('#state-wrapper').length || ! $('#province-wrapper').length )
		return;
	
	$('#state-wrapper').hide();
	$('#province-wrapper').hide();

	// inject state select condition
	$('[name=country]').change(function() {
		var val = $(this).val();

		if ( val === 'United States of America' ) {
			$('#province-wrapper').hide();
			$('#state-wrapper').show();
		}
		else if ( val === 'Canada' ){
			$('#state-wrapper').hide();
			$('#province-wrapper').show();
		}
		else {
			$('#state-wrapper').hide();
			$('#province-wrapper').hide();	
		}
	});
});

})(document, window, jQuery);