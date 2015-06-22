<?php
/**
 * Template Name: Past Events
 */?>
<?php get_header(); ?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/components/side-menu');
			?>
			
			<div class="col-lg-10 content-wrapper-wide">
				<?php
					get_template_part('views/event/past-events');
				?>
			</div>

		</div>
	</div>
	
</section>

<?php get_footer(); ?>