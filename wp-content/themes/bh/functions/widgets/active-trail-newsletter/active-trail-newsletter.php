<?php
/*
Widget Name: Active_Trail_Newsletter
Version: 2.0

Changes Log:
1.0
===
1. Initiation

2.0
===
1. Enqueu scripts instead of inline calls
2. Form classes declarations instead of using IDs in order to enable several widget instances in a single page
*/

class Active_Trail_Newsletter extends WP_Widget
{
	function __construct() {
		$widget_options = array( 
			'classname' => 'Active_Trail_Newsletter',
			'description' => 'Handles several groups registration',
		);
		parent::__construct( 'Active_Trail_Newsletter', 'Active Trail Newsletter', $widget_options );
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance,
			array (
				'at_mm_userid'	=> '',
				'title'			=> '',
				'pre_text'		=> '',
				'groups'		=> ''
			));
			
		$at_mm_userid	= $instance['at_mm_userid'];
		$title			= $instance['title'];
		$pre_text		= $instance['pre_text'];

		$groups			= isset($instance['groups']) ? $instance['groups'] : array();
		$groups_html	= array();
		$groups_counter	= 0;
		
		if ($groups) :
			foreach ($groups as $group) :
				if ( isset($group['id']) && isset($group['name']) )
					$groups_html[] = sprintf(
						'<div class="group">' .
							'<div class="inline-group inline-left"><label for="%1$s[%2$s][id]">ID: <input class="widefat" id="%1$s[%2$s][id]" name="%1$s[%2$s][id]" type="text" value="%3$s" /></label></div>' .
							'<div class="inline-group"><label for="%1$s[%2$s][name]">Name: <input class="widefat" id="%1$s[%2$s][name]" name="%1$s[%2$s][name]" type="text" value="%4$s" /></label></div>' .
							'<span class="remove-group">Remove</span>' .
						'</div>',
						
						$this->get_field_name('groups'),
						$groups_counter,
						esc_attr($group['id']),
						esc_attr($group['name'])
					);
					
				$groups_counter++;
			endforeach;
		endif;
		
		?>
			<style>
				.widget-content .inline {
					float: left;
					width: 49%;
				}
				
				.widget-content .inline-group {
					float: left;
					width: 42%;
				}
				
				.widget-content .inline-left {
					margin-right: 2%;
				}
				
				.widget-content .groups {
					margin-top: 10px;
				}
				
				.widget-content .group {
					margin-bottom: 10px;
					overflow: hidden;
				}
				
				.widget-content .add-group,
				.widget-content .remove-group {
					color: #0000ff;
					cursor: pointer;
					display: inline-block;
				}
				
				.widget-content .remove-group {
					margin: 23px 0 0 5px;
				}
			</style>
			
			<script>
				var groupsField	= <?php echo json_encode($this->get_field_name('groups')); ?>;
				var groupsNum	= <?php echo json_encode($groups_counter) ?>;
				
				var $ = jQuery.noConflict();
				$(document).ready(function() {
					var count = groupsNum;
					
					// add group
					$('.<?php echo $this->get_field_id('add-group'); ?>').click(function() {
						var group =
							'<div class="group">' +
								'<div class="inline-group inline-left"><label for="' + groupsField + '[' + count + '][id]">ID: <input class="widefat" id="' + groupsField + '[' + count + '][id]" name="' + groupsField + '[' + count + '][id]" type="text" value="" /></label></div>' +
								'<div class="inline-group"><label for="' + groupsField + '[' + count + '][name]">Name: <input class="widefat" id="' + groupsField + '[' + count + '][name]" name="' + groupsField + '[' + count + '][name]" type="text" value="" /></label></div>' +
								'<span class="remove-group">Remove</span>' +
							'</div>';
						
						$('#<?php echo $this->get_field_id('groups'); ?>').append(group);
						
						count++;
					});
					
					// remove group
					$(".remove-group").live('click', function() {
						$(this).parent().remove();
					});
				});
			</script>
			
