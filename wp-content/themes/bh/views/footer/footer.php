<footer>

	<?php echo get_template_part('views/footer/footer-links'); ?>

	<div class="container">
	
		<div class="row">

			<div class="col col-sm-8">
				<div class="copyrights">&copy; 1996 <?php bloginfo('name'); ?></div>
			</div>
	
			<div class="col col-sm-4">
				<?php
					// search form
					get_template_part('views/components/search', 'form');
				?>
			</div>
			
		</div>
		
		<?php /*
		<div class="monitoring">
			<?php echo get_num_queries() . ' queries in ' . timer_stop(0) . ' seconds. '; ?>
		</div>
		*/ ?>

	</div>

</footer>