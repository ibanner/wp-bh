<?php
/**
 * The Template for displaying shop homepage banners
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$banners = get_field('acf-options-shop_banners', 'option');

if ( ! $banners )
	return;
	
$banner_index = 1;

echo '<div class="row">';
	echo '<div class="col-sm-12">';
		echo '<div class="shop-banners">';
		
			foreach ($banners as $banner) :
			
				$image					= $banner['image_'					. ICL_LANGUAGE_CODE];
				$link_type				= $banner['link_type_'				. ICL_LANGUAGE_CODE];
				$page_product_link		= $banner['page_product_link_'		. ICL_LANGUAGE_CODE];
				$product_category_link	= $banner['product_category_link_'	. ICL_LANGUAGE_CODE];
				$product_occasion_link	= $banner['product_occasion_link_'	. ICL_LANGUAGE_CODE];
				$product_artist_link	= $banner['product_artist_link_'	. ICL_LANGUAGE_CODE];
				$ext_link				= $banner['external_link_'			. ICL_LANGUAGE_CODE];
				$link					= '';
				$target					= '_self';
				
				switch ($link_type) :
				
					case 'no' :															break;
					case 'page' :		$link = $page_product_link		? get_permalink($page_product_link->ID)	: '';		break;
					case 'category' :	$link = $product_category_link	? get_term_link($product_category_link)	: '';		break;
					case 'occasion' :	$link = $product_occasion_link	? get_term_link($product_occasion_link)	: '';		break;
					case 'artist' :		$link = $product_artist_link	? get_term_link($product_artist_link)	: '';		break;
					case 'ext' :		$link = $ext_link				? $ext_link								: '';		$target = '_blank';
				
				endswitch;
				
				if ($image) :
				
					echo '<div class="shop-banner shop-banner-' . $banner_index . '">';
						echo ($link) ? '<a href="' . $link . '" target="' . $target . '">' : '';
							echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
						echo ($link) ? '</a>' : '';
					echo '</div>';
					
				endif;
				
				$banner_index++;
				
			endforeach;
			
		echo '</div>';			
	echo '</div>';	
echo '</div>';