var $ = jQuery.noConflict();

// bind form and send data
$(document).ready(function() {
	var options = {
		beforeSubmit	: validateForm,
		success			: showResult
	};
	
	$('.widget-active_trail_newsletter-form').ajaxForm(options);
});

function validateForm(formData, jqForm, options) {
	var txtMail, newsletterGroups = [];

	// mail
	txtMail = formData[1].value;

	// newsletter groups
	$.each(formData, function(key, value) {
		if (key<2)
			return;

		newsletterGroups.push(value.value);
	});

	var	txtMailInput		= jqForm.find('.mm_newemail');
		result_container	= jqForm.next('.result-container'),
		mailErr				= jqForm.find('.mailErr'),
		newsletterErr		= jqForm.find('.newsletterErr'),
		emailFilter			= /^.+@.+\..{2,3}$/,
		res					= true;
	
	// mail validation
	if ( txtMail == null || !(emailFilter.test(txtMail)) ) {
		mailErr.removeClass('hide');
		txtMailInput.focus();
		res = false;
	}
	else {
		mailErr.addClass('hide');
	}
	
	// newsletter groups validation
	if (newsletterGroups.length == 0) {
		newsletterErr.removeClass('hide');
		res = false;
	}
	else {
		newsletterErr.addClass('hide');
	}
	
	if (!res)
		return false;
		
	// prepare result container
	result_container.find('.msg').addClass('hide');
	result_container.find('.loader').removeClass('hide');
	
	return true;
}

function showResult(responseText, statusText, xhr, jqForm) {
	var	result_container	= jqForm.next('.result-container');

	if (statusText == 'success') {
		if (responseText=='0') {
			result_container.find('.result').removeClass('error').addClass('success');
			result_container.find('.msg-0').removeClass('hide');
		}
		else {
			result_container.find('.result').removeClass('success').addClass('error');
			result_container.find('.msg-1').removeClass('hide');
		}
		
		result_container.find('.loader').addClass('hide');
		
		return true;
	}
	else {
		result_container.find('.result').removeClass('success').addClass('error');
		result_container.find('.msg-999').removeClass('hide');
		result_container.find('.loader').addClass('hide');
		
		return false;
	}
}