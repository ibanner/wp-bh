<?php
	global $product;
	
	$artists = BH_shop_get_artist_links($product->id);
?>

<div class="product-item">

	<div class="product-item-image">
		<a href="<?php echo esc_url( get_permalink($product->id) ); ?>">
			<?php echo $product->get_image( 'shop_catalog', array( 'alt' => $product->get_title() ) ); ?>
		</a>
	</div>
	
	<div class="product-item-meta">
	
		<div class="title">
			<a href="<?php echo esc_url( get_permalink($product->id) ); ?>">
				<?php echo get_the_title($product->id); ?>
			</a>
		</div>
		
		<?php echo ($artists) ? '<div class="artist-title">' . ( (ICL_LANGUAGE_CODE == 'en') ? '<span>' . __('By ', 'BH') . '</span>' : '' ) . $artists . '</div>' : ''; ?>
		<?php woocommerce_template_single_price(); ?>
		
	</div>

</div>