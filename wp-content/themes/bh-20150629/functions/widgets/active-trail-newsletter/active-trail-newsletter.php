<?php
/*
Widget Name: Active_Trail_Newsletter
Version: 1.0

Changes Log:
1. Initiation
*/

class Active_Trail_Newsletter extends WP_Widget
{
	function Active_Trail_Newsletter() {
		$widget_ops = array('classname' => 'Active_Trail_Newsletter', 'description' => 'Handles several groups registration');
		$this->WP_Widget('Active_Trail_Newsletter', 'Active Trail Newsletter', $widget_ops);
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
			echo $before_widget;
			
			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
			
			?>
			
				<script src="<?php echo TEMPLATE; ?>/functions/widgets/active-trail-newsletter/jquery.form.min.js"></script>
				
				<script>
					var $ = jQuery.noConflict();
					
					// bind form and send data
					$(document).ready(function() {
						var options = {
							beforeSubmit	: validateForm_<?php echo str_replace( '-', '_', $this->get_field_id('form') ); ?>,
							success			: showResult_<?php echo str_replace( '-', '_', $this->get_field_id('form') ); ?>
						};
						
						$('#<?php echo $this->get_field_id('form'); ?>').ajaxForm(options);
					});
					
					function validateForm_<?php echo str_replace( '-', '_', $this->get_field_id('form') ); ?>(formData, jqForm, options) {
						var result_container	= $('#<?php echo $this->get_field_id('result'); ?>');
						result_container.attr('class', 'result-container hide');
						
						var mailErr				= $('#<?php echo $this->get_field_id('mailErr'); ?>');
						var newsletterErr		= $('#<?php echo $this->get_field_id('newsletterErr'); ?>');
						
						var emailFilter			= /^.+@.+\..{2,3}$/;
						var txtMail				= $('#<?php echo $this->get_field_id('mm_newemail'); ?>');
						
						var newsletterGroups	= $('[name="<?php echo $this->get_field_name('mm_key'); ?>[]"]');
						
						var res					= true;
						
						// mail validation
						if ( txtMail == null || !(emailFilter.test(txtMail.val())) ) {
							mailErr.removeClass('hide');
							txtMail.focus();
							res = false;
						}
						else {
							mailErr.addClass('hide');
						}
						
						// newsletter groups validation
						var checked = false;
						for (var i=0; i<newsletterGroups.length; i++) {
							if (newsletterGroups[i].checked) {
								checked = true;
								break;
							}
						}
						
						if (!checked) {
							newsletterErr.removeClass('hide');
							res = false;
						}
						else {
							newsletterErr.addClass('hide');
						}
						
						if (!res)
							return false;
							
						// prepare result container
						result_container.find('.result').html('');
						result_container.removeClass('hide');
						result_container.find('.loader').removeClass('hide');
						
						return true;
					}
					
					function showResult_<?php echo str_replace( '-', '_', $this->get_field_id('form') ); ?>(responseText, statusText, xhr, $form) {
						var result_container	= $('#<?php echo $this->get_field_id('result'); ?>');
						var msg_999				= '<?php _e('General error, please try again later', 'BH'); ?>';
						var msg_1				= '<?php _e('Registration has been failed, please try again later', 'BH'); ?>';
						var msg_0				= '<?php _e('Registration was successful, thank you!', 'BH'); ?>';
						
						if (statusText == 'success') {
							if (responseText=='0') {
								result_container.find('.result').addClass('success');
								result_container.find('.result').html(msg_0);
							}
							else {
								result_container.find('.result').addClass('error');
								result_container.find('.result').html(msg_1);
							}
							
							result_container.find('.loader').addClass('hide');
							
							return true;
						}
						else {
							result_container.find('.result').addClass('error');
							result_container.find('.result').html(msg_999);
							result_container.find('.loader').addClass('hide');
							
							return false;
						}
					}
				</script>
			
			<?php
			
			if (!empty($title))
				echo $before_title . $title . $after_title;
				
			echo '<div class="widgetcontent">';
				echo ( $instance['pre_text'] ) ? '<div class="pre-text">' . apply_filters('widget_text', $instance['pre_text'], $instance) . '</div>' : '';
				
				?>
				
				<form id="<?php echo $this->get_field_id('form'); ?>" action="<?php echo TEMPLATE; ?>/functions/widgets/active-trail-newsletter/newsletter-api.php" method="post">
					<input id="<?php echo $this->get_field_id('mm_userid'); ?>" class="mm_userid" name="<?php echo $this->get_field_name('mm_userid'); ?>" type="hidden" value="<?php echo $instance['at_mm_userid']; ?>" />
					
					<input id="<?php echo $this->get_field_id('mm_newemail'); ?>" class="mm_newemail" name="<?php echo $this->get_field_name('mm_newemail'); ?>" type="text" placeholder="<?php _e('Email Address', 'BH'); ?>" value="" />
					<div id="<?php echo $this->get_field_id('mailErr'); ?>" class="errph hide"><?php _e('Email address is missing or incorrect', 'BH'); ?></div>
					
					<div class="newsletter-groups">
						<?php
							foreach ($instance['groups'] as $group)
								echo '<label><input type="checkbox" name="' . $this->get_field_name('mm_key') . '[]" id="' . $group['id'] . '" value="' . $group['id'] . '" />' . $group['name'] . '</label>';
						?>
					</div>
					<div id="<?php echo $this->get_field_id('newsletterErr'); ?>" class="errph hide"><?php _e('Please check at least one newsletter name', 'BH'); ?></div>
					
					<input class="newsletter-submit" type="submit" value="<?php _e('Register', 'BH'); ?>" />
				</form>
				
				<div id="<?php echo $this->get_field_id('result'); ?>" class="result-container hide">
					<div class="loader hide">
						<?php get_template_part('views/components/loader'); ?>
					</div>
					<div class="result"></div>
				</div>
				
				<?php

			echo '</div>';
			
			echo $after_widget;
 		endif;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Active_Trail_Newsletter");') );

?>