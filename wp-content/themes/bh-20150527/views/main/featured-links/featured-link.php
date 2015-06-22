<?php

	$link_value = '';
	$link_target = '';
	
	if ($internal_link || $external_link) :
		$link_value		= ($internal_link) ? $internal_link : $external_link;
		$link_target	= ($internal_link) ? '_self' : '_blank';
	endif;
	
	// build link image
	$link_image =
		'<div class="link-image">' .
			( ($link_value) ? '<a href="' . $link_value . '" target="' . $link_target . '">' : '' ) .
				'<img src="' . $image['url'] . '" alt="' . ( ($image['alt']) ? $image['alt'] : $title ) . '" />' .
			( ($link_value) ? '</a>' : '' ) .
		'</div>';
		
	// build link content
	$link_content =
		'<div class="link-content">' .
			'<h3>' . $title . '</h3>' . $description .
		'</div>';

	// display link
	if ($link_index == 1) :

		// first link
		echo '<div class="col-sm-12">';
			echo $link_image;
			echo $link_content;
		echo '</div>';
		
	else :
	
		// other links
		echo '<div class="col-sm-8 col-md-12 col-lg-9">' . $link_image . '</div>';
		echo '<div class="col-sm-4 col-md-12 col-lg-3 link-content-wrapper">' . $link_content . '</div>';
		
	endif;

?>