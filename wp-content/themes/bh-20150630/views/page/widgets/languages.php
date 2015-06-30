<?php

	$languages = get_sub_field('languages_list');
	
	if ($languages) :
	
		echo '<ul class="languages widget">';
		
			foreach($languages as $lang) :
			
				$country_code	= $lang['country_code'];
				$language_name	= $lang['language_name'];
				$link			= $lang['page'];
				
				echo '<li>';
					echo '<a href="' . $link . '" title="' . $language_name . '"><img src="' . plugins_url('sitepress-multilingual-cms/res/flags/' . $country_code . '.png') . '" alt="" /></a>';
				echo '</li>';
			
			endforeach;
			
		echo '</ul>';
	
	endif;

?>