<?php
/**
 * Single Product experience banner
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$banner	= get_field('acf-options_experience_banner_' . ICL_LANGUAGE_CODE, 'option');
$page	= get_field('acf-options_experience_page', 'option');

if ($page) :
	$page_id = icl_object_id($page->ID, 'page', false);
endif;

if ($banner) :

	echo '<div class="experience-banner">';
		echo ($page_id) ? '<a href="' . get_permalink($page_id) . '" target="_blank">' : '';
			echo '<img src="' . $banner['url'] . '" width="' . $banner['width'] . '" height="' . $banner['height'] . '" alt="' . $banner['alt'] . '" />';
		echo ($page_id) ? '</a>' : '';
	echo '</div>';
	
endif;