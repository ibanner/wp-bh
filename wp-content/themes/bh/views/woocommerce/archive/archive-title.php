<?php
/**
 * The Template for displaying archive title (product taxonomy term / search results query string)
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_tax() ) {

	// custom taxonomy archive
	global $wp_query;

	$tt = $wp_query->get_queried_object();

	if ( ! $tt )
		return;

	$term_icon = get_field('acf-product-category-shop_homepage_menu_icon', $tt->taxonomy . '_' . $tt->term_id); ?>

	<div class="term-title <?php echo $term_icon ? 'has-icon' : ''; ?>">
		<?php echo $term_icon ? '<div class="icon" style="background-image: url(\'' . $term_icon . '\');"></div>' : ''; ?>
		<h1><?php echo $tt->name; ?></h1>
	</div>

<?php }

elseif ( is_search() ) {

	// search ?>
	<div class="term-title">
		<h1><?php echo __('Search Results', 'BH') . ': <span>' . get_search_query() . '</span>' ?></h1>
	</div>

<?php }