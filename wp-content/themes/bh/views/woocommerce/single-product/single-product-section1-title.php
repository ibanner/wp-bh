<?php
/**
 * Single Product title
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$artists = BH_shop_get_artist_links($post->ID);

echo '<div class="title">';

	echo '<h1 itemprop="name" class="product-title">' . get_the_title() . '</h1>';
	
	if ($artists) :
		echo '<div itemprop="brand" itemscope itemtype="http://schema.org/Organization" class="artist-title">';
			echo ( ICL_LANGUAGE_CODE == 'en' ) ? '<span>' . __('By ', 'BH') . '</span>' : '';
			echo '<span itemprop="name">' . $artists . '</span>';
		echo '</div>';
	endif;
	
echo '</div>';