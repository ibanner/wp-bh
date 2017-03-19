<?php
/**
 * Utilities
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_remove_more_jump_link
 *
 * This function removes read more jump link
 *
 * @param	$link (string) Read more link
 * @return	(string)
 */
function BH_remove_more_jump_link( $link ) {

	$offset = strpos( $link, '#more-' );

	if ( $offset ) {
		$end = strpos( $link, '"',$offset );
	}

	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}

	// return
	return $link;

}
add_filter( 'the_content_more_link', 'BH_remove_more_jump_link' );

/**
 * BH_excerpt_more_link
 *
 * This function tweaks excerpt more link
 *
 * @param	$more (string)
 * @return	(string)
 */
function BH_excerpt_more_link( $more ) {

	//global $post;

	//$read_more_btn = get_field( 'acf-options_event_btn_read_more', 'option' );

	//return '...<div class="read-more"><a class="btn inline-btn red-btn big" href="' . get_permalink($post->ID) . '">' . $read_more_btn . '</a></div>';

	// return
	return ' [...]';

}
add_filter( 'excerpt_more', 'BH_excerpt_more_link' );

/**
 * archive posts per page
 */

/**
 * BH_archive_posts_per_page
 *
 * This function sets unlimited posts for archive pages
 *
 * @param	$query (object)
 * @return	(string)
 */
function BH_archive_posts_per_page( $query ) {

	if ( is_admin() || ! $query->is_main_query() )
		// return
		return;

	if ( is_archive() ) {

		$query->set( 'posts_per_page', -1 );

		// return
		return;

	}

}
add_action( 'pre_get_posts', 'BH_archive_posts_per_page', 1 );

/**
 * Separate media categories from post categories
 * use a custom category called 'category_media' for the categories in the media library
 */
add_filter( 'wpmediacategory_taxonomy', function(){ return 'category_media'; }, 1 ); //requires PHP 5.3 or newer

/**
 * BH_strip_tags_content
 * 
 * This function strips tags from HTML string
 * 
 * Examples:
 * Sample text:
 * $text = '<b>sample</b> text with <div>tags</div>';
 * 
 * Result for strip_tags_content($text, '<b>'):
 * <b>sample</b> text with
 * 
 * Result for strip_tags_content($text, '<b>', TRUE);
 * text with <div>tags</div>
 * 
 * @param	$text (string) HTML string
 * @param	$tags (string) HTML tags to include/exclude
 * @param	$invert (bool) Include (false) / exclude (true)
 * @return	(string)
 */
function BH_strip_tags_content( $text, $tags = '', $invert = FALSE ) {

	preg_match_all( '/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags );
	$tags = array_unique( $tags[1] );

	if( is_array($tags) && count($tags) > 0 ) {

		if( $invert == FALSE ) {
			// return
			return preg_replace( '@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text );
		}
		else {
			// return
			return preg_replace( '@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text );
		}

	}
	elseif( $invert == FALSE ) {
		// return
		return preg_replace( '@<(\w+)\b.*?>.*?</\1>@si', '', $text );
	}

	// return
	return $text;

}

/**
 * BH_mejskin_enqueue_styles
 * 
 * This function enqueues MediaLayer skin
 *
 * @param	N/A
 * @return	N/A
 */
function BH_mejskin_enqueue_styles() {

	$library = apply_filters( 'wp_audio_shortcode_library', 'mediaelement' );

	if ( 'mediaelement' === $library && did_action('init') ) {
		wp_enqueue_style( 'mejskin', CSS_DIR . '/libs/mediaelement/skin/mediaelementplayer.css', false, null );
	}

}
add_action( 'wp_footer', 'BH_mejskin_enqueue_styles', 11 );

/**
 * BH_setpost_limits
 * 
 * This function sets post limits in case of feed display
 *
 * @param	$s (string) Maximum posts to display
 * @return	(string)
 */
function BH_setpost_limits( $s ) {

	if( is_feed() && ! empty($_GET['nolimit']) )
		// return
		return '';

	// return
	return $s;

}
add_filter( 'post_limits', 'BH_setpost_limits' );

/**
 * BH_background_post
 *
 * This function sends POST request in the background
 *
 * @param	$url (string) URL to be sent
 * @return	(bool)
 */
function BH_background_post( $url ) {

	$parts = parse_url($url);

	$fp = fsockopen( $parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30 );

	if ( ! $fp )
		// return
		return false;

	$out = "POST ".$parts['path']." HTTP/1.1\r\n";
	$out.= "Host: ".$parts['host']."\r\n";
	$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	$out.= "Content-Length: ".strlen($parts['query'])."\r\n";
	$out.= "Connection: Close\r\n\r\n";

	if ( isset($parts['query']) )
		$out.= $parts['query'];

	fwrite( $fp, $out );
	fclose( $fp );

	// return
	return true;

}

/**
 * BH_trim_str
 *
 * This function trims special characters from a given string
 *
 * @param	$str (string)
 * @return	(string)
 */
function BH_trim_str( $str ) {

	// return
	return str_replace( array("\r\n", "\n", "\r"), array("\\r\\n", "\\n", "\\r"), $str );

};