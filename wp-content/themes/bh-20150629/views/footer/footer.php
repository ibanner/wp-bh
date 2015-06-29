<footer>

	<div class="container">
	
		<div class="row">

			<div class="col col-md-3">
				<div class="nadav-logo bh-sprites"></div>
			</div>
	
			<div class="col col-sm-8 col-md-6">
				<?php
					// footer menu
					get_template_part('views/components/footer-menu');
				?>
			</div>
	
			<div class="col col-sm-4 col-md-3">
				<?php
					// search form
					get_template_part('views/components/search', 'form');
				?>
			</div>
			
		</div>
		
		<div class="monitoring" style="display: none;">
			<?php echo get_num_queries() . ' queries in ' . timer_stop(0) . ' seconds. '; ?>
		</div>

	</div>

</footer>