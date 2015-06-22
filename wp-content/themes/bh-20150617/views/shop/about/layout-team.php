<?php

	// layout parameters
	$pre_text	= get_field('acf-shop_about_team_pre_text');
	$team		= get_field('acf-shop_about_team_members');
	
	if ($pre_text || $team) :
	
		echo '<section class="shop-about-layout shop-about-layout-team">';
			echo '<div class="container">';
			
				echo ($pre_text) ? '<div class="pre-text">' . $pre_text . '</div>' : '';
				
				if ($team) :
					echo '<div class="team">';
					
						$i = 0;
						foreach ($team as $t) :
							$image	= $t['image'];
							$name	= $t['name'];
							$desc	= $t['description'];
							
							if ($image && $name) :
								echo ($i%3 == 0) ? '<div class="row">' : '';
								
									echo '<div class="col-sm-4 team-member">';
										echo '<div class="team-member-image">';
											echo '<img src="' . $image['url'] . '" alt="' . $name . '" width="' . $image['width'] . '" height="' . $image['height'] . '" />';
										echo '</div>';
										
										echo '<h3 class="team-member-name">' . $name . '</h3>';
										
										echo ($desc) ? '<div class="team-member-desc">' . $desc . '</div>' : '';
									echo '</div>';
									
									$i++;
									
								echo ($i%3 == 0) ? '</div>' : '';
							endif;
							
						endforeach;
						
						//close unclosed row
						echo ($i%3 != 0) ? '</div>' : '';
						
					echo '</div>';
				endif;
				
			echo '</div>';
		echo '</section>';
		
	endif;

?>