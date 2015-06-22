<?php global $product; ?>
<li>
	<a data-postid="<?php echo $product->id; ?>" href="<?php echo esc_url( get_permalink($product->id) ); ?>">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo $product->get_image( 'shop_thumbnail', array( 'alt' => $product->get_title() ) ); ?>
	</a>
</li>