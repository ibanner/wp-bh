<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<!-- Facebook Open Graph API -->
<meta property="fb:app_id" content="666465286777871"/>

<title><?php wp_title('', true, 'left'); ?></title>

<!-- Google Fonts -->
<?php
	global $google_fonts;
	
	if ($google_fonts) : foreach ($google_fonts as $key => $val) :
		printf ("<link href='%s' rel='stylesheet'>", $val);
	endforeach; endif;
?>

<!-- Google Analytics tracking code -->
<script>
	_BH_GA_tid	= '<?php the_field('acf-options_tracking_code', 'option'); ?>';
</script>
<!-- End Google Analytics tracking code -->