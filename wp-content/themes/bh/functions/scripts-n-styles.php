<?php

add_action( 'login_enqueue_scripts',	'BH_login_scripts_n_styles' );
add_action( 'admin_enqueue_scripts',	'BH_admin_scripts_n_styles' );
add_action( 'wp_enqueue_scripts',		'BH_wp_scripts_n_styles' );

add_filter( 'mce_css', 'BH_editor_style' );

/**
 * BH_login_scripts_n_styles
 * 
 * used before authentication
 */
function BH_login_scripts_n_styles() {
	wp_register_style( 'admin-login',	CSS_DIR . '/admin/login.css',	array(),	VERSION );
}

/**
 * BH_admin_scripts_n_styles
 */
function BH_admin_scripts_n_styles() {
	wp_register_style( 'admin-general',	CSS_DIR . '/admin/general.css',	array(),	VERSION );
}

/**
 * BH_wp_scripts_n_styles
 */
function BH_wp_scripts_n_styles() {
	/**
	 * styles
	 */
	wp_enqueue_style ( 'bootstrap',					CSS_DIR . '/libs/bootstrap.min.css',				array(),													VERSION );
	wp_register_style( 'bootstrap-rtl',				CSS_DIR . '/libs/bootstrap-rtl.min.css',			array('bootstrap'),											VERSION );
	//wp_register_style( 'fancybox',				CSS_DIR . '/libs/fancybox.css',						array('bootstrap'),											VERSION );
	wp_register_style( 'jquery-ui',					CSS_DIR . '/libs/jquery-ui.css',					array('bootstrap'),											VERSION );
	wp_enqueue_style ( 'font-awesome',		 		CSS_DIR . '/libs/font-awesome.min.css',				array(),													VERSION );
	
	wp_enqueue_style ( 'general',					CSS_DIR . '/general.css',							array('bootstrap'),											VERSION );
	wp_register_style( 'main',						CSS_DIR . '/main.css',								array('bootstrap'),											VERSION );
	wp_register_style( 'event',						CSS_DIR . '/event.css',								array('bootstrap'),											VERSION );
	wp_register_style( 'blog',						CSS_DIR . '/blog.css',								array('bootstrap'),											VERSION );
	wp_register_style( 'rtl',						CSS_DIR . '/rtl.css',								array('bootstrap-rtl'),										VERSION );
	
	if ( is_page() ) :
		$page_template = basename( get_page_template() );
		switch ($page_template) :
			case 'main.php' :
				wp_enqueue_style('main');
				break;
			case 'event.php' :
			case 'past-events.php' :
				wp_enqueue_style('jquery-ui');
				wp_enqueue_style('event');
				break;
			case 'blog.php' :
				wp_enqueue_style('blog');
				break;
		endswitch;
	endif;
	
	if ( is_category() || is_date() || is_singular('post') ) :
		wp_enqueue_style('blog');
	endif;
	
	if ( is_post_type_archive('product') || is_tax( get_object_taxonomies('product') ) || is_tax('event_category') ) :
		wp_enqueue_style('jquery-ui');
	endif;
	
	if ( is_tax('event_category') || is_singular('event') ) :
		wp_enqueue_style('event');
	endif;
	
	if (is_rtl()) :
		wp_enqueue_style('bootstrap-rtl');
		wp_enqueue_style('rtl');
	endif;
	
	/**
	 * scripts
	 */
//	wp_deregister_script('jquery');
//	wp_enqueue_script ( 'jquery',					'http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',	array(),													'2.1.3',	false );
	wp_register_script( 'bootstrap',				JS_DIR . '/libs/bootstrap.min.js',									array('jquery'),											VERSION,	true );
	wp_register_script( 'matchMedia',				JS_DIR . '/libs/matchMedia.js',										array('jquery'),											VERSION,	true );
	wp_register_script( 'matchMediaListener',		JS_DIR . '/libs/matchMedia.addListener.js',							array('jquery'),											VERSION,	true );
	//wp_register_script( 'fancybox',				JS_DIR . '/libs/fancybox.js',										array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'countdown',				JS_DIR . '/libs/jquery.responsive_countdown.min.js',				array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'cycle2',					JS_DIR . '/libs/jquery.cycle2.min.js',								array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'cycle2-carousel',			JS_DIR . '/libs/jquery.cycle2.carousel.min.js',						array('cycle2'),											VERSION,	true );
	wp_register_script( 'cycle2-swipe',				JS_DIR . '/libs/jquery.cycle2.swipe.min.js',						array('cycle2'),											VERSION,	true );
	wp_register_script( 'cycle2-ios6fix',			JS_DIR . '/libs/ios6fix.js',										array('cycle2'),											VERSION,	true );
	wp_register_script( 'jquery-ui',				JS_DIR . '/libs/jquery-ui.min.js',									array('jquery', 'bootstrap'),								VERSION,	true );
	//wp_register_script( 'jquery-ui-touch',		JS_DIR . '/libs/jquery.ui.touch-punch.min.js',						array('jquery-ui'),											VERSION,	true );
	wp_register_script( 'angular',					JS_DIR . '/libs/angular.min.js',									array('jquery'),						                    VERSION,	true );
	wp_register_script( 'rcSubmit',					JS_DIR . '/libs/rcSubmit.js',									    array('angular'),						                    VERSION,	true );
	wp_register_script( 'ticketnet',				JS_DIR . '/libs/ticketnet.min.js',									array('jquery'),											VERSION,	true );
	wp_register_script( 'elevateZoom',				JS_DIR . '/libs/jquery.elevateZoom-3.0.8.min.js',					array('jquery'),											VERSION,	true );
	
	wp_register_script( 'general',					JS_DIR . '/min/general.min.js',										array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'main',						JS_DIR . '/min/main.min.js',										array('cycle2-carousel', 'cycle2-swipe', 'cycle2-ios6fix'),	VERSION,	true );
	wp_register_script( 'bh-analytics',				JS_DIR . '/bh-analytics.js',										array('jquery'),											VERSION,	true );
	wp_register_script( 'bh-fb-pixel',				JS_DIR . '/bh-fb-pixel.js',											array(),													VERSION,	true );
	wp_register_script( 'banner',					JS_DIR . '/min/bh-slideshow.min.js',								array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'event',					JS_DIR . '/min/event.min.js',										array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'blog',						JS_DIR . '/min/blog.min.js',										array('jquery', 'bootstrap'),								VERSION,	true );
	wp_register_script( 'forms',					JS_DIR . '/forms/forms.js',											array('jquery'),						                    VERSION,	true );       
	wp_register_script( 'microfilm',				JS_DIR . '/forms/microfilm.js',										array('jquery'),						                    VERSION,	true );       
	wp_register_script( 'item-handler',				JS_DIR . '/forms/item-handler.js',									array('jquery'),						                    VERSION,	true );        
	wp_register_script( 'state-handler',			JS_DIR . '/forms/state-handler.js',									array('jquery'),						                    VERSION,	true );
	
	// google analytics script
	wp_enqueue_script('bh-analytics');
	// facebook pixel script
	wp_enqueue_script('bh-fb-pixel');
}

// tinyMCE styles
function BH_editor_style( $styles ) {
	$styles .= ', ' . CSS_DIR . '/admin/' . 'editor.css';
	
	// Google Fonts
	global $google_fonts;
	
	if ($google_fonts) : foreach ($google_fonts as $key => $val) :
		$font = str_replace( ',', '&#44', $val );
		$styles .= ', ' . $font;
	endforeach; endif;
	
	return $styles;
}

?>