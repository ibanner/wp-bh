<?php

	// get blog page id
	$blog_page = get_field('acf-options_blog_page', 'option');
	$blog_page_id = $blog_page ? $blog_page->ID : '';
	
	// get current object id
	$object_id = get_queried_object_id();
	
	// get current post categories
	if ( is_singular('post') ) {
		$post_categories = wp_get_post_terms( $object_id, 'category', array('fields' => 'ids') );
	}
	
	// get categories
	$args = array(
		'orderby'		=> 'term_order'
	);
	
	if ( function_exists('BH_get_cached_terms') )
		$categories = BH_get_cached_terms('category', $args);
	else
		$categories = get_terms('category', $args);
	
	// get recent posts
	$args = array(
		'numberposts'		=> 15,
		'post_status'		=> 'publish',
		'suppress_filters'	=> 0
	);
	$recent_posts = wp_get_recent_posts($args);
	
	// get archives
	$args = array(
		'format'	=> 'custom',
		'after'		=> '|',
		'echo'		=> 0
	);
	$archives = wp_get_archives($args);
	
	// build archive list
	if ($archives) :
	
		$archives = explode('|', $archives);
		array_pop($archives);
		
		$archives_output = '<ul class="blog-menu">';
		
		if ( is_month() ) :
			// set string pattern
			if ( get_option('permalink_structure') != '' ) :
				$pattern = '#/(\d\d\d\d)/(\d\d)#';
			else :
				$pattern = '/m=(\d\d\d\d)(\d\d)/';
			endif;
			
			// get current archive month
			preg_match($pattern, $_SERVER['REQUEST_URI'], $matches);
			$current_year	= $matches[1];
			$current_month	= $matches[2];
			
			foreach ($archives as $link) :
				if ( preg_match($pattern, $link, $matches) ) :
					$year	= $matches[1];
					$month	= $matches[2];
					
					$archives_output .= '<li' . ( ($current_year == $year && $current_month == $month) ? ' class="current-menu-item"' : '' ) . '>' . $link . '</li>';
				endif;
			endforeach;
		else :
			foreach ($archives as $link)
				$archives_output .= '<li>' . $link . '</li>';
		endif;
		
		$archives_output .= '</ul>';
		
	endif;
	
	echo '<div class="col-lg-2 visible-lg side-menu-wrapper">';
		echo '<nav>';
			
			// blog page
			echo '<ul>';
				echo '<li class="parent">';
					echo '<a href="' . get_permalink($blog_page_id) . '">' . get_the_title($blog_page_id) . '</a>';
				echo '</li>';
			echo '</ul>';
			
			// categories
			if ($categories) :
				echo '<h3>' . __('Categories', 'BH') . '</h3>';
				
				echo '<ul class="blog-menu">';
					foreach ($categories as $cat) :
						$current = ( ($object_id == $cat->term_id) || (is_singular('post') && in_array($cat->term_id, $post_categories)) ) ? true : false;
						echo '<li' . ( ($current) ? ' class="current-menu-item"' : '' ) . '><a href="' . get_term_link($cat) . '">' . $cat->name . '</a></li>';
					endforeach;
				echo '</ul>';
			endif;
					
			// recent posts
			if ($recent_posts) :
				echo '<h3>' . __('Recent Posts', 'BH') . '</h3>';
				
				echo '<ul class="blog-menu">';
					foreach ($recent_posts as $recent) :
						echo '<li' . ( ($object_id == $recent['ID']) ? ' class="current-menu-item"' : '' ) . '><a href="' . get_permalink($recent['ID']) . '">' . $recent['post_title'] . '</a></li>';
					endforeach;
				echo '</ul>';
			endif;
					
			// archives
			if ($archives_output) :
				echo '<h3>' . __('Archives', 'BH') . '</h3>';
				
				echo $archives_output;
			endif;
			
		echo '</nav>';
		
		// display newsletter widget
		get_template_part('views/sidebar/sidebar', 'newsletter');
	echo '</div>';

?>