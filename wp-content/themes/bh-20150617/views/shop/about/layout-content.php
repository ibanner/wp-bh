<section class="shop-about-layout shop-about-layout-content">

	<div class="container">
	
		<?php
			if (have_posts()) : the_post();
				echo '<div class="content">';
					the_content();
				echo '</div>';
			endif;
		?>
		
	</div>
	
	<?php get_template_part('views/components/featured-image'); ?>
	
</section>