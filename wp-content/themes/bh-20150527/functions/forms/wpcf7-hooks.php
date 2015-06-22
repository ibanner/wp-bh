<?php

	/**
	 * Make sure that Contact Form 7 is installed
	 */
	function is_cf7_installed() {
		return class_exists("WPCF7_ContactForm");
	}
	
	add_action('init', 'enqueue_forms_script', 12);
	function enqueue_forms_script() {
		if (!is_cf7_installed()) return;
		
		wp_enqueue_script('forms');
	}
	
	add_action('wpcf7_before_send_mail', 'add_headers', 10, 1);
	function add_headers($wpcf7_data) {
		$wpcf7_data->mail['additional_headers'] .= "Reply-To: " . $wpcf7_data->posted_data['email'] . "\r\n";
	}
	
	add_filter('wpcf7_form_class_attr', 'form_class_attr');
	function form_class_attr($class) {
		$class .= ' bh-form';
		return $class;
	}
	
	require_once('payment-gateway.php');
	
	function make_payment($wpcf7_data) {
		global $pelecardApi, $pelecardRequest, $paymentData, $custom_payment;
		
		if ( isset($custom_payment) ) {
			$transactionKey = substr($custom_payment->data['transactionKey'], 0, 3);
		}
		else {
			$transactionKeyMap = array(
				'jewish genealogy'      => 'gen',
				'jewish genealogy he'   => 'gen',
				'communities'           => 'com',
				'communities he'        => 'com'
			);
			
			$form_name = $wpcf7_data->title;
			$transactionKey = $transactionKeyMap[$form_name];
		}
		
		try {
			$pelecardRequest = $pelecardApi->makePayment($paymentData, $transactionKey);
			$pelecardResponse = $pelecardRequest->getResponse();
			
			if ( $pelecardResponse->isSuccessful() ) {
				$transaction_id = $pelecardResponse->getID();
				$submission = WPCF7_Submission::get_instance();
				
				if ( isset($custom_payment) ) {
					$custom_payment->paid($transaction_id);
					$submission->posted_data['issuer-email'] = $custom_payment->data['issuerEmail'];
					$submission->posted_data['customer-email'] = $custom_payment->data['customerEmail'];
				}
				
				$submission->posted_data['transactionid'] = $transaction_id;
				$submission->posted_data['total'] = strval( $pelecardResponse->getTotal() ) . ' ' . strtoupper( $pelecardApi->getCurrencyName( $pelecardResponse->getCurrencyCode() ) );
				
				add_filter('wpcf7_ajax_json_echo', 'check_mail', 10, 2);
			}
			else {
				failed_payment($wpcf7_data);
			}
		}
		catch (Exception $e) {
			failed_payment($wpcf7_data);
		}
	}
	
	function check_mail($items, $response) {
		if ( !$items['mailSent'] ) {}
		
		return $items;
	}
	
	function failed_payment($wpcf7_data) {
		$wpcf7_data->skip_mail = true;
		add_filter( 'wpcf7_ajax_json_echo', 'kill_form', 10, 2 );
	}
	
	function kill_form($items, $result) {
		global $pelecardRequest;
		
		if ( $items['mailSent'] ) {
			$items['mailSent'] = false;
			$items['message'] = get_reason($pelecardRequest);
		}
		
		return $items;
	}
	
	// cardno - custom tag
	wpcf7_add_shortcode( 'cardno', 'create_cardno_input', true );
	wpcf7_add_shortcode( 'cardno*', 'create_cardno_input', true );
	
	function create_cardno_input( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield' . ( ($type == 'cardno*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'cardno*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_cardno', 'wpcf7_cardno_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_cardno*', 'wpcf7_cardno_validation_filter', 10, 2 );
	
	function wpcf7_cardno_validation_filter( $result, $tag ) {
		global $paymentData;
		
		$type = $tag['basetype'];
		$name = $tag['name'];
		$form_name = $tag['raw_values'][0];
		
		if ( 'cardno' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
			else {
				// set form price in NIS
				if ( !isset( $paymentData['total'] ) ) {
					$prices = get_field('acf-options_services_costs', 'option');
					
					foreach ($prices as $entry) {
						if ( strtolower($entry['service_id']) == strtolower($form_name) ) {
							$paymentData['total'] = $entry['cost'];
						}
					}
				}
				
				add_action( 'wpcf7_before_send_mail', 'make_payment', 10, 1 );
			}
		}
		
		return $result;
	}
	
	// custom-payment-key - custom tag
	wpcf7_add_shortcode( 'paymentkey', 'create_paymentkey_input', true );
	wpcf7_add_shortcode( 'paymentkey*', 'create_paymentkey_input', true );
	
	$custom_payment = null;
	
	function create_paymentkey_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		$key = $_GET['key'];
		
		$custom_payment = $GLOBALS['customPaymentManager']->getCustomPayment($key);
		
		if ( !$custom_payment ) {
			die('The payment does not exist or has been outdated.');
		}
		
		if ( $custom_payment->data['paid'] ) {
			die('This payment has been received. Thank you.');
		}
		
		$payment_key = $custom_payment ? $custom_payment->data['paymentKey'] : 'Wrong Key';
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input type="hidden" readonly="true" value="' . $payment_key . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("paymentkey").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_paymentkey', 'wpcf7_paymentkey_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_paymentkey*', 'wpcf7_paymentkey_validation_filter', 10, 2 );
	
	function wpcf7_paymentkey_validation_filter( $result, $tag ) {
		global $custom_payment;
		
		$type = $tag['basetype'];
		$name = $tag['name'];
		$payment_key = $_POST[$name];
		
		$custom_payment = $GLOBALS['customPaymentManager']->getCustomPayment($payment_key);
		
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
	
	// transactionkey - custom tag
	wpcf7_add_shortcode( 'transactionkey', 'create_transactionkey_input', true );
	wpcf7_add_shortcode( 'transactionkey*', 'create_transactionkey_input', true );
	
	function create_transactionkey_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$transaction_key = $custom_payment ? $custom_payment->data['transactionKey'] : '';
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input readonly="true" value="' . $transaction_key . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("' . $name . '").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_transactionkey', 'wpcf7_transactionkey_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_transactionkey*', 'wpcf7_transactionkey_validation_filter', 10, 2 );
	
	function wpcf7_transactionkey_validation_filter( $result, $tag ) {
		global $custom_payment;
		
		$type = $tag['basetype'];
		$name = $tag['name'];
		$transaction_key = $_POST[$name];
		
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
	
	// total - custom tag
	wpcf7_add_shortcode( 'total', 'create_total_input', true );
	wpcf7_add_shortcode( 'total*', 'create_total_input', true );
	
	function create_total_input( $tag ) {
		global $custom_payment;
		
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$total = $custom_payment ? $custom_payment->data['total'] : '';
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<input readonly="true" value="' . $total . '" name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-text inputfield wpcf7-validates-as-required" aria-required="true" onchange="document.getElementById("' . $name . '").value=this.value;" /></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_total', 'wpcf7_total_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_total*', 'wpcf7_total_validation_filter', 10, 2 );
	
	function wpcf7_total_validation_filter( $result, $tag ) {
		global $custom_payment, $paymentData;
		
		$type = $tag['basetype'];
		$name = $tag['name'];
		$total = $_POST[$name];
		
		if ( 'total' == $type ) {
			if ( ! isset( $total ) || empty( $total ) && '0' !== $total ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
			elseif ( !$custom_payment || $custom_payment->data['total'] !== $total ) {
				$result['valid'] = false;
				$result['reason'][$name] = get_total_error();
			}
			else {
				// set form price in NIS
				$paymentData['total'] = $custom_payment->data['total'];
			}
		}
		
		return $result;
	}
	
	// event - custom tag
	wpcf7_add_shortcode( 'event', 'create_event_select', true );
	wpcf7_add_shortcode( 'event*', 'create_event_select', true );
	
	function create_event_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		// query event marked for display in form
		$args = array(
			'post_type'         => 'event',
			'posts_per_page'    => -1,
			'no_found_rows'		=> true,
			'orderby'           => 'title',
			'order'             => 'ASC',
			'meta_query'        => array(
				array(
					'key' => 'acf-event_registration_form',
					'value' => true
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
		$type = $tag['type'];
		$name = $tag['name'];
		
		if ( 'event*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// expyear - custom tag
	wpcf7_add_shortcode( 'expyear', 'create_expyear_select', true );
	wpcf7_add_shortcode( 'expyear*', 'create_expyear_select', true );
	
	function create_expyear_select( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'expyears*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'expyears*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		
		$current_year = intval(date('y'));
		
		for ($i = $current_year; $i < 100; $i++) :
			$output .= '<option value="' . $i . '">' . $i . '</option>';
		endfor;
		
		$output .= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_expyear', 'wpcf7_expyear_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_expyear*', 'wpcf7_expyear_validation_filter', 10, 2 );
	
	function wpcf7_expyear_validation_filter( $result, $tag ) {
		$type = $tag['type'];
		$name = $tag['name'];
		
		if ( 'expyear*' == $type ) {
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
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'country*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'country*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output .= file_get_contents('wp-content/themes/bh/views/components/country-list.html');
		$output .= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_country', 'wpcf7_country_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_country*', 'wpcf7_country_validation_filter', 10, 2 );
	
	function wpcf7_country_validation_filter( $result, $tag ) {
		$type = $tag['type'];
		$name = $tag['name'];
		
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
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'state*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'state*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output .= file_get_contents('wp-content/themes/bh/views/components/state-list.html');
		$output .= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_state', 'wpcf7_state_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_state*', 'wpcf7_state_validation_filter', 10, 2 );
	
	function wpcf7_state_validation_filter( $result, $tag ) {
		$type = $tag['type'];
		$name = $tag['name'];
		
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
		
		$type = $tag['type'];
		$name = $tag['name'];
		
		$output = '<span class="wpcf7-form-control-wrap ' . $name . '">';
		$output .= '<select name="' . $name . '" id="' . $name . '" class="wpcf7-form-control wpcf7-select inputfield' . ( ($type == 'province*') ? ' wpcf7-validates-as-required' : '' ) . '"' . ( ($type == 'province*') ? ' aria-required="true"' : '' ) . 'onchange="document.getElementById("' . $name . '").value=this.value;"><option value="">---</option>';
		$output .= file_get_contents('wp-content/themes/bh/views/components/province-list.html');
		$output .= '</select></span>';
		
		return $output;
	}
	
	add_filter( 'wpcf7_validate_province', 'wpcf7_province_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_province*', 'wpcf7_province_validation_filter', 10, 2 );
	
	function wpcf7_province_validation_filter( $result, $tag ) {
		$type = $tag['type'];
		$name = $tag['name'];
		
		if ( 'province*' == $type ) {
			if ( ! isset( $_POST[$name] ) || empty( $_POST[$name] ) && '0' !== $_POST[$name] ) {
				$result['valid'] = false;
				$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
			}
		}
		
		return $result;
	}
	
	// form-footer - custom tag
	wpcf7_add_shortcode( 'form-footer', 'create_form_footer', true );
	
	function create_form_footer( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$form_footer = get_field('acf-options_form_footer_' . ICL_LANGUAGE_CODE, 'option');
		
		return '<div class="form-footer">' . $form_footer . '</div>';
	}
	
	// handle-state - custom tag to enqueue state-handler javascript
	wpcf7_add_shortcode( 'handle-state', 'enqueue_state_handler', true );
	
	function enqueue_state_handler( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		wp_enqueue_script('state-handler');
		
		return '';
	}
	
	// handle-items - custom tag to enqueue item-handler javascript
	wpcf7_add_shortcode( 'handle-items', 'enqueue_item_handler', true );
	
	function enqueue_item_handler( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		wp_enqueue_script('item-handler');
		
		return '';
	}
	
	// script - custom tag to enqueue javascript
	wpcf7_add_shortcode( 'script', 'form_enqueue_script', true );
	
	function form_enqueue_script( $tag ) {
		if ( !is_array( $tag ) ) return '';
		
		$script_name = $tag['raw_values'][0];
		
		wp_enqueue_script($script_name);
		
		return '';
	}
	
	add_filter('wpcf7_is_tel', 'validate_phone', 11, 2);
	function validate_phone($result, $tel) {
		if ( strlen($tel) < 9 ) {
			$result = false;
		}
		
		return $result;
	}
	
	add_filter( 'wpcf7_validate_file', 'check_file', 11, 2 );
	add_filter( 'wpcf7_validate_file*', 'check_file', 11, 2 );
	
	function check_file($result, $tag) {
		$name = $tag['name'];
		
		if ( $result['valid'] && !isset( $result['reason'][$name] ) ) {
			add_action( 'wpcf7_before_send_mail', 'save_file', 10, 1 );
		}
		
		return $result;
	}
	
	function save_file($wpcf7_data) {
		foreach ($_FILES as $key => $file) {
			$upload_dir = wp_upload_dir();
			$folder = 'gedcom';
			$filename = time() . $file['name'];
			$target = $upload_dir['path'] . '/' . $folder . '/' . $filename;
			$zip_file = $target . '.zip';
			$submission = WPCF7_Submission::get_instance();
			
			// create the archive
			$zip = new ZipArchive();
			if($zip->open($zip_file,ZIPARCHIVE::CREATE) === true) {
				$saved = $zip->addFile($submission->uploaded_files[$key], $file['name']);
			} else {
				$saved = copy($submission->uploaded_files[$key], $target);
			}
			
			if ( $saved ) {
				@chmod($target, 0664);
				$submission->posted_data['gedcom-url'] = $upload_dir['baseurl'] . '/' . $folder . '/' . $filename . '.zip';
			} else {
				$wpcf7_data->skip_mail = true;
				add_filter( 'wpcf7_ajax_json_echo', 'kill_form', 10, 2 );
			}
		}
	}

?>