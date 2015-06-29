<?php
/**
 * BH theme menus
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register theme menus
 */
global $menus;

$menus = array(
	'top-menu'		=> __('Top Menu'),
	'main-menu'		=> __('Main Menu'),
	'footer-menu'	=> __('Footer Menu'),
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

/***************************/
/* top/main menu functions */
/***************************/

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

/**
 * BH_main_walker_nav_menu
 * 
 * main menu walker
 * transform the menu into list of HTML LIs containing menu item parent and depth level indicators for each
 */
class BH_main_walker_nav_menu extends Walker_Nav_Menu {
	// remove <ul> tags
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "";
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "";
	}
	
	// add menu item parent and depth level indicators to <li> tags
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		
		// depth dependent classes
		$depth_class_name = 'menu-item-depth-' . $depth;
		
		// passed classes
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = esc_attr( implode( ' ', apply_filters('nav_menu_css_class', array_filter($classes), $item) ) );
		
		// build <li> html
		$output .= '<li menu-item="'. $item->ID . '" menu-item-parent="' . $item->menu_item_parent . '" class="' . $depth_class_name . ' ' . $class_names . '">';
		
		// link attributes
		$attributes  = ! empty($item->attr_title)	? ' title="'	. esc_attr($item->attr_title) . '"' : '';
		$attributes .= ! empty($item->target    )	? ' target="'	. esc_attr($item->target    ) . '"' : '';
		$attributes .= ! empty($item->xfn       )	? ' rel="'		. esc_attr($item->xfn       ) . '"' : '';
		$attributes .= ! empty($item->url       )	? ' href="'		. esc_attr($item->url       ) . '"' : '';
		$attributes .= ' item="' . esc_attr($item->ID) . '"';
		
		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);
		
		// build <a> html
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		
		// close <li> html
		$output .= "</li>";
	}
	
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "";
	}
}

/**
 * BH_get_desktop_menu
 * 
 * transform HTML LIs structured menu to desktop main menu
 * generally this function considers only two levels menu items
 * 
 * @param	array	$menu	array of LI string elements
 * @return	string			two levels HTML LIs structure
 */
function BH_get_desktop_menu($menu) {
	if (!$menu)
		return;
		
	$output			= '';
	$current_level	= 0;
	
	foreach ($menu as $li) :
		$li_level = substr($li, strpos($li, 'class="menu-item-depth-') + 23, 1);
		if ($li_level <= 1) :
			if ($current_level < $li_level) :
				// open a new <ul> tag
				$output .= '<ul class="sub-menu">' . $li;
				$current_level++;
			elseif ($current_level > $li_level) :
				// close current <ul> tag
				$output .= '</li></ul></li>' . $li;
				$current_level--;
			else :
				// we are at the same level
				$output .= ($output) ? '</li>' . $li : $li;
			endif;
		endif;
	endforeach;
	
	// close last <ul> / <li> tags
	if ($output) :
		$output .= ($current_level > 0) ? '</li></ul></li>' : '</li>';
	endif;
	
	return $output;
}

/**
 * BH_get_mobile_menu
 * 
 * transform HTML LIs structured menu to mobile menu
 * 
 * @param	array	$menu			array of LI string elements
 * @param	bool	$is_top_menu	indicates whether $menu is the top menu or not (top menu is displayed at the first side menu level as well)
 * @return	string					mobile menu HTML LIs structure
 */
