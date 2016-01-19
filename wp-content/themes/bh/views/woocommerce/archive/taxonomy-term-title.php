<?php
/**
 * The Template for displaying product taxonomy term title
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;

$tt = $wp_query->get_queried_object();

if ( ! $tt )
	return;

$term_icon = get_field('acf-product-category-shop_homepage_menu_icon', $tt->taxonomy . '_' . $tt->term_id); ?>

<div class="term-title <?php echo $term_icon ? 'has-icon' : ''; ?>">
	<?php echo $term_icon ? '<div class="icon" style="background-image: url(\'' . $term_icon . '\');"></div>' : ''; ?>
	<h1><?php echo $tt->name; ?></h1>
</div>