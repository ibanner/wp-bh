<?php

	function BH_register_posttypes() {
		BH_register_posttype_event();
	}
	add_action('init', 'BH_register_posttypes');
	
	// register post type event
	function BH_register_posttype_event() {
		$labels = array(
			'name'					=> __('Events'),
			'singular_name'			=> __('Event'),
			'menu_name'				=> __('Events'),
			'all_items'				=> __('All Events'),
			'add_new'				=> __('Add New'),
			'add_new_item'			=> __('Add New Event'),
			'edit_item'				=> __('Edit Event'),
			'new_item'				=> __('New Event'),
			'view_item'				=> __('View Event'),
			'search_items'			=> __('Search Events'),
			'not_found'				=> __('No Events Found'),
			'not_found_in_trash'	=> __('No Events Found in Trash'), 
			'parent_item_colon'		=> __('')
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
		register_post_type('event', $args);
	}
	
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

?>