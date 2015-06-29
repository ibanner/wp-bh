<?php

	$events_page = get_field('acf-options_events_page', 'option');
	$events_page_id = $events_page ? $events_page->ID : '';
	
	if ( (is_tax('event_category') || is_singular('event')) && $events_page_id ) :
		$object_id = $events_page_id;
	else :
		$object_id = get_queried_object_id();
	endif;
	
	// get top menu parent
	$parent = BH_get_top_menu_item($object_id, 'main-menu');
	
	echo '<div class="col-lg-2 visible-lg side-menu-wrapper">';
		echo '<nav>';
			echo '<ul>';
			
				if ($parent) :
				
					// top parent page item
					echo '<li class="parent">';
						echo ($parent['url']) ? '<a href="' . $parent['url'] . '">' : '';
							echo $parent['title'];
						echo ($parent['url']) ? '</a>' : '';
					echo '</li>';
					
					// display event categories in case of parent page is based on event.php page template
					if ( get_page_template_slug($parent['object_id']) == 'page-templates/event.php' ) :
						echo BH_get_event_categories_menu(get_queried_object_id(), true);
					endif;
					
					$args = array(
						'theme_location'	=> 'main-menu',
						'container'			=> false,
						'items_wrap'		=> '%3$s',
						'children_of'		=> $parent['ID']
					);
					wp_nav_menu($args);
					
				else :
				
					echo '<li class="parent">' . get_the_title($post->ID) . '</li>';
					
				endif;
		
			echo '</ul>';		
		echo '</nav>';
		
		// display newsletter widget in case of parent page is based on event.php page template
		if ( $parent && get_page_template_slug($parent['object_id']) == 'page-templates/event.php' ) :
			get_template_part('views/sidebar/sidebar', 'newsletter');
		endif;
	echo '</div>';

?>