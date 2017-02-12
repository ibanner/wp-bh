<?php
/**
 * The Template for displaying gallery
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/page/content
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$images = get_sub_field('images');

global $gallery_exist;
$gallery_exist = false;

if ( $images ) { ?>

	<div class="gallery-layout-content">

		<div class="gallery row" itemscope itemtype="http://schema.org/ImageGallery">

			<?php 
				$gallery_images = array();

				foreach ($images as $i) {
					$image = array(
						'title'		=> esc_js( $i['title'] ),
						'alt'		=> esc_js( $i['alt'] ),
						'caption'	=> esc_js( $i['caption'] ),
						'url'		=> esc_js( $i['url'] )
					);

					$gallery_images[] = $image;
				}

				if ( $gallery_images ) {
					$i = 0;
					while ( $i <= 2 ) { ?>

						<div class="gallery-col col<?php echo $i++; ?> col-xs-4"></div>

					<?php }
				}
			?>

		</div>

		<?php if ( $gallery_images ) { ?>

			<button class="btn load-more inline-btn cyan-btn big"><?php _e('Load more', 'BH'); ?></button>

			<script>
				_BH_gallery_images = '<?php echo json_encode( $gallery_images ); ?>';
			</script>

			<?php $gallery_exist = true;

			wp_enqueue_style('photoswipe');
			wp_enqueue_style('photoswipe-default-skin');

		} ?>

	</div>

<?php }