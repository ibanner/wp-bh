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
	 * @hooked	BH_shop_header - 15
	 */
	do_action('woocommerce_before_main_content');
?>

<div class="container">
	<?php
		/**
		 * woocommerce_before_single_product hook
		 *
		 * @hooked wc_print_notices - 10
		 */
		 do_action( 'woocommerce_before_single_product' );
	
		 if ( post_password_required() ) {
		 	echo get_the_password_form();
		 	return;
		 }
	?>
	
	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php
			/**
			 * Single product - section 1
			 * 
			 * @uses	woocommerce_breadcrumb
			 */
			get_template_part('views/woocommerce/single-product/single-product', 'section1');
		?>
		
		<?php
			/**
			 * Single product - section 2
			 */
			//get_template_part('views/woocommerce/single-product/single-product', 'section2');
		?>
		
		<?php
			/**
			 * Single product - section 3
			 */
			get_template_part('views/woocommerce/single-product/single-product', 'section3');
		?>
		
		<meta itemprop="url" content="<?php the_permalink(); ?>" />
		
	</div><!-- #product-<?php the_ID(); ?> -->
</div>

<?php
	/**
	 * BH_shop_footer hook
	 * 
	 * @hooked	BH_shop_footer_link_boxes - 10
	 */
	do_action('BH_shop_footer');
?>

<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
?>

<?php get_footer(); ?>