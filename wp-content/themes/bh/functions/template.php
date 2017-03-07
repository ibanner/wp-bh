<?php
/**
 * Template functions
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_shop_cart_popup
 *
 * This function returns the Shop Mini Cart button and popup to be displayed as part of header elements
 *
 * @param	$header_position (string) Header position [top/mid]
 * @param	$sidebar (string) Sidebar HTML
 * @return	(string) Shop mini cart button and popup or empty string in case of input failure
 */
function BH_shop_cart_popup( $header_position, $sidebar ) {

	if ( ! $header_position || ! $sidebar )
		// return
		return '';

	$output = '<div class="shop-cart-popup-btn">';

	if ( $header_position == 'top' )
		$output .= '<a class="sprite-cart" href="' . WC()->cart->get_cart_url() . '"></a>';
	else
		$output .= '<button class="sprite-cart"></button>';

	// Insert shopping cart indicator placeholder - code in woocommerce.js will update this on page load
	$output .= '<div class="widget_shopping_cart_indicator"></div>';

	if ( $header_position == 'mid' )
		$output .= '</div>';

	$output .= '<div class="shop-cart-popup-content">';
		$output .= $sidebar;
	$output .= '</div>';

	if ( $header_position == 'top' )
		$output .= '</div>';

	// return
	return $output;

}

/**
 * BH_newsletter_popup
 *
 * This function returns the Newsletter button and popup to be displayed as part of header elements
 *
 * @param	$header_position (string) Header position [top/mid]
 * @param	$sidebar (string) Sidebar HTML
 * @return	(string) Newsletter button and popup or empty string in case of input failure
 */
function BH_newsletter_popup( $header_position, $sidebar ) {

	if ( ! $header_position || ! $sidebar )
		// return
		return '';

	// Manipulate $sidebar to contain $header_position info
	// Used to make a distinguish between group checkboxes of different widget instances
	$sidebar = preg_replace( "/\"mm_key\[([a-z\-]+)\]\"/", "\"mm_key[$1-" . $header_position . "]\"", $sidebar );

	$output = '<div class="newsletter-popup-btn">';
		$output .= '<button class="label">' . __('ENews', 'BH') . '</button>';
	$output .= '</div>';

	$output .= '<div class="newsletter-popup-content">';
		$output .= $sidebar;
		$output .= '<span class="glyphicon glyphicon-remove"></span>';
	$output .= '</div>';

	// return
	return $output;

}

/**
 * BH_header_links_n_icons
 * 
 * This function returns the icons and links to be displayed as part of header elements
 * 
 * @param	N/A
 * @return	(string)
 */
function BH_header_links_n_icons() {

	ob_start();
	
	get_template_part( 'views/header/header-elements' );
	$output = ob_get_contents();
	
	ob_end_clean();
	
	// return
	return $output;

}

/**
 * BH_get_contact_details
 * 
 * This function returns the homepage contact details section
 * 
 * @param	N/A
 * @return	(string)
 */
function BH_get_contact_details() {

	ob_start();
	
	get_template_part( 'views/main/contact-details/contact-details' );
	$output = ob_get_contents();
	
	ob_end_clean();
	
	// return
	return $output;

}

/**
 * set_opening_hours_msg
 * 
 * This function updates BH-opening-hours-msg transient according to opening hours settings
 * 
 * @param	$code (string) WPML language code
 * @return	N/A
 */
