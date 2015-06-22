<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/sidebar/sidebar', 'blog');
			?>
			
			<div class="col-lg-10 content-wrapper-wide">
				<?php
					if (have_posts()) :
					
						while (have_posts()) : the_post();
						
							get_template_part('views/blog/loop', 'item');
							
						endwhile;
						
					else :
					
						get_template_part('views/components/not-found');
					
					endif;
				?>
			</div>
			
		</div>	
	</div>
	
</section>