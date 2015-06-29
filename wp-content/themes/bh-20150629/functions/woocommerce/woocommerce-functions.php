<?php
/**
 * WooCommerce functions
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/functions
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_theme_support('woocommerce');

/**
 * BH_remove_woocommerce_generator_tag
 * 
 * Remove WooCommerce Generator tag
 */
function BH_remove_woocommerce_generator_tag() {
	remove_action('wp_head','wc_generator_tag');
}

/**
 * BH_woocommerce_manage_scripts_n_styles
 * 
 * Optimize WooCommerce scripts and styles
 * Remove WooCommerce scripts and styles from non WooCommerce pages
 */
function BH_woocommerce_manage_scripts_n_styles() {
	if ( is_admin() || ! is_page() )
		return;
	
	global $post;
	$shop_page	= get_field('acf-shop_page', $post->ID) || 'shop-why-shop-with-us.php' == basename( get_page_template() ) || is_cart() || is_checkout() || is_account_page();		// is shop page (true/false)
	
	if ( function_exists('is_woocommerce') ) {
		//dequeue scripts and styles
		if ( ! is_woocommerce() && ! $shop_page ) {
			// woocommerce styles
			wp_dequeue_style( 'woocommerce_chosen_styles' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-smallscreen' );
			wp_dequeue_style( 'woocommerce-general' );
			wp_dequeue_style( 'mijireh_css' );
			
			// woocommerce-multilingual styles
			wp_dequeue_style( 'wcml_wc' );
			wp_dequeue_style( 'currency-switcher' );
			wp_dequeue_style( 'wpml-wcml-prices' );
			wp_dequeue_style( 'toolset-font-awesome' );
			wp_dequeue_style( 'wpml-wcml' );
			wp_dequeue_style( 'cleditor' );
			wp_dequeue_style( 'wp-pointer' );
			wp_dequeue_style( 'buttons' );
			
			// woocommerce scripts
			wp_dequeue_script( 'wc-credit-card-form' );
			wp_dequeue_script( 'chosen' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-payment' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-country-select' );
			wp_dequeue_script( 'wc-address-i18n' );
			wp_dequeue_script( 'jquery-cookie' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-cart' );
			wp_dequeue_script( 'wc-chosen' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-payment-method' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'pusher' );
			wp_dequeue_script( 'page_slurp' );
			wp_dequeue_script( 'simplify-commerce' );
			wp_dequeue_script( 'wc-simplify-commerce' );
			wp_dequeue_script( 'wc-price-slider' );
			
			// woocommerce-multilingual styles
			wp_dequeue_script( 'wcml-tm-scripts-prices' );
			wp_dequeue_script( 'wcml-scripts' );
			wp_dequeue_script( 'wcml-tm-scripts' );
			wp_dequeue_script( 'cleditor' );
			wp_dequeue_script( 'suggest' );
			wp_dequeue_script( 'wp-pointer' );
			wp_dequeue_script( 'word-count' );
			wp_dequeue_script( 'editor' );
			wp_dequeue_script( 'quicktags' );
			wp_dequeue_script( 'wpml_tm' );
			wp_dequeue_script( 'wcml-lock-script' );
		}
	}
}

/**
 * BH_woocommerce_wrapper_start
 * 
 * WooCommerce Content Wrapper start
 */
function BH_woocommerce_wrapper_start() {
	echo '<section class="page-content">';
}

/**
 * BH_woocommerce_wrapper_end
 * 
 * WooCommerce Content Wrapper end
 */
function BH_woocommerce_wrapper_end() {
	echo '</section>';
}

/**
 * BH_woocommerce_breadcrumb_defaults
 * 
 * WooCommerce breadcrumb manipulation
 */
function BH_woocommerce_breadcrumb_defaults($defaults) {
	$defaults['home']		= __('Home', 'BH');
	$defaults['delimiter']	= '<span class="breadcrumb-delimiter bh-sprites"></span>';
	
	return $defaults;
}

/**
 * BH_shop_header
 * 
 * @uses	BH_product_cat_menu
 */
function BH_shop_header() {
	$is_cart_checkout = is_cart() || is_checkout();
	
	echo '<div class="container">';
		echo '<div class="row shop-header">';
		
			echo '<div class="' . ( $is_cart_checkout ? 'col-sm-12' : 'col-sm-11 shop-body-left' ) . '">';
				echo '<div class="row">';
				
					// product category menu
					echo '<div class="col-sm-7 product-cat-menu-wrapper"' . ( $is_cart_checkout ? ' style="width: 66.66666667%;"' : '' ) . '>';
						BH_product_cat_menu();
					echo '</div>';
					
					// product search
					echo '<div class="col-sm-5 widget-product-search-wrapper"' . ( $is_cart_checkout ? ' style="right: auto; width: 33.33333333%;"' : '' ) . '>';
						if ( is_active_sidebar('shop-header-search') )
							dynamic_sidebar('shop-header-search');
					echo '</div>';
					
				echo '</div>';
			echo '</div>';
			
			if ( ! $is_cart_checkout ) :
				echo '<div class="col-sm-1 shop-body-right">';
					if ( is_active_sidebar('shop-header-cart') )
						dynamic_sidebar('shop-header-cart');
				echo '</div>';
			endif;
			
		echo '</div>';
	echo '</div>';
}

/**
 * BH_shop_footer_link_boxes
 * 
 * Display link boxes to common shop pages
 * 
 * @uses	BH_shop_footer_link
 */
function BH_shop_footer_link_boxes() {
	// collect link boxes data	
	$links = array(
		'returns_policy'	=> array(
			'title'			=> get_field('acf-options_returns_policy_title_' . ICL_LANGUAGE_CODE, 'option'),
			'text'			=> get_field('acf-options_returns_policy_text_' . ICL_LANGUAGE_CODE, 'option'),
			'page'			=> get_field('acf-options_returns_policy_page', 'option')
		),
		
		'shipment_policy'	=> array(
			'title'			=> get_field('acf-options_shipment_policy_title_' . ICL_LANGUAGE_CODE, 'option'),
			'text'			=> get_field('acf-options_shipment_policy_text_' . ICL_LANGUAGE_CODE, 'option'),
			'page'			=> get_field('acf-options_shipment_policy_page', 'option')
		),
		
		'faqs'				=> array(
			'title'			=> get_field('acf-options_faqs_title_' . ICL_LANGUAGE_CODE, 'option'),
			'text'			=> get_field('acf-options_faqs_text_' . ICL_LANGUAGE_CODE, 'option'),
			'page'			=> get_field('acf-options_faqs_page', 'option')
		)
	);
	
	echo '<div class="container">';
		echo '<div class="row shop-footer">';
		
			foreach ($links as $type => $val) {
				$page_id = icl_object_id($val['page']->ID, 'page', false);
				
				echo '<div class="col-sm-4 link-box-wrapper">';
					echo '<div class="link-box">';
						BH_shop_footer_link( $type, $val['title'], $val['text'], get_permalink($page_id) );
					echo '</div>';
				echo '</div>';
			}
			
		echo '</div>';
	echo '</div>';
}

/**
 * BH_shop_footer_link
 * 
 * Display shop link box
 * 
 * @param	string	$type			link type (returns_policy / shipment_policy / faqs)
 * @param	string	$title			link box title
 * @param	string	$text			link box text
 * @param	string	$page_title		page title to link to
 * @param	string	$page_link		page link
 */
function BH_shop_footer_link($type, $title, $text, $page_link) {
	echo '<a href="' . $page_link . '">';
	
		// title
		echo '<div class="title ' . $type . '">' . $title . '</div>';
		
		// content
		echo '<div class="content">' . $text . '</div>';
		
		// learn more
		echo '<div class="link">' . __('Learn more', 'BH') . '</div>';
		
	echo '</a>';
}

/**
 * override_woocommerce_widgets
 * 
 * @overrides	WC_Widget_Cart
 * @overrides	WC_Widget_Recently_Viewed
 * @overrides	WC_Widget_Price_Filter
 */
function override_woocommerce_widgets() {
	// WC_Widget_Cart
	if ( class_exists('WC_Widget_Cart') ) {
		unregister_widget('WC_Widget_Cart');
		
		include_once('widgets/woocommerce-cart.php');
		
		register_widget('BH_WC_Widget_Cart');
	}
	
	// WC_Widget_Recently_Viewed
	if ( class_exists('WC_Widget_Recently_Viewed') ) {
		unregister_widget('WC_Widget_Recently_Viewed');
		
		include_once('widgets/woocommerce-recently-viewed.php');
		
		register_widget('BH_WC_Widget_Recently_Viewed');
	}
}

/**
 * BH_woocommerce_shopping_cart_indicator_fragment
 * 
 * Ajaxify Shopping Cart Indicator widget
 * Ensure shopping cart indicator update when products are added to the cart via AJAX
 */
function BH_woocommerce_shopping_cart_indicator_fragment($fragments) {
	global $woocommerce;
	
	ob_start();
	
	echo '<div class="widget_shopping_cart_indicator">' . ($woocommerce->cart->cart_contents_count ? '<span>' . $woocommerce->cart->cart_contents_count . '</span>' : '') . '</div>';
	
	$fragments['div.widget_shopping_cart_indicator'] = ob_get_clean();
	
	return $fragments;
}

/**
 * BH_add_viewed_products
 * 
 * Add post ID and all tranlated post IDs to a given array
 * Used to maintain the "woocommerce_recently_viewed" cookie
 * 
 * @param	int		$post_id			post ID
 * @param	&array	&$viewed_products	recently viewed products array
 */
function BH_add_viewed_products($post_id, &$viewed_products) {
	$languages = icl_get_languages('skip_missing=1');
	
	if ( ! empty($languages) ) :
	
		foreach ($languages as $l) :
			$id = icl_object_id($post_id, 'product', false, $l['language_code']);
			
			$key = array_search($id, $viewed_products);
			
			if ( $key === false ) {
				// post doesn't exist in array - add a new post ID
				array_unshift($viewed_products, $id);
				
				if ( sizeof( $viewed_products ) > 40 )
					array_pop($viewed_products);
			} else {
				// post exists in array - prepent post ID
				unset( $viewed_products[$key] );
				array_unshift($viewed_products, $id);
			}
		endforeach;
		
	endif;
}

/**
 * BH_remove_viewed_products
 * 
 * Remove post ID and all tranlated post IDs from a given array
 * Used to maintain the "woocommerce_recently_viewed" cookie
 * 
 * @param	int		$post_id			post ID
 * @param	&array	&$viewed_products	recently viewed products array
 */
function BH_remove_viewed_products($post_id, &$viewed_products) {
	$languages = icl_get_languages('skip_missing=1');
	
	if ( ! empty($languages) ) :
	
		foreach ($languages as $l) :
			$id = icl_object_id($post_id, 'product', false, $l['language_code']);
			
			$key_to_remove = array_search($id, $viewed_products);
			
			if ($key_to_remove !== false) :
				// post exists in array - remove post ID
				unset( $viewed_products[$key_to_remove] );
			endif;
		endforeach;
		
	endif;
}

/**
 * BH_init_product_filter_values
 * 
 * Initiate product filter values according to current taxonomy (product_cat, occasion, artist), term ID, price range and taxonomy terms
 * 
 * $taxonomies structure:
 * $taxonomies[ {taxonomy name} ][0]					=> taxonomy filter title
 * $taxonomies[ {taxonomy name} ][1]					=> number of products associated with this taxonomy
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][0]	=> term name
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][1]	=> number of products associated with this term
 * $taxonomies[ {taxonomy name} ][2][ {term ID} ][2]	=> whether this term is checked in taxonomy filter [1 = true / 0 = false]
 * 
 * @param	string	$taxonomy			taxonomy (product_cat|occasion|artist)
 * @param	int		$taxonomy_term_id	term ID
 * @param	int		&$min_price			minimum filter price
 * @param	int		&$max_price			maximum filter price
 * @param	int		&$min_handle_price	minimum filter handle price
 * @param	int		&$max_handle_price	maximum filter handle price
 * @param	array	&$taxonomies		holds arrays of arrays of terms
 * @return	array						array of post IDs
 */
function BH_init_product_filter_values($taxonomy, $taxonomy_term_id, &$min_price, &$max_price, &$min_handle_price, &$max_handle_price, &$taxonomies) {
	global $woocommerce;
	
	$posts = array();
	
	if ( ! $taxonomy || ! $taxonomy_term_id || count($taxonomies) == 0 )
		return;
	
	// reset $taxonomies counters
	foreach ($taxonomies as $tax_name => $tax_data) {
		$taxonomies[$tax_name][1] = 0;
		foreach ($tax_data[2] as $term_id => $term_data) {
			$taxonomies[$tax_name][2][$term_id][1] = 0;
		}
	}
	
	// get checked terms
	$checked_terms = array();	// array of taxonomy arrays hold checked term IDs
	
	foreach ($taxonomies as $tax_name => $tax_data)
		foreach ($tax_data[2] as $term_id => $term_data)
			if ($term_data[2] == '1') {
				if ( ! key_exists($tax_name, $checked_terms) )
					$checked_terms[$tax_name] = array();
					
				$checked_terms[$tax_name][] = $term_id;
			}
			
	// get category products
	$meta_query = $woocommerce->query->get_meta_query();
	
	$args = array(
		'post_type'			=> 'product',
		'posts_per_page'	=> -1,
		'no_found_rows'		=> true,
		'tax_query'			=> array(
			'relation'		=> 'AND',
			array(
				'taxonomy'	=> $taxonomy,
				'field'		=> 'id',
				'terms'		=> $taxonomy_term_id
			)
		),
		'meta_query'	=> $meta_query
	);
	
	// include checked terms in query, if exist any
	if ( count($checked_terms) > 0 )
		foreach ($checked_terms as $tax_name => $terms)
			$args['tax_query'][] = array(
				'taxonomy'	=> $tax_name,
				'field'		=> 'id',
				'terms'		=> $terms,
				'operator'	=> 'AND'
			);
			
	$query = new WP_Query($args);
	
	// fill in filter values according to products meta data
	global $post;
	
	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
	
		// get product price
		$_product = wc_get_product($post->ID);
		$price = round( $_product->get_price() );
		
		// update price filter
		if ( is_null($min_price) || is_null($max_price) ) :
			$min_price = $max_price = $price;
		else :
			$min_price = min($price, $min_price);
			$max_price = max($price, $max_price);
		endif;
		
		// exlude product if price is below or above minimum and maximum handle prices
		if ( ! is_null($min_handle_price) && ! is_null($max_handle_price) && ( $price < $min_handle_price || $price > $max_handle_price ) )
			continue;
		
		// update taxonomies filter
		foreach ($taxonomies as $tax_name => &$tax_data) :
			// update $taxonomies counters
			$p_terms = wp_get_post_terms($post->ID, $tax_name);
			
			if ($p_terms)
				foreach ($p_terms as $p_term) :
					// increment number of products associated with this taxonomy
					$tax_data[1]++;
					
					// increment number of products associated with this term
					$tax_data[2][$p_term->term_id][1]++;
				endforeach;
		endforeach;
		
		// save post
		$posts[] = $post->ID;
		
	endwhile; endif; wp_reset_postdata();
	
	// update price filter handles in first page load
	if ( is_null($min_handle_price) || is_null($max_handle_price) ) :
		$min_handle_price = $min_price;
		$max_handle_price = $max_price;
	endif;
	
	return $posts;
}

/**
 * BH_shop_home_banners
 * 
 * Shop Homepage / Show shop homepage banners
 */
function BH_shop_home_banners() {
	get_template_part('views/woocommerce/archive/home', 'banners');
}

/**
 * BH_shop_home_product_sliders
 * 
 * Shop Homepage / Show shop homepage sliders
 */
function BH_shop_home_product_sliders() {
	get_template_part('views/woocommerce/archive/home', 'sliders');
}

/**
 * BH_shop_show_product_images
 * 
 * Single Product / Section 1
 * Show product gallery
 */
function BH_shop_show_product_images() {
	get_template_part('views/woocommerce/single-product/single-product-section1', 'images');
}

/**
 * BH_shop_single_title
 * 
 * Single Product / Section 1
 * Show product title and artist
 */
function BH_shop_single_title() {
	get_template_part('views/woocommerce/single-product/single-product-section1', 'title');
}

/**
 * BH_shop_single_excerpt
 * 
 * Single Product / Section 1
 * Show product excerpt
 */
function BH_shop_single_excerpt() {
	get_template_part('views/woocommerce/single-product/single-product-section1', 'excerpt');
}

/**
 * BH_shop_single_meta
 * 
 * Single Product / Section 1
 * Show product content and meta information like weight and dimensions
 */
function BH_shop_single_meta() {
	get_template_part('views/woocommerce/single-product/single-product-section1', 'meta');
}

/**
 * BH_shop_single_badges
 * 
 * Single Product / Section 1
 * Show product budges
 */
function BH_shop_single_badges() {
	get_template_part('views/woocommerce/single-product/single-product-section1', 'badges');
}

/**
 * BH_shop_toggle_experience
 * 
 * deprecated
 * 
 * Single Product / Section 2
 * Show experience section toggle button
 */
/*
function BH_shop_toggle_experience() {
	get_template_part('views/woocommerce/single-product/single-product-section2', 'toggle');
}
*/

/**
 * BH_shop_show_random_image
 * 
 * deprecated
 * 
 * Single Product / Section 2
 * Show experience random image
 */
/*
function BH_shop_show_random_image() {
	get_template_part('views/woocommerce/single-product/single-product-section2', 'image');
}
*/

/**
 * BH_shop_show_experience_text
 * 
 * deprecated
 * 
 * Single Product / Section 2
 * Show experience text
 */
/*
function BH_shop_show_experience_text() {
	get_template_part('views/woocommerce/single-product/single-product-section2', 'text');
}
*/

/**
 * BH_shop_show_experience_banner
 * 
 * Single Product / Section 2
 * Show experience banner
 */
function BH_shop_show_experience_banner() {
	get_template_part('views/woocommerce/single-product/single-product-section2', 'banner');
}

/**
 * BH_shop_show_related_products
 * 
 * Single Product / Section 3
 * Show related products according to the following scenario:
 * First check whether there are manually related products defined, and show them
 * If no manually products defined - show all products (except the current one)from the same product category
 */
function BH_shop_show_related_products() {
	get_template_part('views/woocommerce/single-product/single-product-section3', 'related');
}

/**
 * BH_EC_product_detail
 * 
 * Enhanced Ecommerce - Measuring a Product Details View
 */
function BH_EC_product_detail() {
	get_template_part('views/woocommerce/single-product/ec-product-detail');
}

/**
 * BH_shop_get_artist_links
 * 
 * @return	string	product artist links seperated by comma
 */
function BH_shop_get_artist_links($product_id) {
	$artists = wp_get_post_terms($product_id, 'artist');
	
	$artists_html = '';
	
	if ($artists) {
		$count = 1;
		foreach ($artists as $artist) {
			$artists_html .= ($count++ > 1) ? ', ' : '';
			$artists_html .= '<a href="' . get_term_link($artist) . '">' . $artist->name . '</a>';
		}
	}
	
	return $artists_html;
}

/**
 * BH_shop_get_price_html
 * 
 * Customize product price html
 */
function BH_shop_get_price_html($price, $product) {
	$del = BH_strip_tags_content($price, '<ins>', true);
	$ins = BH_strip_tags_content($price, '<del>', true);
	
	if ( strpos($del, '<del>') === false || strpos($ins, '<ins>') === false )
		return $price;
		
	return $ins . $del;
}

/**
 * BH_shop_set_related_products_limit
 * 
 * Unlimit related products
 */
function BH_shop_set_related_products_limit($query) {
	$query['limits'] = -1;
	
	return $query;
}

/**
 * BH_shop_product_cat_banner
 * 
 * Show product category banner
 */
function BH_shop_product_cat_banner() {
	get_template_part('views/woocommerce/archive/category', 'banner');
}

/**
 * BH_shop_catalog_orderby_options
 * 
 * Reset products catalog ordering options
 */
function BH_shop_catalog_orderby_options($options) {
	$options['menu_order']	= __('Most Popular', 'BH');
	$options['price']		= __('Price: Low to High', 'BH');
	$options['price-desc']	= __('Price: High to Low', 'BH');
	
	if ( isset( $options['popularity'] ) )
		unset( $options['popularity'] );
		
	if ( isset( $options['rating'] ) )
		unset( $options['rating'] );
		
	if ( isset( $options['date'] ) )
		unset( $options['date'] );
		
	return $options;
}

/**
 * BH_shop_order_invoice
 * 
 * Generate an invoice for completed order
 * Use icount API
 */
function BH_shop_order_invoice($order_id) {
	$order		= new WC_Order($order_id);
	
	// get currency code
	$currency				= $order->get_order_currency();
	$currency_code			= '';
	
	switch ($currency) :
		case 'EUR' :		$currency_code = '1';	break;
		case 'USD' :		$currency_code = '2';	break;
		case 'JPY' :		$currency_code = '3';	break;
		case 'GBP' :		$currency_code = '4';	break;
		case 'ILS' :		$currency_code = '5';	break;
		case 'SGD' :		$currency_code = '6';	break;
		case 'CAD' :		$currency_code = '7';	break;
		case 'RUB' :		$currency_code = '8';	break;
		case 'NZD' :		$currency_code = '9';	break;
		case 'AUD' :		$currency_code = '10';	break;
	endswitch;
	
	// get current exchange rate
	$wcml_settings			= get_option('_wcml_settings');
	$exr					= (float)( 1 / $wcml_settings['currency_options']['USD']['rate'] );
	$exr_str				= ($exr) ? sprintf('%f', $exr) : '';
	
	// get order billing country
	$client_country_code	= get_post_meta($order_id, '_billing_country',		true);
	$countries				= include WC()->plugin_path() . '/i18n/countries.php';
	$client_country			= ($client_country_code && $countries) ? $countries[$client_country_code] : '';
	
	// collect order info
	$compID					= get_field('acf-options_icount_company_id',	'option');
	$user					= get_field('acf-options_icount_user',			'option');
	$pass					= get_field('acf-options_icount_password',		'option');
	
	$dateissued				= date( 'Ymd', strtotime($order->order_date) );
	$ppdateissued			= date( 'Y-m-d', strtotime($order->order_date) );
	
	$clientname				= get_post_meta($order_id, '_billing_first_name',	true) . ' ' . get_post_meta($order_id, '_billing_last_name',	true);
	$client_street			= get_post_meta($order_id, '_billing_address_1',	true);
	$client_city			= get_post_meta($order_id, '_billing_city',			true);
	$client_zip				= get_post_meta($order_id, '_billing_postcode',		true);
	$sendOrig				= get_post_meta($order_id, '_billing_email',		true);
	
	$absolutetotalwithvat	= number_format( (float)( $order->get_total() ),													2, '.', '' );
	$totalsum				= ($currency == 'ILS') ? number_format( (float)( $order->get_total() - $order->get_total_tax() ),	2, '.', '' ) : number_format( (float)( $exr * ( $order->get_total() - $order->get_total_tax() ) ),	2, '.', '' );
	$totalvat				= ($currency == 'ILS') ? number_format( (float)( $order->get_total_tax() ),							2, '.', '' ) : number_format( (float)( $exr * ( $order->get_total_tax() ) ),						2, '.', '' );
	$totalwithvat			= ($currency == 'ILS') ? number_format( (float)( $order->get_total() ),								2, '.', '' ) : number_format( (float)( $exr * ( $order->get_total() ) ),							2, '.', '' );
	$totalshipping			= number_format( (float)( $order->get_total_shipping() ),											2, '.', '' );
	$taxexempt				= '1';
	$maampercent			= '18';		// we don't charge for VAT. if we will, we must use a custom field for defining this value
	
	// collect order items (including shipping as a separated item)
	$order_items			= $order->get_items();
	
	$items					= array();
	$desc					= array();	// item name
	$quantity				= array();	// item quantity
	$unitprice				= array();	// item unit price
	
	if ($order_items) {
		foreach ($order_items as $item) {
			$desc[]			= $item['name'];
			$quantity[]		= $item['qty'];
			$unitprice[]	= $order->get_item_subtotal($item, false, true);
		}
		
		// shipping info
		$desc[]				= $order->get_shipping_method();
		$quantity[]			= '1';
		$unitprice[]		= $totalshipping;
	}
	
	if ($desc && $quantity && $unitprice) {
		for ($i=0 ; $i<count($desc) ; $i++)
			$items['desc[' . $i . ']']		= $desc[$i];
			
		for ($i=0 ; $i<count($quantity) ; $i++)
			$items['quantity[' . $i . ']']	= $quantity[$i];
			
		for ($i=0 ; $i<count($unitprice) ; $i++)
			$items['unitprice[' . $i . ']']	= $unitprice[$i];
	}
	
	// collect payment info
	$payment_method			= get_post_meta($order_id, '_payment_method', true);
	$payment				= array();
	
	switch ($payment_method) :
		case 'pelecard'	:
			$payment['credit']				= '1';
			$payment['cc_paymenttype']		= '1';
			$payment['cctotal']				= $absolutetotalwithvat;
			$payment['ccfirstpayment']		= $absolutetotalwithvat;
			$payment['cc_numofpayments']	= '1';
			$payment['cc_peraondate']		= $dateissued;
			
			break;
		case 'paypal'	:
			$payment['paypal']				= '1';
			$payment['pp_sum']				= $absolutetotalwithvat;
			$payment['pp_paydate']			= $ppdateissued;
			
			break;
	endswitch;
	
	$data = array(
		'compID'			=> $compID,
		'user'				=> $user,
		'pass'				=> $pass,
		'dateissued'		=> $dateissued,
		'clientname'		=> $clientname,
		'client_street'		=> $client_street,
		'client_city'		=> $client_city,
		'client_country'	=> $client_country,
		'client_zip'		=> $client_zip,
		'totalsum'			=> $totalsum,
		'totalvat'			=> $totalvat,
		'totalwithvat'		=> $totalwithvat,
		'taxexempt'			=> $taxexempt,
		'maampercent'		=> $maampercent,
		'paid'				=> $totalwithvat,
		'totalpaid'			=> $totalwithvat,
		'docType'			=> 'invrec',
		'currency'			=> $currency_code,
		'rate'				=> $exr_str,
		'sendOrig'			=> $sendOrig,
//		'show_response'		=> '1',
//		'debug'				=> 'yes'
	);
	
	// add language indicator
	if ($currency != 'ILS')
		$data['lang'] = 'en';
	
	// add items and payment info into $data
	$data = $data + $items + $payment;
	
	// send request
	$url = get_field('acf-options_icount_api_url', 'option');
	
	$options = array (
		'http' => array (
			'header'	=> "Content-type: application/x-www-form-urlencoded\r\n",
			'method'	=> 'POST',
			'content'	=> http_build_query($data),
		)
	);
	$context = stream_context_create($options);
	
	$result = file_get_contents($url, false, $context);
	
/*	$headers = 'From: nirg@bh.org.il' . "\r\n" .
		'Reply-To: webmaster@example.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
	mail('nirg@bh.org.il', 'A new invoice for order#' . $order_id, $result, $headers); */
}