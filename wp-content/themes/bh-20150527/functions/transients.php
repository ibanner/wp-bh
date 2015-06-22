<?php

	/**
	 * set/delete 'last updated' transients:
	 * 
	 * =========================================================================================================================================+
	 * BH_create_category																														|
	 * =========================================================================================================================================+
	 * called:														set:		1. MC-category-<language code>-terms-updated
	 * 1. after a category term is created
	 * 																			2. MC-top-menu-updated
	 * 																			3. MC-main-menu-updated
	 * 																			4. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_delete_category																														|
	 * =========================================================================================================================================+
	 * called:														set:		1. MC-category-<language code>-terms-updated
	 * 1. after a category term is deleted
	 * 																			2. MC-top-menu-updated
	 * 																			3. MC-main-menu-updated
	 * 																			4. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_edit_category																															|
	 * =========================================================================================================================================+
	 * called:														set:		1. MC-category-<language code>-terms-updated
	 * 1. after a category term is edited
	 * 																			2. MC-top-menu-updated
	 * 																			3. MC-main-menu-updated
	 * 																			4. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_create_event_category																													|
	 * =========================================================================================================================================+
	 * called:														set:		1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 1. after an event category term is created/edited
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 
	 * 																			3. MC-top-menu-updated
	 * 																			4. MC-main-menu-updated
	 * 																			5. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_delete_event_category																													|
	 * =========================================================================================================================================+
	 * called:														delete:		1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 1. before an event category term is deleted
	 * 																set:		1. MC-event_category-<language code>-terms-updated
	 * 
	 * 																			2. MC-top-menu-updated
	 * 																			3. MC-main-menu-updated
	 * 																			4. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_edit_event_category																													|
	 * =========================================================================================================================================+
	 * called:														delete:		1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 1. before an event category term is edited
	 * 
	 * =========================================================================================================================================+
	 * BH_before_delete_post																													|
	 * =========================================================================================================================================+
	 * called:														set for event:
	 * 1. before a post is deleted												1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 																			3. MC-events-<language code>-wp-query-updated
	 * 
	 * 																set for post:
	 * 																			1. MC-category-<language code>-terms-updated
	 * 
	 * 																set for both event and post:
	 * 																			1. MC-top-menu-updated
	 * 																			2. MC-main-menu-updated
	 * 																			3. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_trashed_post																															|
	 * =========================================================================================================================================+
	 * called:														set for event:
	 * 1. after a post is sent to the trash										1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 																			3. MC-events-<language code>-wp-query-updated
	 * 
	 * 																set for post:
	 * 																			1. MC-category-<language code>-terms-updated
	 * 
	 * 																set for both event and post:
	 * 																			1. MC-top-menu-updated
	 * 																			2. MC-main-menu-updated
	 * 																			3. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_untrashed_post																														|
	 * =========================================================================================================================================+
	 * called:														set for event:
	 * 1. after a post is restored from the trash								1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 																			3. MC-events-<language code>-wp-query-updated
	 * 
	 * 																set for post:
	 * 																			1. MC-category-<language code>-terms-updated
	 * 
	 * 																set for both event and post:
	 * 																			1. MC-top-menu-updated
	 * 																			2. MC-main-menu-updated
	 * 																			3. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_pre_post_update																														|
	 * =========================================================================================================================================+
	 * called:														set for event:
	 * 1. before an existing post is updated in the database					1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 																			3. MC-events-<language code>-wp-query-updated
	 * 
	 * 																set for post:
	 * 																			1. MC-category-<language code>-terms-updated
	 * 
	 * 																set for both event and post:
	 * 																			1. MC-top-menu-updated
	 * 																			2. MC-main-menu-updated
	 * 																			3. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_save_post																																|
	 * =========================================================================================================================================+
	 * called:														set for event:
	 * 1. once a post has been saved											1. MC-' . substr($category->slug, 0, 35) . '-events-updated
	 * 																			2. MC-event_category-<language code>-terms-updated
	 * 																			3. MC-events-<language code>-wp-query-updated
	 * 
	 * 																set for post:
	 * 																			1. MC-category-<language code>-terms-updated
	 * 
	 * 																set for both event and post:
	 * 																			1. MC-top-menu-updated
	 * 																			2. MC-main-menu-updated
	 * 																			3. MC-footer-menu-updated
	 * 
	 * =========================================================================================================================================+
	 * BH_acf_save_options																														|
	 * =========================================================================================================================================+
	 * called:														set:		1. MC-contact-details-<language code>-updated
	 * 1. after ACF options page has beed saved
	 * 
	 */
	
	/***************************/
	/* get transient functions */
	/***************************/
	
	/**
	 * BH_get_cached_desktop_menu
	 * 
	 * transient version for functions/menus.php -> BH_get_desktop_menu()
	 * 
	 * @param	array		$menu			array of LI string elements
	 * @param	string		$menu_name		menu theme location - used to check validity against menu transient
	 * @return	string						two levels HTML LIs structure
	 */
	function BH_get_cached_desktop_menu($menu, $menu_name) {
		if (!$menu)
			return;
			
		$desktop_menu	= '';
		$locations		= get_nav_menu_locations();
		
		if ( isset($menu_name) && isset($locations[$menu_name]) ) :
			$desktop_menu_key		= 'MC-' . md5( serialize('desktop') . serialize($locations[$menu_name]) . serialize(get_queried_object()) );
			$desktop_menu_in_cache	= get_transient($desktop_menu_key);
			$last_updated			= get_transient('MC-' . $menu_name . '-updated');
			
			if ( isset($desktop_menu_in_cache['data']) && isset($last_updated) && $last_updated < $desktop_menu_in_cache['time'] )
				// return menu from cache
				return $desktop_menu_in_cache['data'];
				
			// menu isn't valid or not exist in cache
			if (!$last_updated)
				set_transient('MC-' . $menu_name . '-updated', time());
				
			$desktop_menu = BH_get_desktop_menu($menu);
			$data = array('time' => time(), 'data' => $desktop_menu);
			set_transient($desktop_menu_key, $data);
		endif;
		
		return $desktop_menu;
	}
	
	/**
	 * BH_get_cached_mobile_menu
	 * 
	 * transient version for functions/menus.php -> BH_get_mobile_menu()
	 * 
	 * @param	array		$menu			array of LI string elements
	 * @param	string		$menu_name		menu theme location - used to check validity against menu transient
	 * @return	string						mobile menu HTML LIs structure
	 */
	function BH_get_cached_mobile_menu($menu, $menu_name) {
		if (!$menu)
			return;
			
		$mobile_menu	= '';
		$locations		= get_nav_menu_locations();
		
		if ( isset($menu_name) && isset($locations[$menu_name]) ) :
			$mobile_menu_key		= 'MC-' . md5( serialize('mobile') . serialize($locations[$menu_name]) . serialize(get_queried_object()) );
			$mobile_menu_in_cache	= get_transient($mobile_menu_key);
			$last_updated			= get_transient('MC-' . $menu_name . '-updated');
			
			if ( isset($mobile_menu_in_cache['data']) && isset($last_updated) && $last_updated < $mobile_menu_in_cache['time'] )
				// return menu from cache
				return $mobile_menu_in_cache['data'];
				
			// menu isn't valid or not exist in cache
			if (!$last_updated)
				set_transient('MC-' . $menu_name . '-updated', time());
				
			// generate mobile menu
			$mobile_menu = BH_get_mobile_menu($menu);
			$data = array('time' => time(), 'data' => $mobile_menu);
			set_transient($mobile_menu_key, $data);
		endif;
		
		return $mobile_menu;
	}
	
	/**
	 * BH_get_cached_nav_menu_items
	 * 
	 * transient version for wp_get_nav_menu_items()
	 * 
	 * @param	string		$menu_name		menu theme location
	 * @return	string
	 */
	function BH_get_cached_nav_menu_items($menu_name) {
		$locations	= get_nav_menu_locations();
		$menu_items	= '';
		
		if ( isset($menu_name) && isset($locations[$menu_name]) ) :
			$menu_items_key			= 'MC-' . md5( serialize($locations[$menu_name]) );
			$menu_items_in_cache	= get_transient($menu_items_key);
			$last_updated			= get_transient('MC-' . $menu_name . '-updated');
			
			if ( isset($menu_items_in_cache['data']) && isset($last_updated) && $last_updated < $menu_items_in_cache['time'] )
				// return menu items from cache
				return $menu_items_in_cache['data'];
				
			// menu items aren't valid or not exist in cache
			if (!$last_updated)
				set_transient('MC-' . $menu_name . '-updated', time());
				
			$menu_items = wp_get_nav_menu_items($locations[$menu_name]);
			$data = array('time' => time(), 'data' => $menu_items);
			set_transient($menu_items_key, $data);
		endif;
		
		return $menu_items;
	}
	
	/**
	 * BH_get_cached_terms
	 * 
	 * transient version for get_terms()
	 * 
	 * @param	string		$taxonomy_slug		the taxonomy to retrieve terms from
	 * @param	array		$args
	 * @return	array							array of term objects or an empty array if no terms were found
	 */
	function BH_get_cached_terms($taxonomy_slug, $args) {
		$terms			= array();
		
		if ( !isset($taxonomy_slug) )
			return $terms;
		
		$terms_key		= 'MC-' . md5( serialize($taxonomy_slug) . serialize($args) . serialize(ICL_LANGUAGE_CODE) );
		$terms_in_cache	= get_transient($terms_key);
		$last_updated	= get_transient('MC-' . substr($taxonomy_slug, 0, 32) . '-' . ICL_LANGUAGE_CODE . '-terms-updated');
		
		if ( isset($terms_in_cache['data']) && isset($last_updated) && $last_updated < $terms_in_cache['time'] )
			// return terms from cache
			return $terms_in_cache['data'];
			
		// terms aren't valid or not exist in cache
		if (!$last_updated)
			set_transient('MC-' . substr($taxonomy_slug, 0, 32) . '-' . ICL_LANGUAGE_CODE . '-terms-updated', time());
			
		$terms = get_terms($taxonomy_slug, $args);
		$data = array('time' => time(), 'data' => $terms);
		set_transient($terms_key, $data);
		
		return $terms;
	}
	
	/**
	 * BH_get_cached_event_category_events
	 * 
	 * transient version for functions/events.php -> BH_get_event_category_events()
	 * 
	 * @param	object		$category		event category object
	 * @return	mix							array of post objects or FALSE if no post objects were found
	 */
	function BH_get_cached_event_category_events($category) {
		$events				= false;
		
		if ( !isset($category) )
			return $events;
		
		$events_key			= 'MC-' . md5( serialize($category->slug) );
		$events_in_cache	= get_transient($events_key);
		$last_updated		= get_transient('MC-' . substr($category->slug, 0, 35) . '-events-updated');
		
		if ( isset($events_in_cache['data']) && isset($last_updated) && $last_updated < $events_in_cache['time'] )
			// return category events from cache
			return $events_in_cache['data'];
			
		// category events aren't valid or not exist in cache
		if (!$last_updated)
			set_transient('MC-' . substr($category->slug, 0, 35) . '-events-updated', time());
			
		$events = BH_get_event_category_events($category->term_id);
		$data = array('time' => time(), 'data' => $events);
		set_transient($events_key, $data);
		
		return $events;
	}
	
	/**
	 * BH_get_cached_wp_query
	 * 
	 * get WP_Query results from cache
	 * 
	 * @param	array		$args			WP_Query arguments
	 * @param	string		$transient		referenced transient - used to check validity against query transient
	 * @return	array						array of post objects or an empty array if no posts were found
	 */
	function BH_get_cached_wp_query($args, $transient) {
		if ( !isset($args) || !isset($transient) )
			return;
			
		$posts_key		= 'MC-' . md5( serialize($args) . serialize($transient) );
		$posts_in_cache	= get_transient($posts_key);
		$last_updated	= get_transient('MC-' . $transient . '-wp-query-updated');
		
		if ( isset($posts_in_cache['data']) && isset($last_updated) && $last_updated < $posts_in_cache['time'] )
			// return posts from cache
			return $posts_in_cache['data'];
			
		// posts aren't valid or not exist in cache
		if (!$last_updated)
			set_transient('MC-' . $transient . '-wp-query-updated', time());
			
		global $post;
		
		$posts = array();
		$query = new WP_Query($args);
		
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			$posts[] = $post;
		endwhile; endif; wp_reset_postdata();
		
		$data = array('time' => time(), 'data' => $posts);
		set_transient($posts_key, $data);
		
		return $posts;
	}
	
	/**
	 * BH_get_cached_contact_details
	 * 
	 * transient version for functions/template.php -> BH_get_contact_details()
	 * 
	 * @return	string
	 */
	function BH_get_cached_contact_details() {
		$section_key		= 'MC-' . md5( serialize('contact details') . serialize(ICL_LANGUAGE_CODE) );
		$section_in_cache	= get_transient($section_key);
		$last_updated		= get_transient('MC-contact-details-' . ICL_LANGUAGE_CODE . '-updated');
		
		if ( isset($section_in_cache['data']) && isset($last_updated) && $last_updated < $section_in_cache['time'] )
			// return section from cache
			return $section_in_cache['data'];
			
		// section isn't valid or not exist in cache
		if (!$last_updated)
			set_transient('MC-contact-details-' . ICL_LANGUAGE_CODE . '-updated', time());
			
		$section = BH_get_contact_details();
		$data = array('time' => time(), 'data' => $section);
		set_transient($section_key, $data);
		
		return $section;
	}
	
	/**********************************/
	/* set/delete transient functions */
	/**********************************/
	
	/**
	 * BH_create_category
	 */
	function BH_create_category() {
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set category terms 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-category-' . $key . '-terms-updated', time());
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_delete_category
	 */
	function BH_delete_category() {
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set category terms 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-category-' . $key . '-terms-updated', time());
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_edit_category
	 */
	function BH_edit_category() {
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set category terms 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-category-' . $key . '-terms-updated', time());
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_create_event_category
	 * 
	 * @param	int		$term_id
	 */
	function BH_create_event_category($term_id) {
		$category = get_term_by('id', $term_id, 'event_category');
		
		// set a new event category events 'last updated' transient
		set_transient('MC-' . substr($category->slug, 0, 35) . '-events-updated', time());
		
		// set event category terms 'last updated' transients
		BH_set_cached_event_category_terms();
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_delete_event_category
	 * 
	 * @param	int		$tt_id
	 */
	function BH_delete_event_category($tt_id) {
		$category = get_term_by('term_taxonomy_id', $tt_id, 'event_category');
		
		if ($category) :	// need to be checked if $tt_id belongs to 'event_category' taxonomy
			// delete event category events 'last updated' transient
			delete_transient('MC-' . substr($category->slug, 0, 35) . '-events-updated', time());
			
			// delete specific event category events transient
			delete_transient('MC-' . md5( serialize($category->slug) ) );
			
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set menu 'last updated' transients
			BH_set_cached_menus();
		endif;
	}
	
	/**
	 * BH_edit_event_category
	 * 
	 * @param	int		$term_id
	 */
	function BH_edit_event_category($term_id) {
		$category = get_term_by('id', $term_id, 'event_category');
		
		// delete event category events 'last updated' transient
		delete_transient('MC-' . substr($category->slug, 0, 35) . '-events-updated', time());
		
		// delete specific event category events transient
		delete_transient('MC-' . md5( serialize($category->slug) ) );
	}
	
	/**
	 * BH_before_delete_post
	 * 
	 * @param	int			$post_id
	 */
	function BH_before_delete_post($post_id) {
		if ( 'event' == get_post_type($post_id) ) :
		
			// event
			$categories = get_the_terms($post_id, 'event_category');
			
			if ($categories)
				foreach ($categories as $cat)
					set_transient('MC-' . substr($cat->slug, 0, 35) . '-events-updated', time());
					
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set relevant WP_Query 'last updated' transients
			BH_set_cached_wp_query('events', true);
			
		else :
		
			// post

			// set category terms 'last updated' transient
			BH_set_cached_category_terms();
			
		endif;
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_trashed_post
	 * 
	 * @param	int			$post_id
	 */
	function BH_trashed_post($post_id) {
		if ( 'event' == get_post_type($post_id) ) :
		
			// event
			$categories = get_the_terms($post_id, 'event_category');
			
			if ($categories)
				foreach ($categories as $cat)
					set_transient('MC-' . substr($cat->slug, 0, 35) . '-events-updated', time());
					
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set relevant WP_Query 'last updated' transients
			BH_set_cached_wp_query('events', true);
			
		else :
		
			// post

			// set category terms 'last updated' transient
			BH_set_cached_category_terms();
			
		endif;
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_untrashed_post
	 * 
	 * @param	int			$post_id
	 */
	function BH_untrashed_post($post_id) {
		if ( 'event' == get_post_type($post_id) ) :
		
			// event
			$categories = get_the_terms($post_id, 'event_category');
			
			if ($categories)
				foreach ($categories as $cat)
					set_transient('MC-' . substr($cat->slug, 0, 35) . '-events-updated', time());
					
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set relevant WP_Query 'last updated' transients
			BH_set_cached_wp_query('events', true);
			
		else :
		
			// post

			// set category terms 'last updated' transient
			BH_set_cached_category_terms();
			
		endif;
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_pre_post_update
	 * 
	 * @param	int			$post_id
	 */
	function BH_pre_post_update($post_id) {
		if ( 'event' == get_post_type($post_id) ) :
		
			// event
			$categories = get_the_terms($post_id, 'event_category');
			
			if ($categories)
				foreach ($categories as $cat)
					set_transient('MC-' . substr($cat->slug, 0, 35) . '-events-updated', time());
					
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set relevant WP_Query 'last updated' transients
			BH_set_cached_wp_query('events', true);
			
		else :
		
			// post

			// set category terms 'last updated' transient
			BH_set_cached_category_terms();
			
		endif;
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_save_post
	 * 
	 * @param	int			$post_id
	 */
	function BH_save_post($post_id) {
		if ( 'event' == get_post_type($post_id) ) :
		
			// event
			$categories = get_the_terms($post_id, 'event_category');
			
			if ($categories)
				foreach ($categories as $cat)
					set_transient('MC-' . substr($cat->slug, 0, 35) . '-events-updated', time());
					
			// set event category terms 'last updated' transients
			BH_set_cached_event_category_terms();
			
			// set relevant WP_Query 'last updated' transients
			BH_set_cached_wp_query('events', true);
			
		else :
		
			// post

			// set category terms 'last updated' transient
			BH_set_cached_category_terms();
			
		endif;
		
		// set menu 'last updated' transients
		BH_set_cached_menus();
	}
	
	/**
	 * BH_acf_save_options
	 */
	function BH_acf_save_options($post_id) {
		if ($post_id != 'options')
			return;
			
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set contact details 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-contact-details-' . $key . '-updated', time());
	}
	
	/*****************************************/
	/* set/delete transient helper functions */
	/*****************************************/
	
	/**
	 * BH_set_cached_category_terms
	 */
	function BH_set_cached_category_terms() {
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set category terms 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-category-' . $key . '-terms-updated', time());
	}
	
	/**
	 * BH_set_cached_event_category_terms
	 */
	function BH_set_cached_event_category_terms() {
		$languages = icl_get_languages('skip_missing=0');
		
		if ($languages)
			// set event category terms 'last updated' transient for each language
			foreach ($languages as $key => $val)
				set_transient('MC-event_category-' . $key . '-terms-updated', time());
	}
	
	/**
	 * BH_set_cached_menus
	 */
	function BH_set_cached_menus() {
		global $menus;
		
		if ($menus)
			foreach ($menus as $key => $val)
				set_transient('MC-' . $key . '-updated', time());
	}
	
	/**
	 * BH_set_cached_wp_query
	 * 
	 * @param	string		$transient		referenced transient to be updated
	 * @param	boolean		$lang			if TRUE set transient for each language, default to FALSE
	 */
	function BH_set_cached_wp_query($transient, $lang = false) {
		if ($lang) {
			$languages = icl_get_languages('skip_missing=0');
			
			if ($languages) {
				foreach ($languages as $key => $val) {
					set_transient('MC-' . $transient . '-' . $key . '-wp-query-updated', time());
				}
			}
		}
		
		else {
			set_transient('MC-' . $transient . '-wp-query-updated', time());
		}
	}

?>