<?php

	$contacts		= get_sub_field('contacts_list');
	
	if ($contacts) :
	
		foreach ($contacts as $c) :
			
			$role	= $c['role'];
			$name	= $c['name'];
			$phone	= $c['phone'];
			$email	= $c['email'];
			
			echo '<div class="contact-wrapper">';
				echo '<div class="contact">';
					echo '<h3>' . $role . '</h3>';
					echo '<div class="name">' . $name . '</div>';
					echo ($phone) ? '<div class="phone">' . $phone . '</div>' : '';
					echo ($email) ? '<div class="email"><a href="mailto:' . $email . '">' . $email . '</a></div>' : '';
					echo ($address) ? '<div class="address">' . $address . '</div>' : '';
				echo '</div>';
			echo '</div>';
			
		endforeach;
		
	endif;

?>