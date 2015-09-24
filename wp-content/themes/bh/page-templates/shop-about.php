<?php
/**
 * Template Name: Shop - About
 */?>
<?php get_header(); ?>

<?php

	/**
	 * woocommerce_before_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 */
	do_action('woocommerce_before_main_content');
	
	echo '<div class="container">';
		woocommerce_breadcrumb();
	echo '</div>';

?>

<?php

	/***********/
	/* content */
	/***********/
	
	get_template_part('views/shop/about/layout', 'content');
	
	/********/
	/* team */
	/********/
	
	get_template_part('views/shop/about/layout', 'team');
	
	/*********************/
	/* experience banner */
	/*********************/
	
	echo '<section class="shop-about-layout shop-about-layout-experience">';
		get_template_part('views/woocommerce/single-product/single-product-section2-banner');
	echo '</section>';
	
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