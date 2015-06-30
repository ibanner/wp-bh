<?php
/**
 * Single Product experience banner
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$banner	= get_field('acf-options_experience_banner',	'option');
$page	= get_field('acf-options_experience_page',		'option');

if ($banner) :

	echo '<div class="experience-banner">';
		echo ($page) ? '<a href="' . get_permalink($page->ID) . '" target="_blank">' : '';
			echo '<img src="' . $banner['url'] . '" width="' . $banner['width'] . '" height="' . $banner['height'] . '" alt="' . $banner['alt'] . '" />';
		echo ($page) ? '</a>' : '';
	echo '</div>';
	
endif;