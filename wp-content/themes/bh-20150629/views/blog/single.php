<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/sidebar/sidebar', 'blog');
			?>
			
			<div class="col-lg-10 content-wrapper-wide">
			
				<?php
					// post featured image
					get_template_part('views/components/featured-image');
				?>
				
				<article class="post single-post" id="post-<?php the_ID(); ?>">
				
					<?php
						// post categories
						echo '<div class="post-categories">';
							the_category(', ', '', $post->ID);
						echo '</div>';
						
						// post title
						echo '<h2 class="post-title">' . get_the_title() . '</h2>';
						
						// post meta data
						get_template_part('views/components/meta', 'single');
						
						// post content
						echo '<div class="post-content">';
							the_content();
						echo '</div>';
					?>
					
				</article>
				
				<div class="fb-comments-wrapper">
					<div class="fb-comments" data-width="100%" data-href="<?php the_permalink(); ?>" data-numposts="5" data-colorscheme="light"></div>
				</div>
				
			</div>
			
		</div>	
	</div>
	
</section>