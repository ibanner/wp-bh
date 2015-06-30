<?php

	// get page content
	$page_404_top_msg		= get_field('acf-options_404_top_message',		'option');
	$page_404_bottom_msg	= get_field('acf-options_404_bottom_message',	'option');
	
?>

<section class="page-content">

	<div class="container container-404">
		<div class="bg-wrap">
			<div class="bg"></div>
		</div>
		
		<div class="row top-msg">
		
			<div class="col-sm-7 col-sm-push-5 col-lg-6 col-lg-push-6">
				<div class="top-msg-title">e<span class="small">rror</span>=<span class="big">404</span><span class="top">2</span></div>
				<div class="top-msg-content"><?php echo $page_404_top_msg; ?><br /><a href="<?php echo HOME; ?>"><?php _e('Back to Homepage', 'BH'); ?></a></div>
			</div>
			
		</div>
		
		<div class="quote">
		
			<div class="row bottom-msg">
			
				<div class="col-sm-7 col-sm-push-5 col-lg-6 col-lg-push-6">
					<div class="bottom-msg-content"><?php echo $page_404_bottom_msg; ?></div>
				</div>
				
			</div>
			
		</div>
	</div>
	
</section>