			<p class="inline inline-left"><label for="<?php echo $this->get_field_id('at_mm_userid'); ?>">Active Trail UserID: <input class="widefat" id="<?php echo $this->get_field_id('at_mm_userid'); ?>" name="<?php echo $this->get_field_name('at_mm_userid'); ?>" type="text" value="<?php echo esc_attr($at_mm_userid); ?>" /></label></p>
			<p class="inline"><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('pre_text'); ?>">Pre Text: <textarea class="widefat" id="<?php echo $this->get_field_id('pre_text'); ?>" name="<?php echo $this->get_field_name('pre_text'); ?>" rows="5"><?php echo esc_attr($pre_text); ?></textarea></label></p>
			<div>
				<label>Newsletter Groups:</label>
				
				<div class="groups" id="<?php echo $this->get_field_id('groups'); ?>">
					<?php echo implode('', $groups_html); ?>
				</div>
				
				<span class="add-group <?php echo $this->get_field_id('add-group'); ?>">+ Add Group</span>
			</div>
		<?php
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['at_mm_userid']	= $new_instance['at_mm_userid'];
		$instance['title']			= $new_instance['title'];
		$instance['pre_text']		= $new_instance['pre_text'];
		
		$instance['groups']			= array();
		
		if ( isset($new_instance['groups']) )
			foreach ($new_instance['groups'] as $group)
				if (trim($group['id']) !== '' && trim($group['name']) !== '')
					$instance['groups'][] = $group;
		
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		// widget content
		if ( $instance['at_mm_userid'] && $instance['groups'] ) :
			wp_enqueue_script('jquery-form',				TEMPLATE . '/functions/widgets/active-trail-newsletter/jquery.form.min.js',			array('jquery'),	VERSION,	true);
			wp_enqueue_script('active-trail-newsletter',	TEMPLATE . '/functions/widgets/active-trail-newsletter/active-trail-newsletter.js',	array('jquery'),	VERSION,	true);

			echo $before_widget;

			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);

			if (!empty($title))
				echo $before_title . $title . $after_title;
				
			echo '<div class="widgetcontent">';
				echo ( $instance['pre_text'] ) ? '<div class="pre-text">' . apply_filters('widget_text', $instance['pre_text'], $instance) . '</div>' : '';
				
				?>
				
				<form class="widget-active_trail_newsletter-form" action="<?php echo TEMPLATE; ?>/functions/widgets/active-trail-newsletter/newsletter-api.php" method="post">
					<input class="mm_userid" name="mm_userid" type="hidden" value="<?php echo $instance['at_mm_userid']; ?>" />
					
					<small><?php _e('Your Email:', 'BH'); ?></small>
					<input class="mm_newemail" name="mm_newemail" type="text" placeholder="<?php _e('Email Address', 'BH'); ?>" value="" />
					<div class="mailErr errph hide"><?php _e('Email address is missing or incorrect', 'BH'); ?></div>
					
					<div class="newsletter-groups">
						<small><?php _e('Choose Language:', 'BH'); ?></small>
						
						<?php
							foreach ($instance['groups'] as $group) {
								echo '<input type="checkbox" name="mm_key[]" id="mm_key[' . $group['id'] . ']" value="' . $group['id'] . '" />';
								echo '<label for="mm_key[' . $group['id'] . ']"><span>' . $group['name'] . '</span></label>';
							}
						?>
					</div>
					<div class="newsletterErr errph hide"><?php _e('Please check at least one newsletter name', 'BH'); ?></div>
					
					<input class="newsletter-submit" type="submit" value="<?php _e('Register', 'BH'); ?>" />
				</form>
				
				<div class="result-container">
					<div class="loader hide">
						<?php get_template_part('views/components/loader'); ?>
					</div>
					<div class="result">
						<div class="msg msg-999 hide"><?php _e('General error, please try again later', 'BH'); ?></div> 
						<div class="msg msg-1 hide"><?php _e('Registration has been failed, please try again later', 'BH'); ?></div> 
						<div class="msg msg-0 hide"><?php _e('Registration was successful, thank you!', 'BH'); ?></div> 
					</div>
				</div>
				
				<?php

			echo '</div>';
			
			echo $after_widget;
 		endif;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Active_Trail_Newsletter");') );