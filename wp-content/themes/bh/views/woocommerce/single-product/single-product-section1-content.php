<?php
/**
 * Single Product content
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/single-product
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$badges		= wp_get_post_terms( $post->ID, 'badge', array( 'orderby' => 'term_order' ) );
$content	= get_the_content($post->ID);

if ( ! $badges && ! $content )
	return;

?>

<div class="single-product-meta-content product-meta-section">

	<?php // title ?>
	<div class="product-meta-section-title"><?php _e('Overview', 'BH'); ?></div>

	<?php // badges ?>
	<?php /* if ($badges) { ?>

		<ul class="badges clearfix">

			<?php foreach ($badges as $badge) {
				$name	= $badge->name;
				$image	= get_field( 'acf-product-badge_image', 'badge_' . $badge->term_id );
				$color	= get_field( 'acf-product-badge_color', 'badge_' . $badge->term_id );

				echo
					'<li' . ( $color ? ' style="background-color: ' . $color . '"' : '' ) . '>' .
						( $image ? '<div class="badge-image"><img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $name . '" /></div>' : '' ) .
						'<div class="badge-name">' . $name . '</div>' .
						'<div class="item-after"' . ( $color ? ' style="border-top-color: ' . $color . '"' : '' ) . '></div>' .
					'</li>';
			} ?>

		</ul>

	<?php } */ ?>

	<?php // content ?>
	<?php if ($content) { ?>

		<div class="content"><?php echo $content; ?></div>

	<?php } ?>

</div>