function BH_get_mobile_menu($menu, $is_top_menu = false) {
	if (!$menu)
		return;
		
	$output		= '';
	
	// $side_menus is built as array of arrays, each represents a complete side menu level
	// each $side_menus array key represents the menu item parent ID
	// first item in each $side_menus array represents the menu item parent title
	// second item in each $side_menus array represents the side menu level
	$side_menus	= array();
	
	// $items used to store all item names in order to index all potential menu item parents names
	// each $items key represents the menu item ID
	// each $items value represents the menu item name
	$items		= array();
	
	// $top_menu will be displayed as part of first level side menu
	global $top_menu;
	
	// build $side_menus array
	foreach ($menu as $li) :
		// collect LI information
		$menu_item_id_start_pos		= strpos($li, 'menu-item="') + 11;
		$menu_item_id_end_pos		= strpos($li, 'menu-item-parent') - 2;
		$menu_item_id				= substr($li, $menu_item_id_start_pos, ($menu_item_id_end_pos-$menu_item_id_start_pos));
		
		$menu_item_parent_start_pos	= strpos($li, 'menu-item-parent="') + 18;
		$menu_item_parent_end_pos	= strpos($li, 'class="menu-item-depth') - 2;
		$menu_item_parent			= substr($li, $menu_item_parent_start_pos, $menu_item_parent_end_pos-$menu_item_parent_start_pos);
		
		$menu_item_name_start_pos	= strpos($li, '<span>') + 6;
		$menu_item_name_end_pos		= strpos($li, '</span>');
		$menu_item_name				= substr($li, $menu_item_name_start_pos, $menu_item_name_end_pos-$menu_item_name_start_pos);
		
		$li_level					= substr($li, strpos($li, 'class="menu-item-depth-') + 23, 1);
		
		// store menu item id and name
		if ( !array_key_exists($menu_item_id, $items) )
			$items[$menu_item_id]	= $menu_item_name;
			
		// insert menu item into $side_menus accordingly
		if ( !array_key_exists($menu_item_parent, $side_menus) ) :
			// define a new $side_menus entry
			$side_menus[$menu_item_parent]		= array();
			$side_menus[$menu_item_parent][0]	= ( array_key_exists($menu_item_parent, $items) ) ? $items[$menu_item_parent] : '';
			$side_menus[$menu_item_parent][1]	= $li_level;
			
			if ($menu_item_parent == '0' && !$is_top_menu)
				$side_menus[0][2]				= '<li><a href="' . get_bloginfo('url') . '">' . __('Home', 'BH') . '</a>';		// </li> will be added later on
		endif;
		
		$side_menus[$menu_item_parent][]		= $li;
	endforeach;
	
	// build $output
	foreach ($side_menus as $menu_item_parent => $side_menu) :
		$output .= '<div class="' . ( (!($is_top_menu && $menu_item_parent == 0)) ? 'side-menu' : 'side-top-menu' ) . '" menu-level="' . ($side_menu[1] + 1) . '" menu-parent="' . $menu_item_parent . '">';
		
			// display menu title
			if ( !($is_top_menu && $menu_item_parent == 0) ) :
				$output .= '<div class="side-menu-top">';
					$output .= '<div class="sub-menu-title">' . ( ($side_menu[0]) ? $side_menu[0] : languages_switcher() ) . '</div>';
					$output .= '<span class="lines"></span>';
				$output .= '</div>';
			endif;
			
			// display menu content
			$output .= '<nav class="menu-main-menu-container">';
				$output .= '<ul>';
					$i = 2;
					while ( $i < count($side_menu) ) :
						$output .= $side_menu[$i++] . '</li>';
					endwhile;
				$output .= '</ul>';
				
				// display $top_menu
				if (!$is_top_menu && $menu_item_parent == 0 && $top_menu) :
					$edited_top_menu = explode('</li>', $top_menu);
					
					// last element is empty - remove it
					array_pop($edited_top_menu);
					
					$output .= BH_get_mobile_menu($edited_top_menu, true);
				endif;
			$output .= '</nav>';
			
		$output .= '</div>';
	endforeach;
	
	return $output;
}

/***************************/
/* product categories menu */
/***************************/

/**
 * BH_product_cat_menu
 */
function BH_product_cat_menu() {
	$args = array(
		'orderby'			=> 'term_order',
		'hide_empty'		=> 0,
		'title_li'			=> '',
		'show_option_none'	=> '',
		'taxonomy'			=> 'product_cat',
		'depth'				=> 1,
		'echo'				=> 0
	);
	$menu = wp_list_categories($args);
	
	if ($menu) :
		echo '<nav class="product-cat-menu"><ul class="nav navbar-nav">';
			echo $menu;
		echo '</ul></nav>';
	endif;
}

add_filter('wp_list_categories', 'BH_list_current_product_cat');
function BH_list_current_product_cat($content) {
	if (is_singular('product')) {
		global $post;
		
		$categories = wp_get_post_terms($post->ID, 'product_cat');
		
		foreach ($categories as $category)
			$content = preg_replace(
				"/class=\"(.*)\"><a ([^<>]*)>$category->name<\/a>/",
				"class=\"$1 current-cat\"><a $2>$category->name</a>",
				$content);
	}
	
	return $content;
}