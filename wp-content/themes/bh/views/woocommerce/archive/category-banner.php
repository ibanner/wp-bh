<?php
/**
 * The Template for displaying product category banner
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
	
$term_id		= $tt->term_id;

$thumbnail_id	= get_woocommerce_term_meta($term_id, 'thumbnail_id', true);
$term_image		= ($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : '';

$term_desc		= $tt->description;

if ($term_desc) { ?>

	<div class="row term-banner">
		<?php if ($term_image) { ?>
			<img class="term-image" src="<?php echo $term_image; ?>" alt="<?php echo $tt->name; ?>" />
		<?php } ?>
		
		<div class="term-description">
			<?php echo $term_desc; ?>
		</div>
	</div>

<?php }