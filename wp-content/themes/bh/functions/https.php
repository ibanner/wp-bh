<?php

	/**
	 * gae_template_redirect
	 * 
	 * Redirect GAE url to domain url
	 */
	function gae_template_redirect() {
		$gae_domain	= 'appspot.com';
		$domain		= 'http://www.bh.org.il';
		$pos		= strpos($_SERVER['HTTP_HOST'], $gae_domain);
		
		if ($pos) {
			wp_redirect($domain . $_SERVER['REQUEST_URI'], 301);
			exit();
		}
	}
	//add_action('template_redirect', 'gae_template_redirect', 1);

	/**
	 * ssl_template_redirect
	 * 
	 * SSL redirection
	 */
	function ssl_template_redirect() {
		if ( ! is_page() )
			return;
		
		global $post;
		$https = get_field('acf-https', $post->ID);
		
		if ( $https && ! is_ssl() ) {
			if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
				wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']), 301);
				exit();
			} else {
				wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
				exit();
			}
		} else if ( !$https && is_ssl() && !is_admin() ) {
			if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
				wp_redirect(preg_replace('|^https://|', 'http://', $_SERVER['REQUEST_URI']), 301);
				exit();
			} else {
				wp_redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
				exit();
			}
		}
	}
	add_action('template_redirect', 'ssl_template_redirect', 2);
	
	function ssl_page( $permalink, $post, $leavename ) {
		$https = get_field('acf-https', $post->ID);
		
		if ($https)
			return preg_replace( '|^http://|', 'https://', $permalink );
			
		return $permalink;
	}
	add_filter('pre_post_link', 'ssl_page', 10, 3);

?>