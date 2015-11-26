<?php
/**
 * Single Product meta
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$weight		= ( $product->has_weight() ) ? $product->get_weight() : '';
$dimensions	= ( $product->has_dimensions() ) ? $product->get_dimensions() : '';

echo '<div class="meta">';

	echo '<div class="content">';
		echo '<h3>' . __('More Details', 'BH') . '</h3>';
		
		echo apply_filters('woocommerce_short_description', $post->post_content);
	echo '</div>';
	
	if ($weight || $dimensions) :
		echo '<table class="table table-bordered">';
		
			if ($weight) :
				echo '<tr>';
					echo '<th>' . __('Weight', 'BH') . '</th>';
					echo '<td>' . $weight . esc_attr( get_option('woocommerce_weight_unit') ) . '</td>';
				echo '</tr>';
			endif;
			
			if ($dimensions) :
				echo '<tr>';
					echo '<th>' . __('Dimensions', 'BH') . '<br /><span>' . __('Length x Width x Height', 'BH') . '</span></th>';
					echo '<td>' . $dimensions . '</td>';
				echo '</tr>';
			endif;
			
		echo '</table>';

		echo $product->length	? '<meta itemprop="depth"	value="' . $product->length . '"	unitCode="CMT" />'	: '';
		echo $product->width	? '<meta itemprop="width"	value="' . $product->width . '"		unitCode="CMT" />'	: '';
		echo $product->height	? '<meta itemprop="height"	value="' . $product->height . '"	unitCode="CMT" />'	: '';
		echo $product->weight	? '<meta itemprop="weight"	value="' . $product->weight . '"	unitCode="GRM" />'	: '';
	endif;

	echo '<meta itemprop="sku"		content="' . $product->sku . '" />';
	echo '<meta itemprop="mpn"		content="' . $product->sku . '" />';
	echo '<meta itemprop="gtin12"	content="' . gtin_12($product->sku) . '" />';

echo '</div>';