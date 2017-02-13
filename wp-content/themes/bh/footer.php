<?php
/**
 * Footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get variables
$wpml_lang = function_exists('icl_object_id') ? ICL_LANGUAGE_CODE : '';

// Globals
global $globals;

?>

<script>

	var js_globals = {};
	js_globals.template_url	= "<?php echo TEMPLATE; ?>";
	js_globals.wpml_lang	= "<?php echo $wpml_lang; ?>";
	js_globals.ajaxurl		= "<?php echo $wpml_lang ? str_replace( "/$wpml_lang/", "/", admin_url( "admin-ajax.php" ) ) : admin_url( "admin-ajax.php" ); ?>";		// workaround for WPML bug

</script>

<?php
	
	get_template_part('views/footer/footer');

	if ( $globals['_gallery_layout_exist'] ) {
		get_template_part('views/footer/footer-photoswipe');
		wp_enqueue_style('photoswipe');
		wp_enqueue_style('photoswipe-default-skin');
		wp_enqueue_script('photoswipe');
		wp_enqueue_script('photoswipe-ui-default');
	}

	wp_enqueue_script('bootstrap');
	wp_enqueue_script('countdown');
	wp_enqueue_script('general');
	wp_enqueue_script('ticketnet');
	
	if ( is_cf7_installed() && ! is_home() && ! is_front_page() ) {
		wp_enqueue_script('forms');
		wp_enqueue_script('state-handler');
		wp_enqueue_script('item-handler');
	}
		
	if ( is_page() ) {
		$page_template = basename( get_page_template() );
		switch ($page_template) :
			case 'main.php' :
				wp_enqueue_script('cycle2');
				wp_enqueue_script('cycle2-carousel');
				wp_enqueue_script('cycle2-swipe');
				wp_enqueue_script('cycle2-ios6fix');
		        wp_enqueue_script('main');
				break;
			case 'event.php' :
			case 'past-events.php' :
				wp_enqueue_script('jquery-ui');
				wp_enqueue_script('event');
				break;
			case 'blog.php' :
				wp_enqueue_script('blog');
		endswitch;
	}
	
	if ( is_category() || is_singular('post') ) {
		wp_enqueue_script('blog');
	}
	
	if ( is_tax('event_category') ) {
		wp_enqueue_script('jquery-ui');
	}
	
	if ( is_tax('event_category') ) {
		wp_enqueue_script('event');
	}
	
	if ( is_product() ) {
		wp_enqueue_script('elevateZoom');
	}
	
	if ( is_singular('post') ) {
		get_template_part('views/blog/add-this');
	}
	
	if ( is_woocommerce() ) {
		wp_enqueue_script('cycle2');
		wp_enqueue_script('cycle2-carousel');
		wp_enqueue_script('cycle2-swipe');
		wp_enqueue_script('cycle2-ios6fix');
	}
	
	wp_footer();

?>

</body>
</html>