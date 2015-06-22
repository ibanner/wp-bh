<?php

	$faqs = get_sub_field('faqs_list');
	
	if ($faqs) :
	
		echo '<ul class="faqs">';
		
			foreach($faqs as $faq) :
			
				$q	= $faq['question'];
				$a	= $faq['answer'];
				
				echo '<li>';
					echo '<div class="question">' . $q . '</div><hr />';
					echo '<div class="answer" style="display: none;">' . $a . '</div>';
				echo '</li>';
			
			endforeach;
			
		echo '</ul>';
	
	endif;

?>