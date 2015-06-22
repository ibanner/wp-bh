<?php
/**
 * Shopping Cart Widget
 *
 * Extends woocommerce shopping cart widget
 * 
 * @author		Beit Hatfutsot
 * @package		bh/functions/widgets
 * @version		1.0
 * @extends		WC_Widget_Cart
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BH_WC_Widget_Cart extends WC_Widget_Cart {
	function widget($args, $instance) {
		extract( $args );
		
		if ( is_cart() || is_checkout() ) return;
		
		global $woocommerce;
		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;
		
		echo $before_widget;
		
		if ($hide_if_empty)
			echo '<div class="hide_cart_widget_if_empty">';
			
		// Insert shopping cart indicator placeholder - code in woocommerce.js will update this on page load
		echo '<div class="widget_shopping_cart_indicator"></div>';
		
		// Insert cart widget placeholder - code in woocommerce.js will update this on page load
		echo '<div class="widget_shopping_cart_content"></div>';
		
		if ($hide_if_empty)
			echo '</div>';
			
		echo $after_widget;
	}
}