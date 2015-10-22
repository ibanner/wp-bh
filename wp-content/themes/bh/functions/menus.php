<?php
/**
 * Menus
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register theme menus
 */
global $menus;

$menus = array(
	'main-menu'		=> __('Main Menu'),
	'shop-menu'		=> __('Shop Menu')
);

function BH_register_menus() {
	global $menus;
	register_nav_menus($menus);
}
add_action('init', 'BH_register_menus');

/***********************/
/* side menu functions */
/***********************/

/**
 * BH_get_top_menu_item
 * 
 * get top menu item
 * used for displaying top menu item in side menu
 * 
 * @param	int		$id			current object ID
 * @param	string	$menu_name	menu theme location
 * @return	mix					array of top menu item arguments (ID, object ID, title, url) or FALSE in case of any failure
 */
function BH_get_top_menu_item($id, $menu_name) {
	if ( !isset($id) || !isset($menu_name) )
		return false;
		
	$parent		= false;
	$locations	= get_nav_menu_locations();
	
	if ( isset($locations[$menu_name]) ) :
		$current_item_key	= '';
		
		if ( function_exists('BH_get_cached_nav_menu_items') )
			$menu_items = BH_get_cached_nav_menu_items($menu_name);
		else
			$menu_items = wp_get_nav_menu_items($locations[$menu_name]);
		
		// find current item key
		if ($menu_items) :
			foreach ($menu_items as $key => $item) :
				if ($item->object_id == $id) {
					$current_item_key = $key;
					
					// check backwards in order to find the top level parent
					while ($menu_items[$current_item_key]->menu_item_parent)
						if ($menu_items[--$key]->ID == $menu_items[$current_item_key]->menu_item_parent)
							$current_item_key = $key;
							
					// $current_item_key now should point to top level parent item
					$parent = array(
						'ID'		=> $menu_items[$current_item_key]->ID,
						'object_id'	=> $menu_items[$current_item_key]->object_id,
						'title'		=> $menu_items[$current_item_key]->title,
						'url'		=> $menu_items[$current_item_key]->url
					);
					
					break;
				}
			endforeach;
		endif;
	endif;
	
	return $parent;
}

/**
 * BH_submenu_limit
 * 
 * extend wp_nav_menu() to enable showing children menu only
 * 
 * @param	array	$items	the menu items, sorted by each menu item's menu order
 * @param	array	$args	an object containing wp_nav_menu() arguments
 * @return	array			subset of $items according to parent item ID (defined as $args->children_of)
 */
add_filter('wp_nav_menu_objects', 'BH_submenu_limit', 10, 2);
function BH_submenu_limit($items, $args) {
	if ( empty($args->children_of) )
		return $items;
		
	$parents	= array();
	$parents[]	= $args->children_of;
	
	foreach ($items as $key => $item) :
		if ( in_array($item->menu_item_parent, $parents) ) {
			$parents[] = $item->ID;
		}
		else {
			unset($items[$key]);
		}
	endforeach;
	
	return $items;
}

/**
 * BH_get_event_categories_menu
 * 
 * get list of event categories, optionally get also event items for each category
 * used for displaying event categories in side menu in case of parent page (top menu item) is based on event.php page template
 * 
 * @param	int		$current_object_id	current object ID. if set the current menu item will be marked
 * @param	bool	$show_events		if TRUE get child events for each event category
 * @return	string						HTML LIs structure
 */
