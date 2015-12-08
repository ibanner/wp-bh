<?php

	/**
	 * Make sure that Contact Form 7 is installed
	 */
	function is_cf7_installed() {
		return class_exists("WPCF7_ContactForm");
	}
	
	/**
	 * overide Gravity Forms validation messsage
	 */
	function change_form_validation_message($message, $form){
		return '<div class="validation_error">There was a problem with your submission.<br />Please see errors bellow.</div>';
	}
	add_filter('gform_validation_message', 'change_form_validation_message', 10, 2);
	
	/**
	 * BH_generate_transactionID
	 * 
	 * generate transaction ID for pelecard payment parmx field
	 * 
	 * @param	string		$form_title		form title
	 * @return	string						generated transaction ID
	 */
	function BH_generate_transactionID($form_title) {
		if ( ! $form_title )
			return '';
			
		$datatime		= date_i18n('d') . date_i18n('m') . date_i18n('y') . date_i18n('H') . date_i18n('i');
		$rand_digits	= 4;
		$rand_number	= str_pad(rand(0, pow(10, $rand_digits)-1), $rand_digits, '0', STR_PAD_LEFT);
		$transactionID = $form_title . $datatime . '-' . $rand_number;
		
		return $transactionID;
	}

	/**
	 * BH_build_message
	 * 
	 * replace tags in mail template with form posted data
	 * 
	 * @param	array		$template		mail template
	 * @param	array		$posted_data	form posted data
	 * @return	array						modified mail template
	 */
	function BH_build_message($template, $posted_data) {
		if ( ! $template || ! $posted_data )
			return '';
			
		$mail = $template;
		
		foreach ( $posted_data as $key => $val ) :
			$data_val = $val;
			
			if ( is_array($val) ) :
				$data_val = '';
				
				foreach ($val as $int_val)
					$data_val .= ( ($data_val != '') ? ', ' : '' ) . $int_val;
			endif;
			
			$mail = str_replace('['.$key.']', $data_val, $mail);
		endforeach;
		
		return $mail;
	}