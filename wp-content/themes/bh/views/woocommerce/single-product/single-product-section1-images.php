<?php
/**
 * Single Product images
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$product_image_disclaimer = get_field( 'acf-options_product_image_disclaimer', 'option' );

$attachment_ids = array();
$attachment_ids = $product->get_gallery_attachment_ids();

if ( has_post_thumbnail() )
	array_unshift( $attachment_ids, get_post_thumbnail_id() );
	
if ($attachment_ids) :

	// store all attachments
	$attachments	= array();
	$sizes			= array( 'shop_thumbnail', 'shop_single', 'full' );
	$limit			= 5;
	
	for ($i=0, $total=count($attachment_ids) ; $total>$i && $i<$limit ; $i++) :
		$attachments[$i] = array();
		
		foreach ($sizes as $size) :
			$image = wp_get_attachment_image_src($attachment_ids[$i], $size);
			
			if ($image) :
				$src	= $image[0];
				$width	= $image[1];
				$height	= $image[2];
				
				$attachments[$i][$size] = array(
					'src'		=> $src,
					'width'		=> $width,
					'height'	=> $height,
					'img'		=> '<img itemprop="contentUrl" src="' . $src . '" alt="' . $post->post_title . '-' . ($i+1) . '" width="' . $width . '" height="' . $height . '" />'
				);
			endif;
		endforeach;
	endfor;
	
	// gallery main item placeholder
	echo '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject" id="gallery-main-item" data-image="' . $attachments[0]['shop_single']['src'] . '" data-zoom-image="' . $attachments[0]['full']['src'] . '">';
		echo $attachments[0]['shop_single']['img'];
	echo '</div>';
	
	// gallery thumbnails
	if ( count($attachments) > 1 ) :
	
		echo '<ul id="gallery-navigator">';
		
			$i = 0;
			foreach ($attachments as $attachment) :
				echo '<li' . ( ($i == 0) ? ' class="active"' : '' ) . ' data-thumbnail="' . $i . '">' . $attachment['shop_thumbnail']['img'] . '</li>';
				$i++;
			endforeach;
			
		echo '</ul>';
		
	endif;
	
	// save $attachments in JSON format for later use ?>
	<script>
		var _BH_product_attachments = <?php echo json_encode($attachments); ?>;
	</script>
	
<?php else :

	// product image placeholder
	echo '<div id="gallery-main-item">';
		echo wc_placeholder_img('shop_single');
	echo '</div>';

endif;

echo ( $product_image_disclaimer ) ? '<small id="disclaimer">' . $product_image_disclaimer . '</small>' : '';