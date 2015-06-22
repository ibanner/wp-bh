<?php

	// filter past events
	$end_date	= get_field('acf-event_end_date');
	$today		= date_i18n('Ymd');
	$past_event	= ($end_date < $today) ? true : false;
	
	// get event attributes
	$ticket_net_btn = (!$past_event) ? BH_get_event_purchase_btn($post->ID) : '';
	
	// get page widgets area
	$widgets_area = get_field('acf-content_page_template_widgets_area');
	
?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				get_template_part('views/components/side-menu');
			?>
			
			<?php if ($widgets_area) : ?>
				<div class="col-sm-4 col-sm-push-8 col-md-4 col-md-push-8 col-lg-3 col-lg-push-7 widgets-area-wrapper">
					<?php
						get_template_part('views/page/widgets/widgets');
					?>
				</div>
			<?php endif; ?>
			
			<div class="<?php echo ($widgets_area) ? 'col-sm-8 col-sm-pull-4 col-md-8 col-md-pull-4 col-lg-7 col-lg-pull-3 content-wrapper' : 'col-lg-10 content-wrapper-wide'; ?>">
				<?php
					echo ($ticket_net_btn) ? '<div class="single-ticket-net-btn">' . $ticket_net_btn . '</div>' : '';
					
					get_template_part('views/components/featured-image');
					get_template_part('views/page/content/content');
					get_template_part('views/event/series');
				?>
			</div>

		</div>
	</div>
	
</section>