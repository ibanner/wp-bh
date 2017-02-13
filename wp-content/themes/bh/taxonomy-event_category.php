<?php
/**
 * The Template for displaying event category page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

<?php
	/**
	 * Variables definition
	 * 
	 * @global	$category_id
	 */

	global $category_id;
	$category_id = get_queried_object_id();

?>

<?php
	/**
	 * BH_before_main_content hook
	 *
	 * @hooked BH_theme_wrapper_start - 10 (outputs opening section and divs for the content)
	 */
	do_action('BH_before_main_content');
?>

<?php
	get_template_part('views/components/side-menu');
	
	echo '<div class="col-lg-10 content-wrapper-wide">';
		get_template_part('views/event/events');
	echo '</div>';
?>

<?php
	/**
	 * BH_after_main_content hook
	 *
	 * @hooked BH_theme_wrapper_end - 10 (outputs closing section and divs for the content)
	 */
	do_action('BH_after_main_content');
?>

<?php get_footer(); ?>