<?php
/**
 * Post Types
 *
 * @author		Beit Hatfutsot
 * @package		bh/functions
 * @version		2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * BH_register_posttypes
 *
 * This function registers Post Types
 *
 * @param	N/A
 * @return	N/A
 */
function BH_register_posttypes() {

	BH_register_posttype_event();
	BH_register_posttype_gallery();

}
add_action( 'init', 'BH_register_posttypes' );

/**
 * BH_register_posttype_event
 *
 * This function registers the "event" Post Type
 *
 * @param	N/A
 * @return	N/A
 */
function BH_register_posttype_event() {

	$labels = array(
		'name'					=> 'Events',
		'singular_name'			=> 'Event',
		'menu_name'				=> 'Events',
		'all_items'				=> 'All Events',
		'add_new'				=> 'Add New',
		'add_new_item'			=> 'Add New Event',
		'edit_item'				=> 'Edit Event',
		'new_item'				=> 'New Event',
		'view_item'				=> 'View Event',
		'search_items'			=> 'Search Events',
		'not_found'				=> 'No Events Found',
		'not_found_in_trash'	=> 'No Events Found in Trash'
	);
	
	$args = array(
		'labels'				=> $labels,
		'public'				=> true,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'show_ui'				=> true,
		'show_in_nav_menus'		=> true,
		'show_in_menu'			=> true,
		'show_in_admin_bar'		=> true,
		'menu_position'			=> 20,
		'menu_icon'				=> null,
		'capability_type'		=> 'post',
		'hierarchical'			=> true,
		'supports'				=> array('title', 'editor', 'revisions'),
		'taxonomies'			=> array(),
		'has_archive'			=> true,
		'rewrite'				=> array('slug' => 'event', 'with_front' => false),
		'query_var'				=> true
	);
	register_post_type( 'event', $args );

}

/**
 * BH_register_posttype_gallery
 *
 * This function registers the "gallery" Post Type
 *
 * @param	N/A
 * @return	N/A
 */
function BH_register_posttype_gallery() {

	$labels = array(
		'name'					=> 'Galleries',
		'singular_name'			=> 'Gallery',
		'menu_name'				=> 'Galleries',
		'all_items'				=> 'All Galleries',
		'add_new'				=> 'Add New',
		'add_new_item'			=> 'Add New Gallery',
		'edit_item'				=> 'Edit Gallery',
		'new_item'				=> 'New Gallery',
		'view_item'				=> 'View Gallery',
		'search_items'			=> 'Search Galleries',
		'not_found'				=> 'No Galleries Found',
		'not_found_in_trash'	=> 'No Galleries Found in Trash'
	);
	
	$args = array(
		'labels'				=> $labels,
		'public'				=> true,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'show_ui'				=> true,
		'show_in_nav_menus'		=> true,
		'show_in_menu'			=> true,
		'show_in_admin_bar'		=> true,
		'menu_position'			=> 21,
		'menu_icon'				=> null,
		'capability_type'		=> 'post',
		'hierarchical'			=> false,
		'supports'				=> array('title'),
		'taxonomies'			=> array(),
		'has_archive'			=> true,
		'rewrite'				=> array('slug' => 'gallery', 'with_front' => false),
		'query_var'				=> true
	);
	register_post_type( 'gallery', $args );

}

/**
 * BH_event_subpannel_columns
 *
 * This function adds columns to the "event" Post Type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @return	(array)
 */
function BH_event_subpannel_columns( $columns ) {

	$event_columns = array(
		'start_date'	=> 'Start Date',
		'end_date'		=> 'End Date'
	);

	$columns = array_merge(
		array_slice($columns, 0, -1),	// before
		$event_columns,					// inserted
		array_slice($columns, -1)		// after
	);

	// return
	return $columns;

}
add_filter( 'manage_event_posts_columns', 'BH_event_subpannel_columns' );

/**
 * BH_event_subpannel_columns_values
 *
 * This function adds columns values to the "event" Post Type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function BH_event_subpannel_columns_values( $columns, $post_id ) {

	// Get variables
	global $post;

	if ($columns == 'start_date') {

		$start_date = get_field('acf-event_start_date', $post->ID);
		echo ($start_date) ? date( 'd/m/Y', strtotime($start_date) ) : '';

	} else if ($columns == 'end_date') {

		$end_date = get_field('acf-event_end_date', $post->ID);
		echo ($end_date) ? date( 'd/m/Y', strtotime($end_date) ) : '';

	}

}
add_action( 'manage_event_posts_custom_column', 'BH_event_subpannel_columns_values', 10, 2 );

/**
 * BH_gallery_subpannel_columns
 *
 * This function adds columns to the "gallery" Post Type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @return	(array)
 */
function BH_gallery_subpannel_columns( $columns ) {

	$gallery_columns = array(
		'shortcode'	=> 'Shortcode'
	);

	$columns = array_merge(
		array_slice($columns, 0, -1),	// before
		$gallery_columns,				// inserted
		array_slice($columns, -1)		// after
	);

	// return
	return $columns;

}
add_filter( 'manage_gallery_posts_columns', 'BH_gallery_subpannel_columns' );

/**
 * BH_gallery_subpannel_columns_values
 *
 * This function adds columns values to the "gallery" Post Type subpannel
 *
 * @param	$columns (array) Subpannel columns
 * @param	$post_id (int) Post ID
 * @return	N/A
 */
function BH_gallery_subpannel_columns_values( $columns, $post_id ) {

	// Get variables
	global $post;

	if ($columns == 'shortcode') {

		$shortcode = esc_attr( sprintf( '[BH-gallery id="%d" title="%s"]', $post_id, get_the_title( $post_id ) ) );
		$shortcode = '<span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="' . $shortcode . '" class="large-text code"></span>';

		echo $shortcode ?? '';

	}

}
add_action( 'manage_gallery_posts_custom_column', 'BH_gallery_subpannel_columns_values', 10, 2 );

/**********************************/
/* transient support action hooks */
/**********************************/

if ( function_exists('BH_before_delete_post') )
	add_action('before_delete_post',	'BH_before_delete_post',	10, 1);
	
if ( function_exists('BH_trashed_post') )
	add_action('trashed_post',			'BH_trashed_post',			10, 1);
	
if ( function_exists('BH_untrashed_post') )
	add_action('untrashed_post',		'BH_untrashed_post',		10, 1);
	
if ( function_exists('BH_pre_post_update') )
	add_action('pre_post_update',		'BH_pre_post_update',		10, 1);
	
if ( function_exists('BH_save_post') )
	add_action('save_post',				'BH_save_post',				10, 1);