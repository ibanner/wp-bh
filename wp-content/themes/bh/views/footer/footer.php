<?php
/**
 * Footer view
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/footer
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<footer>

	<?php // Footer links
		get_template_part('views/footer/footer-links');
	?>

	<div class="container">
		<div class="row search-form-wrapper">
			<div class="col-sm-4 col-sm-push-4">
				<?php //Search form
					get_search_form();
				?>
			</div>
		</div>
	</div>

	<?php // Social links
		get_template_part('views/footer/social-links');
	?>

	<?php // Menu
		get_template_part('views/footer/footer-menu');
	?>

	<?php // sites
		get_template_part('views/footer/sites-links');
	?>

	<div class="copyrights">&copy; 1996 <?php bloginfo('name'); ?></div>

	<?php /*
	<div class="monitoring">
		<?php echo get_num_queries() . ' queries in ' . timer_stop(0) . ' seconds. '; ?>
	</div>
	*/ ?>

</footer>