<?php
/**
 * The Template for displaying product category banner
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;

$tt = $wp_query->get_queried_object();

if ( ! $tt )
	return;
	
$term_id		= $tt->term_id;

$thumbnail_id	= get_woocommerce_term_meta($term_id, 'thumbnail_id', true);
$banner_image	= ($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : '';

$banner_title	= get_field('acf-product-category-banner_title', $tt->taxonomy . '_' . $term_id);
$banner_text	= get_field('acf-product-category-banner_text', $tt->taxonomy . '_' . $term_id);

if ($banner_image) { ?>

	<figure class="category-banner">
		<img src="<?php echo $banner_image; ?>" alt="<?php echo $tt->name; ?>" />
		
		<figcaption>
			<?php
				echo ($banner_title) ? '<h1>' . $banner_title . '</h1>' : '';
				echo ($banner_text) ? '<div class="category-banner-caption">' . $banner_text . '</div>' : '';
			?>
		</figcaption>
	</figure>
	
<?php }