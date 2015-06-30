<?php
/**
 * Template Name: Events
 */?>
<?php get_header(); ?>

<?php

	// set the global $category_id to null in order to display all event categories
	global $category_id;
	$category_id = '';

?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/components/side-menu');
			?>
			
			<div class="col-lg-10 content-wrapper-wide">
				<?php
					get_template_part('views/event/events');
				?>
			</div>

		</div>
	</div>
	
</section>

<?php get_footer(); ?>