function set_opening_hours_msg( $code ) {

	// change acf current language
	if ($code) {

		add_filter('acf/settings/current_language',	function() {
			global $code;
			return $code;
		});

		global $wpdb;
		$locale = $wpdb->get_var( "SELECT default_locale FROM {$wpdb->prefix}icl_languages WHERE code='{$code}'" );
		setlocale( LC_TIME, $locale . '.utf8' );

	}

	// get opening hours data
	$hours				= get_field( 'acf-options_opening_hours', 'option' );

	// get messages
	$open_msg			= get_field( 'acf-options_open_message', 'option' );
	$close_msg			= get_field( 'acf-options_close_message', 'option' );
	$opening_today_msg	= get_field( 'acf-options_opening_today_message', 'option' );

	// get some strings related to above messages
	$tommorow_str		= get_field( 'acf-options_tomorrow_str', 'option' );
	$on_day_str			= get_field( 'acf-options_on_day_str', 'option' );

	if ( $hours && $open_msg && $close_msg && $opening_today_msg && $tommorow_str && $on_day_str ) :

		$status			= 'close';			// [open/close/opening-today]
		$msg			= '';				// message to be displayed

		$current_day	= date_i18n('w');	// w => numeric representation of the day of the week - 0 (for Sunday) through 6 (for Saturday)
		$current_time	= date_i18n('Hi');	// H => 24-hour format of an hour with leading zeros; i => Minutes with leading zeros

		// locate the closest row in $hours
		$row			= '';

		foreach ( $hours as $hours_row ) :

			if ( $hours_row['day'] >= $current_day ) :

				if ( $hours_row['day'] == $current_day ) {
					if ( $current_time < $hours_row['open'] ) {

						// before opening hour today
						$status = 'opening-today';
						$row = $hours_row;
						break;

					} elseif ( $current_time >= $hours_row['open'] && $current_time <= $hours_row['close'] ) {

						// open now
						$status = 'open';
						$row = $hours_row;
						break;

					} else {

						// after closing hour today
						continue;

					}

				} else {

					// open on a later day
					$row = $hours_row;
					break;

				}

			endif;

		endforeach;

		// no match found, first row will be considered
		if (!$row) {
			$row = $hours[0];
		}

		// build the message
		while ( has_sub_field( 'acf-options_opening_hours', 'option' ) ) {

			$open_select	= get_sub_field_object('open');
			$close_select	= get_sub_field_object('close');
			
			$day	= $row['day'];
			$open	= $open_select['choices'][$row['open']];
			$close	= $close_select['choices'][$row['close']];
			
			break;

		}

		if		($status == 'open')				{ $msg = sprintf( $open_msg, $close ); }
		elseif	($status == 'opening-today')	{ $msg = sprintf( $opening_today_msg, $open ); }
		else									{ $msg = sprintf( $close_msg, ( ($current_day == $row['day']-1) ? $tommorow_str : $on_day_str . ' ' . strftime( '%A', strtotime('next Sunday + ' . $day . ' days') ) ), $open ); }

		$transient_key = 'BH-opening-hours-msg' . ( $code ? '-' . $code : '' );

		if ( $msg )
			set_transient( $transient_key, '<div class="msg msg-' . $status . '">' . $msg . '</div>' );
		else
			delete_transient( $transient_key );

	endif;

}

/**
 * BH_get_gallery_html
 *
 * This function returns gallery HTML markup
 *
 * @param	$id (int) gallery Post Type ID
 * @param	$title (string) gallery Post Type title
 * @return	(string)
 */
function BH_get_gallery_html( $id, $title ) {

	if ( ! function_exists('get_field') )
		// return
		return '';

	// Get variables
	$images	= get_field( 'images', $id );
	$output	= '';

	if ( ! $images )
		// return
		return $output;

	// Globals
	global $globals;

	$output .= '<!-- Gallery #' . $id . ' --><div class="gallery-layout-content">';
	$output .= $title ? '<h2 class="title">' . $title . '</h2><hr />' : '';
	$output .= '<div class="gallery gallery-' . $id . ' row" itemscope itemtype="http://schema.org/ImageGallery">';

	$gallery_images = array();

	foreach ( $images as $i ) {

		$image = array(
			'title'		=> esc_attr( BH_trim_str( $i['title'] ) ),
			'alt'		=> esc_attr( BH_trim_str( $i['alt'] ) ),
			'caption'	=> esc_attr( BH_trim_str( $i['caption'] ) ),
			'url'		=> esc_attr( BH_trim_str( $i['url'] ) )
		);

		$gallery_images[] = $image;

	}

	if ( $gallery_images ) {

		$i = 0;
		while ( $i <= 2 ) {
			$output .= '<div class="gallery-col col' . $i++ . ' col-xs-4"></div>';
		}

	}

	$output .= '</div>';

	if ( $gallery_images ) {

		$output .= '<button class="btn load-more inline-btn cyan-btn big">' . __('Load more', 'BH') . '</button>';
		$globals['_galleries']['gallery-'.$id] = $gallery_images;

	}

	$output .= '</div><!-- End of Gallery #' . $id . ' -->';

	// return
	return $output;

}