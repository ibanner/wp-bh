<?php
	global $product;
	
	$p_id		= $product->id;
	$p_sku		= esc_js( $product->sku );
	$p_name		= esc_js( $product->get_title() );
	$p_price	= number_format((float)$product->price, 2, '.', '');
	$p_currency	= get_woocommerce_currency();
	$p_list		= 'Recently Viewed';
	$p_page		= esc_url( get_permalink($p_id) );
	
	$category = '';
	$product_cats = wp_get_post_terms($p_id, 'product_cat');
	if ( $product_cats && ! is_wp_error ($product_cats) ) :
		$single_cat	= array_shift($product_cats);
		$category	= esc_js( $single_cat->name );
	endif;
?>

<li>
	<a data-postid="<?php echo $product->id; ?>" href="#" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product Image', '<?php echo $p_page; ?>'); return !ga.loaded;">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo $product->get_image( 'shop_thumbnail', array( 'alt' => esc_attr( $product->get_title() ) ) ); ?>
	</a>
</li>