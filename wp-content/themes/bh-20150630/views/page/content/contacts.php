<?php

	$contacts		= get_sub_field('contacts_list');
	$widgets_area	= get_field('acf-content_page_template_widgets_area');
	
	$index			= 0;
	$columns		= ($widgets_area) ? 2 : 3;
	
	if ($contacts) :
	
		foreach ($contacts as $c) :
			
			$role		= $c['role'];
			$name		= $c['name'];
			$phone		= $c['phone'];
			$email		= $c['email'];
			$address	= $c['address'];
			
			$index++;
			
			echo ($index%$columns == 1) ? '<div class="row">' : '';
			
				echo '<div class="col-sm-' . ( ($widgets_area) ? '6' : '4' ) . '">';
					echo '<div class="contact">';
						echo '<h3>' . $role . '</h3>';
						echo '<div class="name">' . $name . '</div>';
						echo ($phone) ? '<div class="phone">' . $phone . '</div>' : '';
						echo ($email) ? '<div class="email"><a href="mailto:' . $email . '">' . $email . '</a></div>' : '';
						echo ($address) ? '<div class="address">' . $address . '</div>' : '';
					echo '</div>';
				echo '</div>';
		
			echo ($index%$columns == 0) ? '</div>' : '';
			
		endforeach;
			
		echo ($index%$columns != 0) ? '</div>' : '';
			
	endif;

?>