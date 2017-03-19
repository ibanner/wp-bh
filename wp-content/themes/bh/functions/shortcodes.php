<?php
/**
 * Shortcodes
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_btn
 *
 * This function adds the "btn" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function BH_btn( $atts ) {

	extract( shortcode_atts( array(
		'size'		=> 'big',
		'color'		=> 'default',
		'text'		=> '',
		'link'		=> 'http://',
		'target'	=> 'self'
	), $atts ) );
	
	if ( ! $text )
		// return
		return '';

	// return
	return '<a class="btn inline-btn ' . $color . '-btn ' . $size . '" href="' . $link . '" target="_' . $target . '">' . $text . '</a>';

}
//add_shortcode( 'btn', 'BH_btn' );

/**
 * BH_countdown
 *
 * This function adds the "countdown" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function BH_countdown( $atts ) {

	extract( shortcode_atts( array(
		'id'			=> 'first_countdown',
		'target_date'	=> '',
		'height'		=> '100'
	), $atts ) );

	if ( ! $target_date )
		// return
		return '';

	date_default_timezone_set('Asia/Jerusalem');
	$server_time = date('Y/n/j H:i:s');

	$countdown = 
		'<script>
			jQuery(document).ready(function($){
				$(\'#' . $id . '\').ResponsiveCountdown({
					server_now_date:"' . $server_time . '",
					target_date:"' . $target_date . '",
					time_zone:3,target_future:true,
					set_id:0,pan_id:0,day_digits:2,
					fillStyleSymbol1:"rgba(255, 255, 255, 1)",
					fillStyleSymbol2:"rgba(255, 255, 255, 1)",
					fillStylesPanel_g1_1:"rgba(0, 0, 0, 1)",
					fillStylesPanel_g1_2:"rgba(41, 51, 64, 1)",
					fillStylesPanel_g2_1:"rgba(0, 0, 0, 1)",
					fillStylesPanel_g2_2:"rgba(41, 51, 64, 1)",
					text_color:"rgba(0,0,0,1)",
					text_glow:"rgba(0,0,0,1)",
					show_ss:true,show_mm:true,
					show_hh:true,show_dd:true,
					f_family:"arial",show_labels:true,
					type3d:"single",max_height:' . $height . ',
					days_long:"ימים",days_short:"dd",
					hours_long:"שעות",hours_short:"hh",
					mins_long:"דקות",mins_short:"mm",
					secs_long:"שניות",secs_short:"ss",
					min_f_size:14,max_f_size:30,
					spacer:"none",groups_spacing:2,text_blur:2,
					font_to_digit_ratio:0.1,labels_space:1.2 
				});
			});
		</script>';

	// return
	return '<div id="' . $id . '" class="countdown" style="height: ' . $height . 'px;"></div>' . $countdown;

}
add_shortcode( 'countdown', 'BH_countdown' );

/**
 * BH_gallery
 *
 * This function adds the "BH-gallery" Shortcode
 *
 * @param	$atts (array)
 * @return	(string)
 */
function BH_gallery( $atts ) {

	extract( shortcode_atts( array(
		'id'		=> '',
		'title'		=> ''
	), $atts ) );

	if ( ! $id )
		// return
		return '';

	// return
	return BH_get_gallery_html( $id, $title );

}
add_shortcode( 'BH-gallery', 'BH_gallery' );