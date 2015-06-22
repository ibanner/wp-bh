<?php
/**
 * WooCommerce filters and hooks
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooCommerce optimization
 * 
 * @see		BH_remove_woocommerce_generator_tag()
 * @see		BH_woocommerce_manage_scripts_n_styles()
 */
add_action('get_header', 'BH_remove_woocommerce_generator_tag');
add_action('wp_enqueue_scripts', 'BH_woocommerce_manage_scripts_n_styles', 100);

/**
 * WooCommerce Content Wrapper customization
 * 
 * @see		BH_woocommerce_wrapper_start()
 * @see		BH_woocommerce_wrapper_end()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'BH_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'BH_woocommerce_wrapper_end', 10);

/**
 * WooCommerce Breadcrumb
 * 
 * @see		BH_woocommerce_breadcrumb_defaults()
 */
add_filter('woocommerce_breadcrumb_defaults', 'BH_woocommerce_breadcrumb_defaults');

/**
 * WooCommerce Header
 * 
 * @see		BH_shop_header()
 */
add_action('woocommerce_before_main_content', 'BH_shop_header', 15);

/**
 * WooCommerce Footer
 * 
 * @see		BH_shop_footer_link_boxes()
 */
add_action('BH_shop_footer', 'BH_shop_footer_link_boxes', 10);

/**
 * WooCommerce Widgets customization
 * 
 * @see		override_woocommerce_widgets()
 */
add_action('widgets_init', 'override_woocommerce_widgets', 15);

/**
 * WooCommerce Widget - Cart
 * 
 * @see		BH_woocommerce_shopping_cart_indicator_fragment()
 */
add_filter('add_to_cart_fragments', 'BH_woocommerce_shopping_cart_indicator_fragment');

/**
 * Remove action wc_track_product_view which handles "woocommerce_recently_viewed" cookie update
 * Update of "woocommerce_recently_viewed" cookie is treated manually via AJAX
 */
remove_action('template_redirect', 'wc_track_product_view', 20);

/**
 * Remove actions:
 * 
 * wc_print_notices
 * woocommerce_result_count
 */
remove_action('woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

/**
 * Shop Homepage
 * 
 * @see		BH_shop_home_banners()
 * @see		BH_shop_home_product_sliders()
 */
add_action('BH_shop_home', 'BH_shop_home_banners', 10);
add_action('BH_shop_home', 'BH_shop_home_product_sliders', 20);

/**
 * Single Product
 * 
 * @see		BH_shop_show_product_images()
 * @see		BH_shop_single_title()
 * @see		woocommerce_template_single_price()
 * @see		BH_shop_single_excerpt()
 * @see		woocommerce_template_single_add_to_cart()
 * @see		BH_shop_single_meta()
 * @see		BH_shop_single_badges()
 * @see		BH_shop_toggle_experience()
 * @see		BH_shop_show_random_image()
 * @see		BH_shop_show_experience_text()
 * @see		BH_shop_show_related_products()
 */
add_action('BH_shop_before_single_product_meta', 'BH_shop_show_product_images', 10);

add_action('BH_shop_single_product_meta', 'BH_shop_single_title', 5);
add_action('BH_shop_single_product_meta', 'woocommerce_template_single_price', 10);
add_action('BH_shop_single_product_meta', 'BH_shop_single_excerpt', 20);
add_action('BH_shop_single_product_meta', 'woocommerce_template_single_add_to_cart', 30);
add_action('BH_shop_single_product_meta', 'BH_shop_single_meta', 40);
add_action('BH_shop_single_product_meta', 'BH_shop_single_badges', 50);

//add_action('BH_shop_before_experience', 'BH_shop_toggle_experience', 10);

//add_action('BH_shop_experience', 'BH_shop_show_random_image', 10);
//add_action('BH_shop_experience', 'BH_shop_show_experience_text', 20);

add_action('BH_shop_experience', 'BH_shop_show_experience_banner', 10);

add_action('BH_shop_related_products', 'BH_shop_show_related_products', 10);

/**
 * Product Price
 * 
 * @see		BH_shop_get_price_html()
 */
add_filter('woocommerce_get_price_html', 'BH_shop_get_price_html', 10, 2);

/**
 * Related products limit
 * 
 * @see		BH_shop_set_related_products_limit()
 */
add_filter('woocommerce_product_related_posts_query', 'BH_shop_set_related_products_limit');

/**
 * Products Catalog Ordering
 * 
 * @see		BH_shop_catalog_orderby_options()
 */
add_filter('woocommerce_catalog_orderby', 'BH_shop_catalog_orderby_options');