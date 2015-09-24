<?php
/**
 * Template Name: Shop - Why Shop With Us
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

	while (has_sub_field('acf-shop_wswu_template_layouts')) :
	
		$layout = get_row_layout();
		
		switch ($layout) :
		
			case 'banners' :
			
				/***********/
				/* banners */
				/***********/
				
				get_template_part('views/shop/why-shop-with-us/layout', 'banners');
				
				break;
				
			case 'content' :
			
				/***********/
				/* content */
				/***********/
				
				get_template_part('views/shop/why-shop-with-us/layout', 'content');
				
				break;
				
			case 'video' :
			
				/*********/
				/* video */
				/*********/
				
				get_template_part('views/shop/why-shop-with-us/layout', 'video');
				
		endswitch;
		
	endwhile;

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