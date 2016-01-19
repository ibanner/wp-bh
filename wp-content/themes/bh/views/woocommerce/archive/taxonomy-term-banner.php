<?php
/**
 * The Template for displaying product taxonomy term banner
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
	
$thumbnail_id	= get_woocommerce_term_meta($tt->term_id, 'thumbnail_id', true);
$term_image		= ($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : '';

$term_desc		= $tt->description;
$term_icon		= get_field('acf-product-category-shop_homepage_menu_icon', $tt->taxonomy . '_' . $tt->term_id);

if ($term_desc) { ?>

	<div class="term-banner-wrapper <?php echo $term_icon ? 'has-icon' : ''; ?>">
		<div class="term-banner">
			<?php if ($term_image) { ?>
				<img class="term-image" src="<?php echo $term_image; ?>" alt="<?php echo $tt->name; ?>" />
			<?php } ?>
			
			<div class="term-description">
				<?php echo apply_filters('the_content', $term_desc); ?>
			</div>
		</div>
	</div>

<?php }