function BH_get_event_categories_menu($current_object_id, $show_events) {
	$output = '';
	
	$args = array(
		'orderby'	=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$categories = BH_get_cached_terms('event_category', $args);
	else
		$categories = get_terms('event_category', $args);
	
	foreach ($categories as $cat) :
		
		if ( function_exists('BH_get_cached_event_category_events') )
			$events = BH_get_cached_event_category_events($cat);
		else
			$events = BH_get_event_category_events($cat->term_id);
		
		if ($show_events && $events) :
		
			$events_list		= '';		// events LIs
			$current_category	= false;	// indicates whether the current category is a parent menu item
			
			foreach ($events as $event) :
				if ($event->ID == $current_object_id) $current_category = true;
				$events_list .= '<li class="menu-item menu-item-type-post_type menu-item-object-event menu-item-999' . $event->ID . ' ' . ( ($event->ID == $current_object_id) ? 'current-menu-item current_page_item' : '' ) . '"><a href="' . get_permalink($event->ID) . '" item="999' . $event->ID . '">' . $event->post_title . '</a></li>';
			endforeach;
			
			$output .=
				'<li class="menu-item menu-item-type-taxonomy menu-item-object-event_category menu-item-has-children menu-item-999' . $cat->term_id . ' ' . ( ($cat->term_id == $current_object_id || $current_category) ? 'current-menu-item current_page_item' : '' ) . '"><a href="' . get_term_link($cat) . '" item="999' . $cat->term_id . '">' . $cat->name . '</a>' .
					'<ul class="sub-menu">' . $events_list . '</ul>' .
				'</li>';
			
		elseif ($events) :
		
			$output .= '<li class="menu-item menu-item-type-taxonomy menu-item-object-event_category menu-item-999' . $cat->term_id . ' ' . ( ($cat->term_id == $current_object_id) ? 'current-menu-item current_page_item' : '' ) . '"><a href="' . get_term_link($cat) . '" item="999' . $cat->term_id . '">' . $cat->name . '</a></li>';
			
		endif;
	endforeach;
	
	return $output;
}

/***********************/
/* main menu functions */
/***********************/

/**
 * BH_add_event_categories_submenu
 * 
 * extend wp_nav_menu() to enable showing list of event categories under a given parent item object ID
 * used for displaying event categories in main menu under an event.php page template
 * 
 * @param	array	$items	the menu items, sorted by each menu item's menu order
 * @param	array	$args	an object containing wp_nav_menu() arguments
 * @return	array			extended $items with additional items list containing event categories
 */
add_filter('wp_nav_menu_objects', 'BH_add_event_categories_submenu', 10, 2);
function BH_add_event_categories_submenu($items, $args) {
	if ( empty($args->add_events_list_under) )
		return $items;
		
	$parent_item		= '';
	$parent_item_key	= '';
	$categories_list	= array();
	
	// get parent item and parent item key
	foreach ($items as $key => $item) :
		if ($item->object_id == $args->add_events_list_under) :
			$parent_item = $item;
			$parent_item_key = $key;
			
			break;
		endif;
	endforeach;
	
	if (!$parent_item_key)
		return $items;
		
	// add menu-item-has-children indicator
	if ( !in_array('menu-item-has-children', $items[$parent_item_key]->classes) )
		$items[$parent_item_key]->classes[] = 'menu-item-has-children';
		
	// get event categories
	$category_args = array(
		'orderby'	=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$categories = BH_get_cached_terms('event_category', $category_args);
	else
		$categories = get_terms('event_category', $category_args);
	
	// build categories list
	$index = 0;
	foreach ($categories as $cat) :
		if ( function_exists('BH_get_cached_event_category_events') )
			$events_exist = BH_get_cached_event_category_events($cat);
		else
			$events_exist = BH_get_event_category_events($cat->term_id);
		
		if ($events_exist) :
			$menu_item = new stdClass();
			
			$menu_item->ID					= '999' . $cat->term_id;
			$menu_item->post_status			= 'publish';
			$menu_item->post_parent			= $parent_item->object_id;
			$menu_item->post_type			= 'nav_menu_item';
			$menu_item->menu_item_parent	= $parent_item->ID;
			$menu_item->object_id			= $cat->term_id;
			$menu_item->object				= 'event_category';
			$menu_item->type				= 'taxonomy';
			$menu_item->type_label			= 'Event Category';
			$menu_item->url					= get_term_link($cat);
			$menu_item->title				= $cat->name;
			$menu_item->classes				= array('menu-item', 'menu-item-type-taxonomy', 'menu-item-object-event_category');
			
			// check current ancestor menu item
			if ( is_tax('event_category', $cat->term_id) ) :
				// modify parent item classes
				$items[$parent_item_key]->classes[]		= 'current-menu-ancestor';
				$items[$parent_item_key]->classes[]		= 'current-menu-parent';
				
				// add classes to current item
				$menu_item->classes[]					= 'current-menu-item';
			elseif ( is_singular('event') ) :
				// modify parent item classes
				if ( !in_array('current-menu-ancestor', $items[$parent_item_key]->classes) )
					$items[$parent_item_key]->classes[]	= 'current-menu-ancestor';
					
				if ( has_term($cat->term_id, 'event_category') ) :
					// add classes to current item
					$menu_item->classes[]				= 'current-menu-ancestor';
					$menu_item->classes[]				= 'current-menu-parent';
				endif;
			endif;
			
			$categories_list[$index++] = new WP_Post($menu_item);
		endif;
	endforeach;
	
	// merge categories list into $items
	if ($parent_item_key && $categories_list)
		array_splice($items, $parent_item_key, 0, $categories_list);
		
	return $items;
}

/**
 * BH_add_blog_categories_submenu
 * 
 * extend wp_nav_menu() to enable showing list of blog categories under a given parent item object ID
 * used for displaying blog categories in top menu under a blog.php page template
 * 
 * @param	array	$items	the menu items, sorted by each menu item's menu order
 * @param	array	$args	an object containing wp_nav_menu() arguments
 * @return	array			extended $items with additional items list containing blog categories
 */
add_filter('wp_nav_menu_objects', 'BH_add_blog_categories_submenu', 10, 2);
function BH_add_blog_categories_submenu($items, $args) {
	if ( empty($args->add_blog_list_under) )
		return $items;
		
	$parent_item		= '';
	$parent_item_key	= '';
	$categories_list	= array();
	
	// get parent item and parent item key
	foreach ($items as $key => $item) :
		if ($item->object_id == $args->add_blog_list_under) :
			$parent_item = $item;
			$parent_item_key = $key;
			
			break;
		endif;
	endforeach;
	
	if (!$parent_item_key)
		return $items;
		
	// add menu-item-has-children indicator
	if ( !in_array('menu-item-has-children', $items[$parent_item_key]->classes) )
		$items[$parent_item_key]->classes[] = 'menu-item-has-children';
		
	// get blog categories
	$category_args = array(
		'orderby'		=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$categories = BH_get_cached_terms('category', $category_args);
	else
		$categories = get_terms('category', $category_args);
	
	// build categories list
	$index = 0;
	foreach ($categories as $cat) :
		$menu_item = new stdClass();
		
		$menu_item->ID					= '999' . $cat->term_id;
		$menu_item->post_status			= 'publish';
		$menu_item->post_parent			= $parent_item->object_id;
		$menu_item->post_type			= 'nav_menu_item';
		$menu_item->menu_item_parent	= $parent_item->ID;
		$menu_item->object_id			= $cat->term_id;
		$menu_item->object				= 'category';
		$menu_item->type				= 'taxonomy';
		$menu_item->type_label			= 'Category';
		$menu_item->url					= get_term_link($cat);
		$menu_item->title				= $cat->name;
		$menu_item->classes				= array('menu-item', 'menu-item-type-taxonomy', 'menu-item-object-category');
		
		// check current ancestor menu item
		if ( is_category($cat->term_id) ) :
			// modify parent item classes
			$items[$parent_item_key]->classes[]		= 'current-menu-ancestor';
			$items[$parent_item_key]->classes[]		= 'current-menu-parent';
			
			// add classes to current item
			$menu_item->classes[]					= 'current-menu-item';
		elseif ( is_singular('post') ) :
			// modify parent item classes
			if ( !in_array('current-menu-ancestor', $items[$parent_item_key]->classes) )
				$items[$parent_item_key]->classes[]	= 'current-menu-ancestor';
				
			if ( has_term($cat->term_id, 'category') ) :
				// add classes to current item
				$menu_item->classes[]				= 'current-menu-ancestor';
				$menu_item->classes[]				= 'current-menu-parent';
			endif;
		endif;
		
		$categories_list[$index++] = new WP_Post($menu_item);
	endforeach;
	
	// merge categories list into $items
	if ($parent_item_key && $categories_list)
		array_splice($items, $parent_item_key, 0, $categories_list);
		
	return $items;
}