<?php
/**
 * Products Slider
 * 
 * Show in two cases:
 * Shop homepage - dynamic sliders 
 * Single product - related products
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$no_of_slider_products = count($slider_products);

if ( $no_of_slider_products == 0 )
	return;
	
global $product;

?>

<div class="products-slider-wrapper">

	<div class="products-slider-placeholder">

		<div class="products-slider-carousel"
			data-cycle-slides=".product-item-wrapper"
			data-cycle-fx=carousel
			data-cycle-timeout=0
			data-cycle-allow-wrap=true
			data-cycle-manual-trump=false
			data-cycle-carousel-visible=<?php echo $no_of_slider_products >= 4 ? 4 : $no_of_slider_products; ?>
			data-cycle-swipe=true
			data-cycle-log=false
			data-cycle-prev="#products-slider-prev-<?php echo $slider_index; ?>"
			data-cycle-next="#products-slider-next-<?php echo $slider_index; ?>"
		>

			<?php
				foreach ($slider_products as $slider_product) :
					$product_id = icl_object_id($slider_product->ID, 'product', false);
					$product = wc_get_product($product_id);

					echo '<div class="product-item-wrapper">';
						include( locate_template('views/woocommerce/product-item.php') );
					echo '</div>';
				endforeach;
			?>

		</div>

		<div class="products-slider-prev" id="products-slider-prev-<?php echo $slider_index; ?>"></div>
		<div class="products-slider-next" id="products-slider-next-<?php echo $slider_index; ?>"></div>
		
	</div>

</div>