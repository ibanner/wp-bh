<?php
/**
 * The Template for displaying shop homepage featured products
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $list, $ec_products;

// Initiate $featured as an array of featured product IDs
$featured = array();

$args = array(
	'post_type'			=> 'product',
	'meta_key'			=> '_featured',
	'meta_value'		=> 'yes',
	'posts_per_page'	=> 14
);
$featured_query = new WP_Query($args);

if ( $featured_query->have_posts() ) : while ( $featured_query->have_posts() ) : $featured_query->the_post();
	$product = wc_get_product( $featured_query->post->ID );

	if ( get_post_thumbnail_id($post->ID) )
		$featured[] = $product;
endwhile; endif; wp_reset_query();

$no_of_featured = count($featured);

if ( $no_of_featured < 5 )
	return;

// For Google Analytics Enhanced Ecommerce - define list name and products array
$list = 'Featured Products';
if ( ! is_array($ec_products) )
	$ec_products = array();

// Set featured products display grid
$row1 = $row2 = $row3 = 0;		// Number of products to show in each row

switch ($no_of_featured) :

	case 5 :	$row1 = 5;							break;
	case 6 :	$row1 = 3;	$row2 = 3;				break;
	case 7 :	$row1 = 3;	$row2 = 4;				break;
	case 8 :	$row1 = 4;	$row2 = 4;				break;
	case 9 :	$row1 = 4;	$row2 = 5;				break;
	case 10 :	$row1 = 5;	$row2 = 5;				break;
	case 11 :	$row1 = 4;	$row2 = 3;	$row3 = 4;	break;
	case 12 :	$row1 = 3;	$row2 = 4;	$row3 = 5;	break;
	case 13 :	$row1 = 4;	$row2 = 5;	$row3 = 4;	break;
	case 14 :	$row1 = 5;	$row2 = 4;	$row3 = 5;	break;

endswitch;
	
$featured_title				= get_field('acf-options_shop_featured_products_title', 'option');
$featured_product_template	= locate_template('views/woocommerce/featured-product-item.php');

echo '<div class="shop-featured-wrapper visible-lg">';
	echo '<div class="shop-featured-title">' . $featured_title . '</div>';

	echo '<div class="container shop-featured">';

		// Row 1
		echo '<div class="row items-' . $row1 . '">';
			for ($i=0 ; $i<$row1 ; $i++) {
				$product = $featured[$i];
				include($featured_product_template);
			}
		echo '</div>';

		// Row 2
		if ($row2) :
			echo '<div class="row items-' . $row2 . '">';
				for ($i=$row1 ; $i<($row1+$row2) ; $i++) {
					$product = $featured[$i];
					include($featured_product_template);
				}
			echo '</div>';
		endif;

		// Row 3
		if ($row3) :
			echo '<div class="row items-' . $row3 . '">';
				for ($i=$row1+$row2 ; $i<$no_of_featured ; $i++) {
					$product = $featured[$i];
					include($featured_product_template);
				}
			echo '</div>';
		endif;

	echo '</div>';			
echo '</div>';

?>

<script>
	jQuery(function($) {
		BH_EC_onListView(<?php echo json_encode($ec_products); ?>, '<?php echo get_woocommerce_currency(); ?>', false);
	});
</script>