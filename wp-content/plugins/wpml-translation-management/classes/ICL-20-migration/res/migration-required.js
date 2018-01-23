/*globals jQuery, ajaxurl */
jQuery(document).ready(function () {
	'use strict';

	var notice = jQuery('[data-id="icl-20-migration"][data-group="icl-20-migration"]');
	var confirm;
	var startButton;

	if (notice.length) {
		confirm = notice.find('#wpml-icl20-migrate-confirm');
		startButton = notice.find('.notice-action.button-secondary.notice-action-button-secondary');
	}

	var userConfirms = function (e) {
		e.preventDefault();
		e.stopPropagation();

		if (confirm.prop('checked')) {
			jQuery.ajax(ajaxurl, {
				method:   'POST',
				data:     {
					action: confirm.data('action'),
					nonce:  confirm.data('nonce')
				},
				complete: function () {
					location.reload();
				}
			});
		}
	};

	var updateButton = function () {
		if (confirm.prop('checked')) {
			startButton.removeClass('disabled');
			startButton.on('click', userConfirms);
		} else {
			startButton.addClass('disabled');
			startButton.off('click');
		}
	};

	if (notice.length && confirm.length && startButton.length) {
		updateButton();
		confirm.on('click', updateButton);
	}
});