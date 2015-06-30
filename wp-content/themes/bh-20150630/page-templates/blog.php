<?php
/**
 * Template Name: Blog
 */?>
<?php get_header(); ?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/sidebar/sidebar', 'blog');
			?>
			
			<div class="col-lg-10 content-wrapper-wide">
				<?php
					get_template_part('views/blog/loop');
				?>
			</div>

		</div>
	</div>
	
</section>

<?php get_footer(); ?>