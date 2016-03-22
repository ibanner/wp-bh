<?php
/**
 * Single Product excerpt
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post; ?>

<div itemprop="description" class="single-product-meta-description product-meta-section">

	<div class="excerpt">
		<?php echo apply_filters('woocommerce_short_description', $post->post_excerpt); ?>
	</div>
	
</div>