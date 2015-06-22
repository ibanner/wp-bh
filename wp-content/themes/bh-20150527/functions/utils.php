<?php

	/**
	 * remove more jump link
	 */
	
	function BH_remove_more_jump_link($link) { 
		$offset = strpos($link, '#more-');
		if ($offset) {
			$end = strpos($link, '"',$offset);
		}
	
		if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
		}
	
		return $link;
	}
	add_filter('the_content_more_link', 'BH_remove_more_jump_link');

	/**
	 * excerpt more link
	 */
	
	function BH_excerpt_more_link($more) {
		global $post;
		
		$read_more_btn = get_field('acf-options_event_btn_read_more_' . ICL_LANGUAGE_CODE, 'option');
		
		//return '...<div class="read-more"><a class="btn inline-btn red-btn big" href="' . get_permalink($post->ID) . '">' . $read_more_btn . '</a></div>';
		return ' [...]';
	}
	add_filter('excerpt_more', 'BH_excerpt_more_link');
	
	/**
	 * menu based breadcrumbs
	 */
	
	function get_menu_root_navigation( $id, $show_child_link = null ) {
		$ancestors = array();
		$locations = get_nav_menu_locations();
		$bc = '';
		
		foreach ( $locations as $menu_name => $menu_id ) :
			if ( isset($locations[$menu_name]) ) :
				$menu = wp_get_nav_menu_object( $menu_id );
				$menu_items = wp_get_nav_menu_items( $menu->term_id );
				
				// find current item key
				foreach ( $menu_items as $key => $item ) :
					if ( $item->object_id == $id ) {
						$current_item_key = $key;
						$ancestors[] = $key;
						break;
					}
				endforeach;
				
				if ( isset($current_item_key) ) :
					// build ancestor tree
					while ( $current_item_key && $menu_items[$current_item_key]->menu_item_parent ) {
						$parent_item_id = $menu_items[$current_item_key]->menu_item_parent;
						if ( $parent_item_id ) :
							// find parent key
							foreach ( $menu_items as $key => $item ) :
								if ( $item->ID == $parent_item_id ) {
									$current_item_key = $key;
									$ancestors[] = $key;
									break;
								}
							endforeach;
						else :
							$current_item_key = 0;
						endif;
					}
					
					if ( count($ancestors)>0 ) :
						// print breadcrumb navigation
						$bc = '<a href="' . get_bloginfo('siteurl') . '">' . __('Home', 'BH') . '</a> &raquo; ';
						
						for( $i=count($ancestors) ; $i>1 ; $i--)
							$bc .= '<a href="' . $menu_items[$ancestors[$i-1]]->url . '">' . $menu_items[$ancestors[$i-1]]->title . '</a> &raquo; ';
		
						if ( $show_child_link )
							$bc .= '<a href="' . $menu_items[$ancestors[0]]->url . '">' . $menu_items[$ancestors[0]]->title . '</a> &raquo; ';
						else
							$bc .= $menu_items[$ancestors[0]]->title;
					endif;
				endif;					
			endif;
			
			if ( count($ancestors)>0 )
				break;
		endforeach;
	
		return $bc;
	}
	
	/**
	 * archive posts per page
	 */
	
	function archive_posts_per_page($query) {
		if ( is_admin() || ! $query->is_main_query() )
			return;
			
		if ( is_archive() ) {
			// Display unlimited posts for archive pages
			$query->set('posts_per_page', -1);
			return;
		}
	}
	add_action('pre_get_posts', 'archive_posts_per_page', 1);
	
	/**
	 * separate media categories from post categories
	 * use a custom category called 'category_media' for the categories in the media library
	 */
	add_filter( 'wpmediacategory_taxonomy', function(){ return 'category_media'; }, 1 ); //requires PHP 5.3 or newer
	
	/**
	 * BH_strip_tags_content
	 * 
	 * Strip tags from HTML string
	 * 
	 * Examples:
	 * Sample text:
	 * $text = '<b>sample</b> text with <div>tags</div>';
	 * 
	 * Result for strip_tags_content($text, '<b>'):
	 * <b>sample</b> text with
	 * 
	 * Result for strip_tags_content($text, '<b>', TRUE);
	 * text with <div>tags</div>
	 * 
	 * @param	string	$text		HTML string
	 * @param	string	$tags		HTML tags to include/exclude
	 * @param	bool	$invert		include (false) / exclude (true)
	 */
	function BH_strip_tags_content($text, $tags = '', $invert = FALSE) {
		preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		$tags = array_unique($tags[1]);
		
		if(is_array($tags) AND count($tags) > 0) {
			if($invert == FALSE) {
				return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
			}
			else {
				return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
			}
		}
		elseif($invert == FALSE) {
			return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
		}
		
		return $text;
	}
	
	/**
	 * BH_mejskin_enqueue_styles
	 * 
	 * Custom MediaLayer skin
	 */
	function BH_mejskin_enqueue_styles() {
		$library = apply_filters('wp_audio_shortcode_library', 'mediaelement');
		
		if ( 'mediaelement' === $library && did_action('init') ) {
			wp_enqueue_style( 'mejskin', CSS_DIR . '/libs/mediaelement/skin/mediaelementplayer.css', false, null );
		}
	}
	add_action('wp_footer', 'BH_mejskin_enqueue_styles', 11);
	
?>