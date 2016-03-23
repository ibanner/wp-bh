<?php
	global $list, $ec_products;

	$p_id			= $product->id;
	$p_sku			= esc_js( $product->sku );
	$p_name			= esc_js( $product->get_title() );
	$p_price		= number_format((float)$product->price, 2, '.', '');
	$p_currency		= get_woocommerce_currency();
	$p_list			= esc_js( $list );
	$p_page			= esc_url( get_permalink($p_id) );
	
	$category = '';
	$product_cats = wp_get_post_terms($p_id, 'product_cat');
	if ( $product_cats && ! is_wp_error ($product_cats) ) :
		$single_cat	= array_shift($product_cats);
		$category	= esc_js( $single_cat->name );
	endif;

	$attachment_ids = array();
	$attachment_ids = $product->get_gallery_attachment_ids();

	if ( has_post_thumbnail() )
		array_unshift( $attachment_ids, get_post_thumbnail_id($p_id) );

	$image			= ($attachment_ids)				? wp_get_attachment_image_src($attachment_ids[0], 'shop_catalog') : '';
	$image_hover	= (count($attachment_ids) > 1)	? wp_get_attachment_image_src($attachment_ids[1], 'shop_catalog') : '';

	$ec_products[] = array(
		'sku'		=> $p_sku,
		'name'		=> $p_name,
		'list'		=> $p_list,
		'category'	=> $category,
		'price'		=> $p_price
	);
?>

<div class="featured-product-item">

	<div class="product-item-image">
		<a href="<?php echo $p_page; ?>" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product Image', '<?php echo $p_page; ?>'); return !ga.loaded;">
			<div class="image">
				<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>" />
			</div>

			<?php if ($image_hover) : ?>
				<div class="image-hover" style="display: none;">
					<img src="<?php echo $image_hover[0]; ?>" width="<?php echo $image_hover[1]; ?>" height="<?php echo $image_hover[2]; ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>" />
				</div>
			<?php endif; ?>
		</a>
	</div>
	
	<div class="product-item-meta-wrapper">
		<div class="product-item-meta">

			<div class="title">
				<a href="<?php echo $p_page; ?>" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product Title', '<?php echo $p_page; ?>'); return !ga.loaded;">
					<?php echo $product->get_title(); ?>
				</a>
			</div>
			
			<div class="price"><?php echo $product->get_price_html(); ?></div>
			
			<div class="more-info">
				<a href="<?php echo $p_page; ?>" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product More Info', '<?php echo $p_page; ?>'); return !ga.loaded;">
					<?php _e('Click for more details', 'BH'); ?>
				</a>
			</div>

		</div>
	</div>

</div>