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
	// Newsletter Header Top Menu
	register_sidebar(
		array(
			'id'			=> 'newsletter-header-top-menu',
			'name'			=> 'Newsletter Header Top Menu',
			'description'	=> 'Drag here Active Trail Newsletter widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Newsletter Header Mid Menu
	register_sidebar(
		array(
			'id'			=> 'newsletter-header-mid-menu',
			'name'			=> 'Newsletter Header Mid Menu',
			'description'	=> 'Drag here Active Trail Newsletter widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Newsletter Side Menu
	register_sidebar(
		array(
			'id'			=> 'newsletter-side-menu',
			'name'			=> 'Newsletter Side Menu',
			'description'	=> 'Drag here Active Trail Newsletter widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Shop Header Product Search
	register_sidebar(
		array(
			'id'			=> 'shop-header-search',
			'name'			=> 'Shop Header Product Search',
			'description'	=> 'Drag here Product Search widget',
			'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h2 class="widgettitle">',
			'after_title'	=> '</h2>'
		)
	);
	
	// Shop Header Cart
	register_sidebar(
		array(
			'id'			=> 'shop-header-cart',
			'name'			=> 'Shop Header Cart',
			'description'	=> 'Drag here Cart widget',
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
	
	// Shop Sidebar Refine Products
	register_sidebar(
		array(
			'id'			=> 'shop-sidebar-refine-products',
			'name'			=> 'Shop Sidebar Refine Products',
			'description'	=> 'Drag here Shop Refine Products widget',
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