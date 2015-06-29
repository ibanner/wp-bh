<?php

	$additional_posted_data = array();		// additional posted data to be store before saving to DB
	
	add_filter('wpcf7_form_class_attr', 'form_class_attr');
	function form_class_attr($class) {
		$class .= ' bh-form';
		return $class;
	}
	
	/**
	 * custom tags
	 */
	
	// payment - custom tag to call a payment form
	// could be defined with 2 different forms:
	// [payment custom {en/he}]				: for custom payment form
	// [payment {gen/com} {en/he} {total}]	: for genealogy/communities forms
	wpcf7_add_shortcode( 'payment', 'create_payment_form', false );
	wpcf7_add_shortcode( 'payment*', 'create_payment_form', false );
	
	function create_payment_form( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		// $custom payment holds the relevant custom payment instance in case we are treating custom payment form
		global $custom_payment;
		
		$type					= $tag['type'];
		$transactionID_prefix	= $tag['options'][0];	// custom/gen/com
		$lang					= $tag['options'][1];	// en/he
		$price					= ( $transactionID_prefix == 'custom' && isset($custom_payment) ) ? $custom_payment->data['total'] : $tag['options'][2];
		
		if ( ! ('payment' == $type && $transactionID_prefix && $lang && $price) ) return '';
		
		// generate transaction ID
		$prefix = ( $transactionID_prefix == 'custom' && isset($custom_payment) ) ? substr( $custom_payment->data['transactionKey'], 0, 3 ) : $transactionID_prefix;
		$transactionID = BH_generate_transactionID($prefix);
		
		// store payment data for further processing via Pelecard iframe
		session_start();
		$_SESSION['payment_data']					= array();
		$_SESSION['payment_data']['goodUrl']		= TEMPLATE . '/functions/pelecard/payment-result.php';
		$_SESSION['payment_data']['errorUrl']		= TEMPLATE . '/functions/pelecard/payment-result.php';
		$_SESSION['payment_data']['total']			= $price * 100;
		$_SESSION['payment_data']['currency']		= '1';
		$_SESSION['payment_data']['transactionID']	= $transactionID;
		
		// display Pelecard iframe
		?>
			<script>
				$(function() {
					var payment_form =
						'<script>' +
							'$(document).ajaxSuccess(function(event, xhr, settings) {' +
								// parse xhr responseText
								'var response = JSON.parse(xhr.responseText);' +
								
								'if (typeof response.paymentStep == "undefined" || response.paymentStep != "processing")' +
									'return;' +
								
								// hide contact form 7 form
								'$(".bh-form").fadeOut();' +
								
								// expose payment form
								'$("#payment-form").fadeIn();' +
							'});' +
						'</scr'+'ipt>' +
						
						'<div id="payment-form" style="display: none;">' +
							'<iframe id="frame" name="pelecard_frame" frameborder="0" scrolling="no" src="' + '<?php echo TEMPLATE; ?>' + '/functions/pelecard/Pay' + '<?php echo ($lang == 'he') ? '-he' : ''; ?>' + '.php" style="height:480px; width:400px;"></iframe>' +
						'</div>';
						
					// move payment form after contact form 7 form
					$('.bh-form').after(payment_form);
				});
			</script>
		<?php
	}
	
	add_filter( 'wpcf7_validate_payment', 'wpcf7_payment_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_payment*', 'wpcf7_payment_validation_filter', 10, 2 );
	
	function wpcf7_payment_validation_filter( $result, $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		global $custom_payment;
		
		$type					= $tag['type'];
		$transactionID_prefix	= $tag['options'][0];	// custom/gen/com
		$lang					= $tag['options'][1];	// en/he
		$price					= ( $transactionID_prefix == 'custom' && isset($custom_payment) ) ? $custom_payment->data['total'] : $tag['options'][2];
		
		if ( 'payment' == $type && $transactionID_prefix && $lang && $price ) {
			// set priority lower than 10 in order to call make_payment before insert record to DB by the plugin "Contact Form 7 to DB"
			add_action( 'wpcf7_before_send_mail', 'make_payment', 5, 1 );
		}
		
		return $result;
	}
	
	// paymentkey - custom tag (custom payment form)
	wpcf7_add_shortcode( 'paymentkey', 'create_paymentkey_input', true );
	wpcf7_add_shortcode( 'paymentkey*', 'create_paymentkey_input', true );
	
	$custom_payment = null;
	
	function create_paymentkey_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		$key	= $_GET['key'];
		
		$custom_payment	= $GLOBALS['customPaymentManager']->getCustomPayment($key);
		
		if ( ! $custom_payment ) {
			die('The payment does not exist or has been outdated.');
		}
		
		if ( $custom_payment->data['paid'] ) {
			die('This payment has been received. Thank you.');
		}
		
		$payment_key = $custom_payment ? $custom_payment->data['paymentKey'] : 'Wrong Key';
		
		// store custom payment key for further processing after payment has completed
		session_start();
		$_SESSION['custom_payment_key'] = $custom_payment ? $custom_payment->data['paymentKey'] : '';
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input type="hidden" readonly="true" value="' . $payment_key . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("paymentkey").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_paymentkey', 'wpcf7_paymentkey_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_paymentkey*', 'wpcf7_paymentkey_validation_filter', 10, 2 );
	
	function wpcf7_paymentkey_validation_filter( $result, $tag ) {
		global $custom_payment;
		
		$type			= $tag['basetype'];
		$name			= $tag['name'];
		$payment_key	= $_POST[$name];
		
		$custom_payment	= $GLOBALS['customPaymentManager']->getCustomPayment($payment_key);
		
		if ( 'paymentkey' == $type ) {
			if ( ! isset( $payment_key ) || empty( $payment_key ) && '0' !== $payment_key ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
			elseif ( !$custom_payment || $custom_payment->data['paymentKey'] !== $payment_key || $custom_payment->data['paid'] ) {
				$result['valid'] = false;
				$result['reason'][$name] = 'wrong key';
			}
		}
		
		return $result;
	}
	
	// transactionkey - custom tag (custom payment form)
	wpcf7_add_shortcode( 'transactionkey', 'create_transactionkey_input', true );
	wpcf7_add_shortcode( 'transactionkey*', 'create_transactionkey_input', true );
	
	function create_transactionkey_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		$transaction_key = $custom_payment ? $custom_payment->data['transactionKey'] : '';
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input readonly="true" value="' . $transaction_key . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("' . $name . '").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_transactionkey', 'wpcf7_transactionkey_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_transactionkey*', 'wpcf7_transactionkey_validation_filter', 10, 2 );
	
	function wpcf7_transactionkey_validation_filter( $result, $tag ) {
		global $custom_payment;
		
		$type				= $tag['basetype'];
		$name				= $tag['name'];
		$transaction_key	= $_POST[$name];
		
		if ( 'transactionkey' == $type ) {
			if ( ! isset( $transaction_key ) || empty( $transaction_key ) && '0' !== $transaction_key ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
			elseif ( !$custom_payment || $custom_payment->data['transactionKey'] !== $transaction_key ) {
				$result['valid'] = false;
				$result['reason'][$name] = get_transactionkey_error();
			}
		}
		
		return $result;
	}
	
	// total - custom tag (custom payment form)
	wpcf7_add_shortcode( 'total', 'create_total_input', true );
	wpcf7_add_shortcode( 'total*', 'create_total_input', true );
	
	function create_total_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		$total	= $custom_payment ? $custom_payment->data['total'] : '';
		
		$output	= '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output	.= '<input readonly="true" value="' . $total . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("' . $name . '").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_total', 'wpcf7_total_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_total*', 'wpcf7_total_validation_filter', 10, 2 );
	
	function wpcf7_total_validation_filter( $result, $tag ) {
		global $custom_payment;
		
		$type	= $tag['basetype'];
		$name	= $tag['name'];
		$total	= $_POST[$name];
		
		if ( 'total' == $type ) {
			if ( ! isset( $total ) || empty( $total ) && '0' !== $total ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
			elseif ( !$custom_payment || $custom_payment->data['total'] !== $total ) {
				$result['valid'] = false;
				$result['reason'][$name] = get_total_error();
			}
		}
		
		return $result;
	}
	
	// event - custom tag
	wpcf7_add_shortcode( 'event', 'create_event_select', true );
	wpcf7_add_shortcode( 'event*', 'create_event_select', true );
	
	function create_event_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		// query event marked for display in form
		$args = array(
			'post_type'         => 'event',
			'posts_per_page'    => -1,
			'no_found_rows'		=> true,
			'orderby'           => 'title',
			'order'             => 'ASC',
			'meta_query'        => array(
				'relation' => 'AND',
				array(
					'key' => 'acf-event_registration_form',
					'value' => true
				),
				array(
					'key'		=> 'acf-event_end_date',
					'value'		=> date_i18n('Ymd'),
					'type'		=> 'DATE',
					'compare'	=> '>='
				)
			)
		);
		$events_query = new WP_Query($args);
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'event*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'event*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		
		global $post;
		
		if ($events_query->have_posts()) : while($events_query->have_posts()) : $events_query->the_post();
		
			$id = $post->ID;
			$title = $post->post_title;
			
			if ( isset($_GET['event_id']) && $id == $_GET['event_id'] ) {
				$output .= '<option value="' . $title . '" selected>' . $title . '</option>';
			}
			else {
				$output .= '<option value="' . $title . '">' . $title . '</option>';
			}
			
		endwhile; endif; wp_reset_postdata();
		
		$output .= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_event', 'wpcf7_event_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_event*', 'wpcf7_event_validation_filter', 10, 2 );
	
	function wpcf7_event_validation_filter( $result, $tag ) {
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		if ( 'event*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// country - custom tag
	wpcf7_add_shortcode( 'country', 'create_country_select', true );
	wpcf7_add_shortcode( 'country*', 'create_country_select', true );
	
	function create_country_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		$output	= '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output	.= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'country*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'country*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output	.= file_get_contents('wp-content/themes/bh/views/components/country-list.html');
		$output	.= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_country', 'wpcf7_country_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_country*', 'wpcf7_country_validation_filter', 10, 2 );
	
	function wpcf7_country_validation_filter( $result, $tag ) {
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		if ( 'country*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// state - custom tag
	wpcf7_add_shortcode( 'state', 'create_state_select', true );
	wpcf7_add_shortcode( 'state*', 'create_state_select', true );
	
	function create_state_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		$output	= '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output	.= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'state*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'state*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output	.= file_get_contents('wp-content/themes/bh/views/components/state-list.html');
		$output	.= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_state', 'wpcf7_state_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_state*', 'wpcf7_state_validation_filter', 10, 2 );
	
	function wpcf7_state_validation_filter( $result, $tag ) {
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		if ( 'state*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// province - custom tag
	wpcf7_add_shortcode( 'province', 'create_province_select', true );
	wpcf7_add_shortcode( 'province*', 'create_province_select', true );
	
	function create_province_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		$output	= '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output	.= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'province*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'province*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output	.= file_get_contents('wp-content/themes/bh/views/components/province-list.html');
		$output	.= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_province', 'wpcf7_province_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_province*', 'wpcf7_province_validation_filter', 10, 2 );
	
	function wpcf7_province_validation_filter( $result, $tag ) {
		$type	= $tag['type'];
		$name	= $tag['name'];
		
		if ( 'province*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// formfooter - custom tag
	wpcf7_add_shortcode( 'formfooter', 'create_form_footer', true );
	
	function create_form_footer( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$form_footer = get_field('acf-options_form_footer', 'option');
		
		return '<div class="form-footer">' . $form_footer . '</div>';
	}
	
	/**
	 * make_payment
	 * 
	 * called as part of 'payment' custom tag validation process
	 * called after a successful form validation and before contact form 7 mail submission
	 * 
	 * handles 'processing' stage (before pelecard iframe payment submission)
	 */
	function make_payment($wpcf7_data) {
		global $additional_posted_data, $custom_payment;
		
		$submission = WPCF7_Submission::get_instance();
		
		// update posted data with payment step as 'processing'
		$additional_posted_data['paymentStep'] = 'processing';
		
		// store custom payment issuer and customer Emails
		if ( isset($custom_payment) ) {
			$_SESSION['payment_data']['issuer-email']	= $custom_payment->data['issuerEmail'];
			$_SESSION['payment_data']['customer-email']	= $custom_payment->data['customerEmail'];
		}
		
		// store serialized WPCF7_ContactForm and WPCF7_Submission objects
		if ( isset( $_SESSION['payment_data'] ) ) :
			$_SESSION['payment_data']['contact_form']	= serialize($wpcf7_data);
			$_SESSION['payment_data']['form_instance']	= serialize($submission);
		endif;
		
		// prevent mail submission by contact form 7
		$wpcf7_data->skip_mail = true;
		
		add_filter('wpcf7_ajax_json_echo', 'kill_form', 10, 3);
	}
	
	function kill_form($items, $result) {
		// update $items with success indication (mailSent = true, even so mail wasn't actually sent) and payment step as processing
		$items['mailSent'] = true;
		$items['paymentStep'] = 'processing';
		
		return $items;
	}
	
	/**
	 * filter CFDB form data
	 * set posted data before saving to DB
	 */
	add_filter('cfdb_form_data', 'set_cfdb_posted_data');
	function set_cfdb_posted_data($formData) {
		global $additional_posted_data;
		
		if ($formData && $additional_posted_data)
			// add $additional_posted_data to formData->posted_data
			foreach ($additional_posted_data as $key => $val)
				$formData->posted_data[$key] = $val;
				
		return $formData;
	}
	
	/**
	 * filter 'tel' tag
	 */
	add_filter('wpcf7_is_tel', 'validate_phone', 11, 2);
	function validate_phone($result, $tel) {
		if ( strlen($tel) < 9 ) {
			$result = false;
		}
		
		return $result;
	}
	
	/**
	 * 'file' tag validation
	 * call save_file() function
	 */
	add_filter( 'wpcf7_validate_file', 'check_file', 11, 2 );
	add_filter( 'wpcf7_validate_file*', 'check_file', 11, 2 );
	
	function check_file($result, $tag) {
		$name	= $tag['name'];
		
		if ( $result['valid'] && !isset( $result['reason'][$name] ) ) {
			add_action( 'wpcf7_before_send_mail', 'save_file', 10, 1 );
		}
		
		return $result;
	}
	
	/**
	 * save_file
	 * 
	 * zip uploaded gedcom file and save it in a dedicated folder
	 */
	function save_file($wpcf7_data) {
		$submission		= WPCF7_Submission::get_instance();
		$uploaded_files	= $submission->uploaded_files();
		
		if ( ! $uploaded_files )
			return;
			
		$upload_dir				= wp_upload_dir();
		$folder					= 'gedcom';
		$first_uploaded_file	= reset($uploaded_files);
		$zip_filename			= time() . substr( $first_uploaded_file, strrpos($first_uploaded_file, '/') + 1 ) . '.zip';
		$zip_path				= $upload_dir['path'] . '/' . $folder . '/' . $zip_filename;
		
		// create the archive based on the first uploaded file name
		$zip = new ZipArchive();
		if ( $zip->open($zip_path, ZIPARCHIVE::CREATE) ) :
		
			// add uploaded file to zip file
			foreach ($uploaded_files as $file) :
				$path	= $file;
				$name	= substr( $path, strrpos($path, '/') + 1 );
				$zip->addFile($path, $name);
			endforeach;
			
			@chmod($zip_path, 0664);
			
			// skip contact form 7 mail in order to manually send it with gedcom url
			$wpcf7_data->skip_mail = true;
			add_filter('wpcf7_ajax_json_echo', 'kill_form', 10, 3);
			
			// manually send mail with gedcom url
			$posted_data				= $submission->get_posted_data();
			$posted_data['gedcom-url']	= $upload_dir['url'] . '/' . $folder . '/' . $zip_filename;
			
			$mail	= $wpcf7_data->prop('mail');
			$mail_2	= $wpcf7_data->prop('mail_2');
			
			$mail	= BH_build_message($mail, $posted_data);
			$mail_2	= BH_build_message($mail_2, $posted_data);
			
			// send mail messages
			$result = WPCF7_Mail::send($mail, 'mail');
			
			if ($result) {
				if ( $mail_2 && $mail_2['active'] ) {
					WPCF7_Mail::send( $mail_2, 'mail_2' );
				}
			}
			
			// document the completed request
			$cfdb_data = (object) array (
				'title'				=> $wpcf7_data->title(),
				'posted_data'		=> $posted_data,
				'uploaded_files'	=> $uploaded_files
			);
			do_action_ref_array( 'cfdb_submit', array(&$cfdb_data) );
			
		endif;
	}