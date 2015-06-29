<?php

	/**
	 * Conditionally Override Yoast SEO Breadcrumb Trail
	 * http://plugins.svn.wordpress.org/wordpress-seo/trunk/frontend/class-breadcrumbs.php
	 */

	function wpseo_override_yoast_breadcrumb_trail( $links ) {
		global $post;
		
		$blog_page = get_field('acf-blog_page', 'option'); 
		
		if ( $blog_page && (is_home() || is_singular('post') || is_archive()) ) :
			$breadcrumb[] = array(
				'url' => get_permalink($blog_page->ID),
				'text' => $blog_page->post_title
			);
			
			array_splice($links, 1, -2, $breadcrumb);
		endif;
		
		return $links;
	}
	add_filter( 'wpseo_breadcrumb_links', 'wpseo_override_yoast_breadcrumb_trail' );

?>