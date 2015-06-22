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
		
		<script>
			var _prum = [['id', '54febcb3abe53d756b1004a5'],
				['mark', 'firstbyte', (new Date()).getTime()]];
			(function() {
				var s = document.getElementsByTagName('script')[0],
					p = document.createElement('script');
				p.async = 'async';
				p.src = '//rum-static.pingdom.net/prum.min.js';
				s.parentNode.insertBefore(p, s);
			})();
		</script>
		
	</head>
	
	<body <?php body_class(); ?>>
		<?php
			if ( is_archive() || is_singular('post') )
				get_template_part('views/header/facebook-api');
				
			get_template_part('views/header/google-tag-manager');
			
			get_template_part('views/header/header');
		?>