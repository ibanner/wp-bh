<?php
/**
 * Single Product images
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$product_image_disclaimer = get_field( 'acf-options_product_image_disclaimer', 'option' );

$attachment_ids = array();
$attachment_ids = $product->get_gallery_attachment_ids();

if ( has_post_thumbnail() )
	array_unshift( $attachment_ids, get_post_thumbnail_id() );

if ($attachment_ids) {

	// store all attachments
	$attachments	= array();
	$sizes			= array( 'shop_thumbnail', 'shop_single', 'full' );
	$limit			= 5;

	for ($i=0, $total=count($attachment_ids) ; $total>$i && $i<$limit ; $i++) {
		$attachments[$i] = array();
		
		foreach ($sizes as $size) {
			$image = wp_get_attachment_image_src($attachment_ids[$i], $size);
			
			if ($image) {
				$src	= $image[0];
				$width	= $image[1];
				$height	= $image[2];
				
				$attachments[$i][$size] = array(
					'src'		=> $src,
					'width'		=> $width,
					'height'	=> $height,
					'img'		=> '<img ' . ($size == 'shop_single' ? 'itemprop="contentUrl"' : '') . ' src="' . $src . '" alt="' . $post->post_title . '-' . ($i+1) . '" width="' . $width . '" height="' . $height . '" />'
				);
			}
		}
	} ?>

	<?php // desktop gallery ?>
	<div class="visible-md visible-lg">

		<?php // gallery main item placeholder ?>
		<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject" id="gallery-main-item">
			<img id="gallery-main-item-img" src="<?php echo $attachments[0]['shop_single']['src']; ?>" data-zoom-image="<?php echo $attachments[0]['full']['src']; ?>" />
		</div>

		<?php // gallery thumbnails ?>
		<?php if ( count($attachments) > 1 ) { ?>

			<div id="gallery-navigator">

				<?php foreach ($attachments as $attachment) { ?>
					<a href="#" data-image="<?php echo $attachment['shop_single']['src']; ?>" data-zoom-image="<?php echo $attachment['full']['src']; ?>"><?php echo $attachment['shop_thumbnail']['img']; ?></a>
				<?php } ?>

			</div>

		<?php } ?>

	</div>

	<?php // mobile gallery ?>
	<div class="product-gallery-slider-wrapper visible-xs visible-sm">

		<div class="product-gallery-slider">		

			<div class="product-gallery-slider-placeholder">

				<div class="product-gallery-slider-carousel"
					data-cycle-slides=".product-gallery-item"
					data-cycle-fx=carousel
					data-cycle-timeout=0
					data-cycle-allow-wrap=true
					data-cycle-manual-trump=false
					data-cycle-carousel-visible=1
					data-cycle-swipe=true
					data-cycle-log=false
					data-cycle-prev="#product-gallery-slider-prev"
					data-cycle-next="#product-gallery-slider-next"
				>

					<?php foreach ($attachments as $attachment) { ?>

						<div class="product-gallery-item">
							<?php echo $attachment['shop_single']['img']; ?>
						</div>

					<?php } ?>

				</div>

				<div class="product-gallery-slider-prev" id="product-gallery-slider-prev"></div>
				<div class="product-gallery-slider-next" id="product-gallery-slider-next"></div>
				
			</div>

		</div>

	</div>

<?php } else { ?>

	<?php // product image placeholder ?>
	<div id="gallery-main-item">
		<?php echo wc_placeholder_img('shop_single'); ?>
	</div>

<?php }

echo ( $product_image_disclaimer ) ? '<small id="disclaimer">' . $product_image_disclaimer . '</small>' : '';