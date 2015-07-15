<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
	
		<?php
			get_template_part('views/header/header', 'meta');
			wp_head();
		?>
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		
		<?php
			get_template_part('views/header/pingdom');
			get_template_part('views/header/facebook-remarketing');

			if ( is_wc_endpoint_url('order-received') ) {
				get_template_part('views/header/facebook-conversion');
			}
		?>
		
	</head>
	
	<body <?php body_class(); ?>>
		<?php
			if ( is_archive() || is_singular('post') )
				get_template_part('views/header/facebook-api');
				
			get_template_part('views/header/google-tag-manager');
			
			get_template_part('views/header/header');
		?>