<?php
/**
 * WPML functions
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * load theme text domain
 */
load_theme_textdomain( 'BH', get_template_directory() . '/languages' );

/**
 * WPML - disable scripts & styles
 */
define('ICL_DONT_LOAD_NAVIGATION_CSS',			true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS',	true);
define('ICL_DONT_LOAD_LANGUAGES_JS',			true);

/**
 * BH_languages_switcher
 *
 * @return		String		language switcher button
 */
function BH_languages_switcher() {
	$languages	= icl_get_languages('skip_missing=0&orderby=code');
	$output		= '';
	
	if ( ! empty($languages) ) :

		$output .= '<div class="languages-switcher-btn">';
			foreach ($languages as $l) :
				if ( ! $l['active'] ) :
					$output .= '<a href="' . $l['url'] . '">' .
						strtoupper( mb_substr($l['native_name'], 0, 3) ) .
					'</a>';
				endif;
			endforeach;
		$output .= '</div>';
		
	endif;

	return $output;
}