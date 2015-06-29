<?php
/**
 * Single Product badges
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$badges = wp_get_post_terms( $post->ID, 'badge', array( 'orderby' => 'term_order' ) );

if ($badges) :

	echo '<ul class="badges">';
	
		foreach ($badges as $badge) :
			$image = get_field('acf-product-badge_image', 'badge' . '_'  . $badge->term_id);
			
			echo ($image) ? '<li><img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $badge->name . '" /></li>' : '';
		endforeach;
		
	echo '</ul>';
	
endif;