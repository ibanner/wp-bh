<?php
/**
 * Header view
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/header
 * @version     2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Set global variables
global	$bh_sites,		// BH sites information
		$shop_page,		// True / False	- Is shop page (set earlier in woocommerce-functions.php)
		$current_site,	// main / shop
		$menu;			// Menu theme location

$current_site = is_woocommerce() || $shop_page ? 'shop' : 'main';

$bh_sites = array(
	'main'				=> array(
		'link'			=> HOME,
		'header_title'	=> get_field('acf-options_main_site_header_title', 'option'),
		'footer_title'	=> get_field('acf-options_main_site_footer_title', 'option'),
		'featured_page'	=> get_field('acf-options_main_site_featured_page', 'option'),
		'menu'			=> 'main-menu'
	),

	'shop'				=> array(
		'link'			=> wc_get_page_permalink('shop'),
		'header_title'	=> get_field('acf-options_shop_site_header_title', 'option'),
		'footer_title'	=> get_field('acf-options_shop_site_footer_title', 'option'),
		'featured_page'	=> get_field('acf-options_shop_site_featured_page', 'option'),
		'menu'			=> /*'shop-menu'*/'main-menu'
	),

	'mjs'				=> array(
		'link'			=> get_field('acf-options_mjs_site_link', 'option'),
		'header_title'	=> get_field('acf-options_mjs_site_header_title', 'option'),
		'footer_title'	=> get_field('acf-options_mjs_site_footer_title', 'option')
	)
);

// Set specific page variables for menu manipulation
$blog_page		= get_field('acf-options_blog_page', 'option');
$blog_page_id	= $blog_page ? $blog_page->ID : '';
$events_page	= get_field('acf-options_events_page', 'option');
$events_page_id	= $events_page ? $events_page->ID : '';

// Build menu
$args = array(
	'theme_location'		=> $bh_sites[$current_site]['menu'],
	'container'				=> false,
	'items_wrap'			=> '%3$s',
	'before'				=> '<span class="item-before disable"></span>',
	'link_before'			=> '<span>',
	'link_after'			=> '</span>',
	'depth'					=> 2,
	'add_blog_list_under'	=> $blog_page_id,
	'add_events_list_under'	=> $events_page_id,
	'echo'					=> 0
);
$menu = wp_nav_menu($args); ?>

<header>

	<div class="header-top <?php echo $current_site; ?>">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 sites">
					<?php if ($bh_sites) : ?>
						<ul>
							<?php foreach ($bh_sites as $site_name => $site_info) : ?>
								<li class="site-item <?php echo $site_name . ($site_name == $current_site ? ' active' : ''); ?>">
									<a href="<?php echo $site_info['link']; ?>" target="<?php echo $site_name == 'mjs' ? '_blank' : '_self'; ?>">
										<div class="title"><?php echo $site_info['header_title']; ?></div>
										<div class="icon"></div>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="col-sm-6">
					<div class="logo">
						<a href="<?php echo HOME; ?>" title="<?php echo esc_attr( get_bloginfo('name') ); ?>"></a>
					</div>

					<div class="header-top-elements">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="header-bottom <?php echo $current_site; ?>">
		<div class="container">

			<!-- Menu -->
			<?php if ($menu) : ?>
				<nav class="menu">
					<ul class="nav">
						<?php if ( function_exists('BH_get_cached_menu') ) {
							echo BH_get_cached_menu($menu, $bh_sites[$current_site]['menu']);
						} else {
							echo $menu;
						} ?>
					</ul>
				</nav>
			<?php endif; ?>
			<!-- End menu -->

			<?php
				$featured_page = $bh_sites[$current_site]['featured_page'];
				if ($featured_page) : ?>
					<div class="featured-page">
						<a href="<?php echo get_permalink($featured_page->ID); ?>"><?php echo $featured_page->post_title; ?></a>
					</div>
				<?php endif;
			?>

		</div>
	</div>

</header>


<?php /*

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