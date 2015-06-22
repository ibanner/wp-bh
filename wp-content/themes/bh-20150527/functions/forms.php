<?php

	/**
	 * overide Gravity Forms validation messsage
	 */

	function change_form_validation_message($message, $form){
		return '<div class="validation_error">There was a problem with your submission.<br />Please see errors bellow.</div>';
	}
	add_filter('gform_validation_message', 'change_form_validation_message', 10, 2);

?>