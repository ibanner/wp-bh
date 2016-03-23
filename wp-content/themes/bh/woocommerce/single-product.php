<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author		WooThemes
 * @package		WooCommerce/Templates
 * @version		1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 */
	do_action('woocommerce_before_main_content');
?>

<div class="container">

	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php
			/**
			 * Single product - section 1
			 *
			 * product info
			 */
			get_template_part('views/woocommerce/single-product/single-product', 'section1');
		?>
		
		<?php
			/**
			 * Single product - section 2
			 *
			 * wswu banner
			 */
			get_template_part('views/woocommerce/single-product/single-product', 'section2');
		?>
		
		<?php
			/**
			 * Single product - section 3
			 *
			 * related prodcts
			 */
			get_template_part('views/woocommerce/single-product/single-product', 'section3');
		?>
		
		<?php
			/**
			 * Single product - section 4
			 *
			 * recently viewed products
			 */
			//get_template_part('views/woocommerce/single-product/single-product', 'section4');
		?>
		
		<meta itemprop="url" content="<?php the_permalink(); ?>" />
		
	</div><!-- #product-<?php the_ID(); ?> -->
	
	<?php
		/**
		 * BH_after_single_product hook
		 *
		 * @hooked BH_EC_product_detail - 10
		 */
		 do_action( 'BH_after_single_product' );
	?>

</div>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
?>

<?php get_footer(); ?>