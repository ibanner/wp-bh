<?php

	// load scripts and styles on the dashboard
	function BH_admin_head() {
		wp_enqueue_style('admin-general');
	}
	add_action('admin_head', 'BH_admin_head');
	
	// tweak the login screen
	function BH_login_screen() {
		wp_enqueue_style('admin-login');
	}
	add_action('login_head', 'BH_login_screen');
	
	function BH_login_logo_url() {
		return HOME;
	}
	add_filter('login_headerurl', 'BH_login_logo_url');
	
	function BH_login_logo_url_title() {
		return get_bloginfo('name');
	}
	add_filter('login_headertitle', 'BH_login_logo_url_title' );

?>