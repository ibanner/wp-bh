<?php

	/**
	 * initiate wordpress base path in order to call this file from cli
	 */
	
	if (php_sapi_name() !== 'cli') {
		die("Meant to be run from command line");
	}
	
	function find_wordpress_base_path() {
		$dir = dirname(__FILE__);
		do {
			if( file_exists($dir . "/wp-config.php") ) {
				return $dir;
			}
		} while( $dir = realpath("$dir/..") );
		
		return null;
	}
	
	define('BASE_PATH', find_wordpress_base_path() . "/");
	define('WP_USE_THEMES', false);
	
	global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
	require(BASE_PATH . 'wp-load.php');
	
	/**
	 * cron job script
	 * 
	 * set event's categories transients for all events, meeting one of the following conditions:
	 * 1. End date is equal to yesterday - an event has been removed
	 * 2. Start date is equal to current date - a new event has been added
	 * 
	 * need to be called from www-data crontab:
	 * 5 0 * * * php /data/wp-bh/wp-content/themes/bh/cron/event-category-transients.php
	 */
	$event_categories = array();	// holds all event categories slugss in order to set their transient
	
	$date = new DateTime();
	$date->add(DateInterval::createFromDateString('yesterday'));
	
	$args = array(
		'post_type'			=> 'event',
		'posts_per_page'	=> -1,
		'no_found_rows'		=> true,
		'meta_query'		=> array(
			'relation'		=> 'OR',
			array(
				'key'		=> 'acf-event_end_date',
				'value'		=> $date->format('Ymd'),
				'type'		=> 'DATE',
				'compare'	=> '='
			),
			array(
				'key'		=> 'acf-event_start_date',
				'value'		=> date('Ymd'),
				'type'		=> 'DATE',
				'compare'	=> '='
			)
		)
	);
	$events = new WP_Query($args);
	
	if ($events->have_posts()) : while ($events->have_posts()) : $events->the_post();
		$categories = get_the_terms($post->ID, 'event_category');
		
		if ($categories)
			foreach ($categories as $cat)
				if ( !in_array($cat->slug, $event_categories) )
					$event_categories[] = $cat->slug;
	endwhile; endif; wp_reset_postdata();
	
	// set event categories transients
	if ($event_categories) :
		foreach ($event_categories as $cat_slug)
			set_transient('MC-' . substr($cat_slug, 0, 35) . '-events-updated', time());
			
		// set event category terms 'last updated' transients
		if ( function_exists('BH_set_cached_event_category_terms') )
			BH_set_cached_event_category_terms();
		
		// set menu 'last updated' transients
		if ( function_exists('BH_set_cached_menus') )
			BH_set_cached_menus();
		
		// update relevant WP_Query transients
		if ( function_exists('BH_set_cached_wp_query') )
			BH_set_cached_wp_query('events', true);
	endif;

?>