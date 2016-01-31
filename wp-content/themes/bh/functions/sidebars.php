<?php
/**
 * BH theme sidebars
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function BH_sidebars() {
	// Newsletter
	register_sidebar(
		array(
			'id'			=> 'newsletter',
			'name'			=> 'Newsletter',
			'description'	=> 'Drag here Active Trail Newsletter widget',
			'before_widget'	=> '<div class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Shop Mini Cart
	register_sidebar(
		array(
			'id'			=> 'shop-mini-cart',
			'name'			=> 'Shop Mini Cart',
			'description'	=> 'Drag here Cart widget',
			'before_widget'	=> '<div class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Shop Sidebar Products Filter
	register_sidebar(
		array(
			'id'			=> 'shop-sidebar-products-filter',
			'name'			=> 'Shop Sidebar Products Filter',
			'description'	=> 'Drag here WooCommerce BH Products Filter widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);

	// Shop Sidebar Recently Viewed
	register_sidebar(
		array(
			'id'			=> 'shop-sidebar-recently-viewed',
			'name'			=> 'Shop Sidebar Recently Viewed',
			'description'	=> 'Drag here Recently Viewed widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
}
add_action('widgets_init', 'BH_sidebars');

/**
 * BH_get_dynamic_sidebar
 *
 * @return		String		sidebar content
 */
function BH_get_dynamic_sidebar($index = 1) {
	$sidebar_content = '';

	ob_start();
	dynamic_sidebar($index);
	$sidebar_content = ob_get_clean();

	return $sidebar_content;
}