<?php

	$links		= get_sub_field('links');
	$link_index	= 0;
	
	echo '<section class="main-layout main-layout-featured-links">';
	
		echo '<div class="container">';
			echo '<div class="row">';

				// contact details
				echo '<div class="col-md-5 col-md-push-7 col-lg-4 col-lg-push-8 contact-details">';
				
					echo '<div class="contact-details-wrapper">';
					
						if ( function_exists('BH_get_cached_contact_details') )
							echo BH_get_cached_contact_details();
						else
							echo BH_get_contact_details();
							
					echo '</div>';
				
				echo '</div>';
				
				// featured links
				echo '<div class="col-md-7 col-md-pull-5 col-lg-8 col-lg-pull-4 featured-links">';

					if ($links) :

						foreach ($links as $l) :
						
							$title			= $l['title'];
							$description	= $l['description'];
							$image			= $l['image'];
							$internal_link	= $l['internal_link'];
							$external_link	= $l['external_link'];
							
							if ($title && $image) :

								$link_index++;
								
								// open a new row for each link
								echo '<div class="row row' . $link_index . '">';
	
									include(locate_template('views/main/featured-links/featured-link.php'));
	
								echo '</div>';
								
							endif;
							
						endforeach;
						
					endif;
					
				echo '</div>';

			echo '</div>';
		echo '</div>';
		
	echo '</section>';

?>