<?php

	// create global top menu
	// used for both dektop and mobile menues
	$blog_page		= get_field('acf-options_blog_page', 'option');
	$blog_page_id	= $blog_page ? $blog_page->ID : '';
	$menu_walker	= new BH_main_walker_nav_menu;
	
	$args = array(
		'theme_location'		=> 'top-menu',
		'menu_transient_key'	=> 'header-top-menu',
		'container'				=> false,
		'items_wrap'			=> '%3$s',
		'link_before'			=> '<span>',
		'link_after'			=> '</span>',
		'add_blog_list_under'	=> $blog_page_id,
		'walker'				=> $menu_walker,
		'echo'					=> 0
	);
	
	global $top_menu;
	$top_menu = wp_nav_menu($args);
	
	// create main menu
	// used for both dektop and mobile menues
	$events_page	= get_field('acf-options_events_page', 'option');
	$events_page_id	= $events_page ? $events_page->ID : '';
	
	$args = array(
		'theme_location'		=> 'main-menu',
		'menu_transient_key'	=> 'header-main-menu',
		'container'				=> false,
		'items_wrap'			=> '%3$s',
		'link_before'			=> '<span>',
		'link_after'			=> '</span>',
		'add_events_list_under'	=> $events_page_id,
		'walker'				=> $menu_walker,
		'echo'					=> 0
	);
	$main_menu = wp_nav_menu($args);
	
	if ($main_menu) :
		$main_menu = explode('</li>', $main_menu);
		
		// last element is empty - remove it
		array_pop($main_menu);
	endif;

?>

<header>

	<div class="navbar navbar-default" role="navigation">
	
		<div class="container">
		
			<div class="navbar-header">
				<a class="navbar-brand bh-sprites" href="<?php echo HOME; ?>" title="<?php echo esc_attr( get_bloginfo('name') ); ?>"></a>

				<button type="button" class="navbar-toggle bh-sprites collapsed" data-toggle="collapse-side" data-target=".side-collapse">
					<span class="sr-only">Toggle navigation</span>
				</button>
			</div>				
			
			<div class="navbar-collapse visible-lg">
				<?php
					
					// top menu
					if ($top_menu) :
					
						echo '<nav class="top-menu">';
							echo '<ul id="menu-top-menu" class="nav navbar-nav ' . ( (ICL_LANGUAGE_CODE == 'he') ? 'navbar-left' : 'navbar-right' ) . '">';
							
								// newsletter menu item
								if ( is_active_sidebar('newsletter-top-menu') ) :
									echo '<li class="menu-item-depth-0 menu-item-newsletter">';
										echo '<a href="#"><span>' . __('Newsletter', 'BH') . '</span></a>';
										echo '<div class="newsletter-widget">';
											dynamic_sidebar('newsletter-top-menu');
											echo '<span class="glyphicon glyphicon-remove"></span>';
										echo '</div>';
									echo '</li>';
								endif;
								
								echo $top_menu;
								echo languages_switcher();
								
							echo '</ul>';
						echo '</nav>';
						
					endif;
	
					// main menu
					if ($main_menu) :
					
						echo '<nav class="main-menu">';
							echo '<ul class="nav nav-justified">';
							
								if ( function_exists('BH_get_cached_desktop_menu') )
									echo BH_get_cached_desktop_menu($main_menu, 'main-menu');
								else
									echo BH_get_desktop_menu($main_menu);
								
							echo '</ul>';
						echo '</nav>';
						
					endif;
					
				?>
			</div>

			<div class="side-collapse hidden-lg in">
				<?php
				
					// mobile menu
					if ($main_menu) :
					
						if ( function_exists('BH_get_cached_mobile_menu') )
							echo BH_get_cached_mobile_menu($main_menu, 'main-menu');
						else
							echo BH_get_mobile_menu($main_menu);
						
					endif;
					
				?>
			</div>
			<div class="side-column hidden-lg"></div>
		
		</div>
		
	</div>

</header>