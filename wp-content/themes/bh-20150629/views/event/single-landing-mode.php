<?php

	// get page background
	$content_bg		= get_field('acf-event_background_image');
	$content_style	= ($content_bg) ? 'background-image: url(\'' . $content_bg['url'] . '\'); height: ' . $content_bg['height'] . 'px;' : '';
	
	// get page content
	$content		= get_field('acf-content_page_template_layouts');
	
?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/components/side-menu');
			?>
			
			<div class="col-lg-10 content-landing-mode">
				<?php
					get_template_part('views/components/featured-image');
					
					echo '<div class="landing-content-bg"' . ( ($content_style) ? ' style="' . $content_style . '"' : '' ) . '>';
						echo '<div class="landing-content-center landing-content-center-bg-layer1"' . ( ($content_bg) ? ' style="background-image: url(\'' . $content_bg['url'] . '\');"' : '' ) . '></div>';
						echo '<div class="landing-content-center landing-content-center-bg-layer2"></div>';
						
						echo '<div class="landing-content-center landing-content">';
							get_template_part('views/page/content/content');
						echo '</div>';
					echo '</div>';
				?>
			</div>

		</div>
	</div>
	
</section>