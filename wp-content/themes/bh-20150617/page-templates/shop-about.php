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
	 * @hooked	BH_shop_header - 15
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
	 * BH_shop_footer hook
	 * 
	 * @hooked	BH_shop_footer_link_boxes - 10
	 */
	do_action('BH_shop_footer');
	
	/**
	 * woocommerce_after_main_content hook
	 *
	 * @hooked	woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');
	
?>

<?php get_footer(); ?>