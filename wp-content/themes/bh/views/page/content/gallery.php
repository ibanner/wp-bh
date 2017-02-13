<?php
/**
 * The Template for displaying gallery
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/page/content
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get variables
$images = get_sub_field('images');

// Globals
global $globals;

if ( $images ) { ?>

	<div class="gallery-layout-content">

		<div class="gallery row" itemscope itemtype="http://schema.org/ImageGallery">

			<?php 
				$gallery_images = array();

				function fix_str($string_to_fix) {
					return str_replace(array("\r\n", "\n", "\r"), array("\\r\\n", "\\n", "\\r"), $string_to_fix);
				};

				foreach ($images as $i) {
					$image = array(
						'title'		=> esc_attr( fix_str( $i['title'] ) ),
						'alt'		=> esc_attr( fix_str( $i['alt'] ) ),
						'caption'	=> esc_attr( fix_str( $i['caption'] ) ),
						'url'		=> esc_attr( fix_str( $i['url'] ) )
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

			<?php $globals['_gallery_layout_exist'] = true; ?>

		<?php } ?>

	</div>

<?php }