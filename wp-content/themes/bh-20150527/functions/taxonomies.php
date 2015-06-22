<?php

	function BH_register_taxonomies() {
		BH_register_taxonomy_event_category();
		BH_register_taxonomy_occasion();
		BH_register_taxonomy_artist();
		BH_register_taxonomy_badge();
	}
	add_action('init', 'BH_register_taxonomies');
	
	// register taxonomy event_category
	function BH_register_taxonomy_event_category() {
		$labels = array(
			'name'							=> __('Event Categories'),
			'singular_name'					=> __('Event Category'),
			'menu_name'						=> __('Event Categories'),
			'all_items'						=> __('All Event Categories'),
			'edit_item'						=> __('Edit Event Category'),
			'view_item'						=> __('View Event Category'),
			'update_item'					=> __('Update Event Category'),
			'add_new_item'					=> __('Add New Event Category'),
			'new_item_name'					=> __('New Event Category Name'),
			'parent_item'					=> __('Event Parent Category'),
			'parent_item_colon'				=> __('Event Parent Category:'),
			'search_items'					=> __('Search Event Categories'),
			'popular_items'					=> __('Popular Event Categories'),
			'separate_items_with_commas'	=> __('Separate Event Categories with commas'),
			'add_or_remove_items'			=> __('Add or remove Event Categories'),
			'choose_from_most_used'			=> __('Choose from the most used Event Categories'),
			'not_found'						=> __('No Event Categories found')
		);
		
		$args = array(
			'labels'						=> $labels,
			'public'						=> true,
			'show_ui'						=> true,
			'show_in_nav_menus'				=> true,
			'show_tagcloud'					=> true,
			'show_admin_column'				=> true,
			'hierarchical'					=> true,
			'query_var'						=> true,
			'rewrite'						=> array(
					'slug'					=> 'event_category',
					'with_front'			=> false,
					'hierarchical'			=> false
			)
		);
		register_taxonomy('event_category', 'event', $args);
		flush_rewrite_rules();
	}
	
	// register taxonomy occasion
	function BH_register_taxonomy_occasion() {
		$labels = array(
			'name'							=> __('Occasions'),
			'singular_name'					=> __('Occasion'),
			'menu_name'						=> __('Occasions'),
			'all_items'						=> __('All Occasions'),
			'edit_item'						=> __('Edit Occasion'),
			'view_item'						=> __('View Occasion'),
			'update_item'					=> __('Update Occasion'),
			'add_new_item'					=> __('Add New Occasion'),
			'new_item_name'					=> __('New Occasion Name'),
			'parent_item'					=> __('Parent Occasion'),
			'parent_item_colon'				=> __('Parent Occasion:'),
			'search_items'					=> __('Search Occasions'),
			'popular_items'					=> __('Popular Occasions'),
			'separate_items_with_commas'	=> __('Separate Occasions with commas'),
			'add_or_remove_items'			=> __('Add or remove Occasions'),
			'choose_from_most_used'			=> __('Choose from the most used Occasions'),
			'not_found'						=> __('No Occasions found')
		);
		
		$args = array(
			'labels'						=> $labels,
			'public'						=> true,
			'show_ui'						=> true,
			'show_in_nav_menus'				=> true,
			'show_tagcloud'					=> true,
			'show_admin_column'				=> true,
			'hierarchical'					=> true,
			'query_var'						=> true,
			'rewrite'						=> array(
					'slug'					=> 'occasion',
					'with_front'			=> false,
					'hierarchical'			=> false
			)
		);
		register_taxonomy('occasion', 'product', $args);
		flush_rewrite_rules();
	}
	
	// register taxonomy artist
	function BH_register_taxonomy_artist() {
		$labels = array(
			'name'							=> __('Artists'),
			'singular_name'					=> __('Artist'),
			'menu_name'						=> __('Artists'),
			'all_items'						=> __('All Artists'),
			'edit_item'						=> __('Edit Artist'),
			'view_item'						=> __('View Artist'),
			'update_item'					=> __('Update Artist'),
			'add_new_item'					=> __('Add New Artist'),
			'new_item_name'					=> __('New Artist Name'),
			'parent_item'					=> __('Parent Artist'),
			'parent_item_colon'				=> __('Parent Artist:'),
			'search_items'					=> __('Search Artists'),
			'popular_items'					=> __('Popular Artists'),
			'separate_items_with_commas'	=> __('Separate Artists with commas'),
			'add_or_remove_items'			=> __('Add or remove Artists'),
			'choose_from_most_used'			=> __('Choose from the most used Artists'),
			'not_found'						=> __('No Artists found')
		);
		
		$args = array(
			'labels'						=> $labels,
			'public'						=> true,
			'show_ui'						=> true,
			'show_in_nav_menus'				=> true,
			'show_tagcloud'					=> true,
			'show_admin_column'				=> true,
			'hierarchical'					=> true,
			'query_var'						=> true,
			'rewrite'						=> array(
					'slug'					=> 'artist',
					'with_front'			=> false,
					'hierarchical'			=> false
			)
		);
		register_taxonomy('artist', 'product', $args);
		flush_rewrite_rules();
	}
	
	// register taxonomy badge
	function BH_register_taxonomy_badge() {
		$labels = array(
			'name'							=> __('Badges'),
			'singular_name'					=> __('Badge'),
			'menu_name'						=> __('Badges'),
			'all_items'						=> __('All Badges'),
			'edit_item'						=> __('Edit Badge'),
			'view_item'						=> __('View Badge'),
			'update_item'					=> __('Update Badge'),
			'add_new_item'					=> __('Add New Badge'),
			'new_item_name'					=> __('New Badge Name'),
			'parent_item'					=> __('Parent Badge'),
			'parent_item_colon'				=> __('Parent Badge:'),
			'search_items'					=> __('Search Badges'),
			'popular_items'					=> __('Popular Badges'),
			'separate_items_with_commas'	=> __('Separate Badges with commas'),
			'add_or_remove_items'			=> __('Add or remove Badges'),
			'choose_from_most_used'			=> __('Choose from the most used Badges'),
			'not_found'						=> __('No Badges found')
		);
		
		$args = array(
			'labels'						=> $labels,
			'public'						=> true,
			'show_ui'						=> true,
			'show_in_nav_menus'				=> true,
			'show_tagcloud'					=> true,
			'show_admin_column'				=> true,
			'hierarchical'					=> true,
			'query_var'						=> true,
			'rewrite'						=> array(
					'slug'					=> 'badge',
					'with_front'			=> false,
					'hierarchical'			=> false
			)
		);
		register_taxonomy('badge', 'product', $args);
		flush_rewrite_rules();
	}
	
	// add filter option for admin columns
	class taxonomies_filter_walker extends Walker_CategoryDropdown {
		
		function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
			$pad = str_repeat('&nbsp;', $depth * 3);
			$cat_name = apply_filters('list_cats', $category->name, $category);
			
			if( !isset($args['value']) ){
				$args['value'] = ( $category->taxonomy != 'category' ? 'slug' : 'id' );
			}
			
			$value = ( $args['value'] == 'slug' ? $category->slug : $category->term_id );
			
			$output .= "\t<option class=\"level-$depth\" value=\"" . $value . "\"";
			if ( $value === (string) $args['selected'] ) {
				$output .= ' selected="selected"';
			}
			$output .= '>';
			$output .= $pad . $cat_name;
			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;(' . $category->count . ')';
				
			$output .= "</option>\n";
		}
		
	}
	
	function taxonomies_filter_list() {
		if ( !is_admin() ) return;
		
		$screen = get_current_screen();
		global $wp_query;
		
		if ( $screen->post_type == 'event' ) {
			
			$taxonomies = array(
				'event_category'	=> 'Event Categories'
			);
			
		} elseif ( $screen->post_type == 'product' ) {
			
			$taxonomies = array(
				'occasion'			=> 'Occasions',
				'artist'			=> 'Artists',
				'badge'				=> 'Badges'
			);
			
		}
		
		if ( $screen->post_type == 'event' || $screen->post_type == 'product' ) {
			foreach ($taxonomies as $slug => $name) :
			
				$args = array(
					'show_option_all'	=> 'Show All ' . $name,
					'taxonomy'			=> $slug,
					'name'				=> $slug,
					'orderby'			=> 'name',
					'selected'			=> ( isset( $wp_query->query[$slug] ) ? $wp_query->query[$slug] : '' ),
					'hierarchical'		=> true,
					'show_count'		=> true,
					'hide_empty'		=> false,
					'walker'			=> new taxonomies_filter_walker(),
					'value'				=> 'slug'
				);
				wp_dropdown_categories($args);
				
			endforeach;
		}
	}
	add_action('restrict_manage_posts', 'taxonomies_filter_list');
	
	/**********************************/
	/* transient support action hooks */
	/**********************************/
	
	if ( function_exists('BH_create_category') )
		add_action('create_category',		'BH_create_category');
		
	if ( function_exists('BH_delete_category') )
		add_action('delete_category',		'BH_delete_category');
		
	if ( function_exists('BH_edit_category') )
		add_action('edit_category',			'BH_edit_category');
		
		
	if ( function_exists('BH_create_event_category') )
		add_action('create_event_category',	'BH_create_event_category');
		
	if ( function_exists('BH_delete_event_category') )
		add_action('delete_term_taxonomy',	'BH_delete_event_category');
		
	if ( function_exists('BH_edit_event_category') )
		add_action('edit_event_category',	'BH_edit_event_category');
		
	if ( function_exists('BH_create_event_category') )
		add_action('edited_event_category',	'BH_create_event_category');

?>