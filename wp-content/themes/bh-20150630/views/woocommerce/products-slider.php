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
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( count($slider_products) == 0 )
	return;
	
global $product;

?>

<div class="related-slider-placeholder">

	<?php if ( count($slider_products) > 4 ) : ?>
	
		<div class="related-slider"
			data-cycle-slides=".related-item"
			data-cycle-fx=carousel
			data-cycle-timeout=0
			data-cycle-allow-wrap=false
			data-cycle-manual-trump=false
			data-cycle-carousel-visible=4
			data-cycle-log=false
			data-cycle-prev="#related-slider-prev-<?php echo $slider_index; ?>"
			data-cycle-next="#related-slider-next-<?php echo $slider_index; ?>"
		>
		
		<?php endif; ?>
		
		<?php foreach ($slider_products as $slider_product) :
		
			$product_id = icl_object_id($slider_product->ID, 'product', false); ?>
			
			<div class="related-item <?php echo ( count($slider_products) <= 4 ) ? 'floated' : ''; ?>">
				<?php
					$product = wc_get_product($product_id);
					include( locate_template('views/woocommerce/product-item.php') );
				?>
			</div>
			
		<?php endforeach; ?>
		
	<?php if ( count($slider_products) > 4 ) : ?>
		</div>
		<div class="related-slider-prev bh-sprites" id="related-slider-prev-<?php echo $slider_index; ?>"></div>
		<div class="related-slider-next bh-sprites" id="related-slider-next-<?php echo $slider_index; ?>"></div>
	<?php endif; ?>
	
</div>