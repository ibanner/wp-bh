<?php
/**
 * The Template for displaying shop homepage sliders
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$sliders = get_field('acf-options-shop_sliders', 'option');

if ( ! $sliders )
	return;
	
// for Google Analytics Enhanced Ecommerce - define list name and products array
global $list, $ec_products;

$list			= 'Shop Homepage Slider';
$ec_products	= array();

$slider_index = 1; 

?>

<div class="container shop-home-sliders">

	<?php foreach ($sliders as $slider) :
	
		$title				= strip_tags( $slider['title'], '<a>' );
		$slider_products	= $slider['products'];
		
		if ( count($slider_products) > 0 ) :

			echo '<div class="products-slider">';
				echo '<h2>' . $title . '</h2>';

				// display products slider
				include( locate_template('views/woocommerce/products-slider.php') );
			echo '</div>';

			$slider_index++;

		endif;
		
	endforeach; ?>
	
	<script>
		jQuery(function($) {
			BH_EC_onListView(<?php echo json_encode($ec_products); ?>, '<?php echo get_woocommerce_currency(); ?>', true);
		});
	</script>
	
</div>