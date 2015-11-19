<?php
	global $product, $list, $ec_products;
	
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
	
	$ec_products[] = array(
		'sku'		=> $p_sku,
		'name'		=> $p_name,
		'list'		=> $p_list,
		'category'	=> $category,
		'price'		=> $p_price
	);
	
	$artists = BH_shop_get_artist_links($p_id);
?>

<div class="product-item">

	<div class="product-item-image">
		<a href="<?php echo $p_page; ?>" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product Image', '<?php echo $p_page; ?>'); return !ga.loaded;">
            <?php echo $product->get_image( 'shop_catalog', array( 'alt' => esc_attr( $product->get_title() ) ) ); ?>
		</a>
	</div>
	
	<div class="product-item-meta">
	
		<div class="title-wrapper">
			<div class="title">
				<a href="<?php echo $p_page; ?>" onclick="BH_EC_onProductClick('<?php echo $p_sku; ?>', '<?php echo $p_name; ?>', '<?php echo $category; ?>', '<?php echo $p_price; ?>', '<?php echo $p_currency; ?>', '<?php echo $p_list; ?>', 'Product Title', '<?php echo $p_page; ?>'); return !ga.loaded;">
					<?php echo $product->get_title(); ?>
				</a>
			</div>

			<?php echo ($artists) ? '<div class="artist">' . $artists . '</div>' : ''; ?>
		</div>

		<div class="add-to-cart-wrapper">
			<div class="price"><?php echo $product->get_price_html(); ?></div>

			<div class="add-to-cart">
				<?php

					/**
					 * woocommerce_after_shop_loop_item hook
					 *
					 * @hooked woocommerce_template_loop_add_to_cart - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' );

				?>
			</div>
		</div>

	</div>

</div>