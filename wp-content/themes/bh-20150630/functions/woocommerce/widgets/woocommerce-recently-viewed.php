<?php
/**
 * Recent Products Widget
 *
 * @author 		WooThemes
 * @category 	Widgets
 * @package 	WooCommerce/Widgets
 * @version 	2.1.0
 * @extends 	WC_Widget
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BH_WC_Widget_Recently_Viewed extends WC_Widget_Recently_Viewed {
	function widget($args, $instance) {
		extract( $args );
		
		$title  = __('RECENTLY<br />VIEWED', 'BH');
		
		echo $before_widget;
		
		if ($title)
			echo $before_title . $title . $after_title;
			
		echo '<div class="recently-products-slider-placeholder">';
			echo '<div id="recently-products-slider-prev"><i class="fa fa-angle-up"></i></div>';
			
			echo '<div class="recently-products-slider-wrapper">';
				echo '<div class="recently-products-slider"
					data-cycle-slides="> li"
					data-cycle-fx=carousel
					data-cycle-timeout=0
					data-cycle-allow-wrap=false
					data-cycle-manual-trump=false
					data-cycle-carousel-vertical=true
					data-cycle-log=false
					data-cycle-prev="#recently-products-slider-prev"
					data-cycle-next="#recently-products-slider-next"
					>';
					
					// recently viewed placeholder - will be filled via AJAX
					
				echo '</div>';
			echo '</div>';
			
			echo '<div id="recently-products-slider-next"><i class="fa fa-angle-down"></i></div>';
		echo '</div>';
		
		echo $after_widget;
	}
}