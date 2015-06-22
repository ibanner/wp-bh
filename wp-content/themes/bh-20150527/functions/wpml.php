<?php

	/**
	 * WPML - disable scripts & styles
	 */

	define('ICL_DONT_LOAD_NAVIGATION_CSS',			true);
	define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS',	true);
	define('ICL_DONT_LOAD_LANGUAGES_JS',			true);
	
	/**
	 * languages switcher
	 */

	function languages_switcher() {
		$languages		= icl_get_languages('skip_missing=0&orderby=code');
		$lang_switcher	= '';
		
		if (!empty($languages)) :
		
			foreach ($languages as $l) :
				if (!$l['active']) :
					$lang_switcher .= '<li class="menu-item-depth-0">' .
						'<a href="' . $l['url'] . '">' .
							icl_disp_language($l['native_name'], $l['translated_name']) .
						'</a>' .
					'</li>';
				endif;
			endforeach;
			
		endif;
		
		return $lang_switcher;
	}

?>