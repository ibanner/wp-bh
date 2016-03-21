<?php
/**
 * Footer
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<script>

	var js_globals = {};
	js_globals.template_url = "<?php echo TEMPLATE; ?>";

</script>

<?php
	
	get_template_part('views/footer/footer');
	
	wp_enqueue_script('bootstrap');
	wp_enqueue_script('countdown');
	wp_enqueue_script('general');
	wp_enqueue_script('ticketnet');
	
	if ( is_cf7_installed() && ! is_home() && ! is_front_page() ) :
		wp_enqueue_script('forms');
		wp_enqueue_script('state-handler');
		wp_enqueue_script('item-handler');
	endif;
		
	if ( is_page() ) :
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
	endif;
	
	if ( is_category() || is_singular('post') ) :
		wp_enqueue_script('blog');
	endif;
	
	if ( is_tax('event_category') ) :
		wp_enqueue_script('jquery-ui');
	endif;
	
	if ( is_tax('event_category') ) :
		wp_enqueue_script('event');
	endif;
	
	if ( is_product() ) :
		wp_enqueue_script('elevateZoom');
	endif;
	
	if ( is_singular('post') ) :
		get_template_part('views/blog/add-this');
	endif;
	
	if ( is_woocommerce() ) :
		wp_enqueue_script('cycle2');
		wp_enqueue_script('cycle2-carousel');
		wp_enqueue_script('cycle2-swipe');
		wp_enqueue_script('cycle2-ios6fix');
	endif;
	
	wp_footer();

?>

</body>
</html>