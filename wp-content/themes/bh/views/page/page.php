<?php

	global $shop_page	// is shop page (true/false)
	
	$widgets_area	= get_field('acf-content_page_template_widgets_area');								// get page widgets area
	
?>

<?php
	if ($shop_page) :
	
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked	woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 */
		do_action('woocommerce_before_main_content');
		
		echo '<div class="container">';
			woocommerce_breadcrumb();
		echo '</div>';
		
	endif;
?>

<section class="page-content">

	<div class="container">
		<div class="row">
		
			<?php
				if ( ! $shop_page )
					get_template_part('views/components/side-menu');
			?>
			
			<?php if ( post_password_required($post->ID) ) :

				$label				= 'protected-page-' . ( empty($post->ID) ? rand() : $post->ID );
				$protected_content	= get_field('acf-content_page_password_protected_content');
				$form				=
					'<form class="password-protected-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">' .
						'<label for="' . $label . '">' . __( "Password:" ) . '</label><br />' .
						'<input name="post_password" id="' . $label . '" type="password" size="20" />' .
						'<input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" />' .
					'</form>';

				echo '<div class="col-lg-10 content-wrapper-wide ' . ( ($shop_page) ? 'shop-page' : '' ) . '">';
					echo '<div class="protected-content">';
						echo ($protected_content) ? $protected_content : '<p>' . __( "This content is password protected. To view it please enter your password below:" ) . '</p>';
					echo '</div>';

					echo $form;
				echo '</div>';

			else : ?>

				<?php if ($widgets_area) : ?>
					<div class="col-sm-4 col-sm-push-8 col-md-4 col-md-push-8 col-lg-3 col-lg-push-7 widgets-area-wrapper <?php echo ($shop_page) ? 'shop-page' : ''; ?>">
						<?php
							get_template_part('views/page/widgets/widgets');
						?>
					</div>
				<?php endif; ?>
				
				<div class="<?php echo ($widgets_area) ? 'col-sm-8 col-sm-pull-4 col-md-8 col-md-pull-4 col-lg-7 col-lg-pull-3 content-wrapper' : 'col-lg-10 content-wrapper-wide'; ?> <?php echo ($shop_page) ? 'shop-page' : ''; ?>">
					<?php
						get_template_part('views/components/featured-image');
						get_template_part('views/page/content/content');
					?>
				</div>

				<?php
					// check if a password protected cookie is exist, and delete it
					// this way we prevent w3 total cache from reloading each page because of this cookie is setup (for the particular session)
					if ( isset( $_COOKIE['wp-postpass_' . COOKIEHASH] ) ) {
						unset( $_COOKIE['wp-postpass_' . COOKIEHASH] );
						setcookie( 'wp-postpass_' . COOKIEHASH, null, -1, '/' );
					}
				?>

			<?php endif; ?>

		</div>
	</div>
	
</section>

<?php
	if ($shop_page) :
	
		/**
		 * BH_shop_footer hook
		 * 
		 * @hooked	BH_shop_footer_link_boxes - 10
		 */
		do_action('BH_shop_footer');
		
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked	woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
		
	endif;
?>