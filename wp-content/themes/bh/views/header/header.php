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
$menu = wp_nav_menu($args);

// Set header elements
$languages_switcher				= BH_languages_switcher();
$newsletter_header_top_popup	= BH_newsletter_popup('newsletter-header-top-menu');
$newsletter_header_mid_popup	= BH_newsletter_popup('newsletter-header-mid-menu');
$links_n_icons					= BH_header_links_n_icons();
$shop_cart_popup				= BH_shop_cart_popup();
$featured_page					= $bh_sites[$current_site]['featured_page'];

?>

<header>

	<div class="header-top <?php echo $current_site; ?>">
		<div class="container">

			<div class="sites">
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

			<div class="logo-wrapper">
				<div class="mobile-menu-btn hidden-lg hidden-md">
					<a href="#"></a>
				</div>

				<div class="logo">
					<a href="<?php echo HOME; ?>" title="<?php echo esc_attr( get_bloginfo('name') ); ?>"></a>
				</div>

				<div class="header-elements header-top-elements visible-lg visible-md">
					<?php if ($languages_switcher) : ?>
						<div class="header-element languages-switcher">
							<?php echo $languages_switcher; ?>
						</div>
					<?php endif; ?>

					<?php if ($links_n_icons) :
						echo $links_n_icons;
					endif; ?>

					<?php if ($current_site == 'shop' && $shop_cart_popup) : ?>
						<div class="header-element shop-cart-popup">
							<?php echo $shop_cart_popup; ?>
						</div>
					<?php endif; ?>

					<?php if ($newsletter_header_top_popup) : ?>
						<div class="header-element newsletter-popup">
							<?php echo $newsletter_header_top_popup; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

	<div class="header-mid <?php echo $current_site; ?> hidden-lg hidden-md">
		<div class="container">

			<?php if ($featured_page) : ?>
				<div class="featured-page">
					<a href="<?php echo get_permalink($featured_page->ID); ?>"><?php echo $featured_page->post_title; ?></a>
				</div>
			<?php endif; ?>

			<div class="header-elements header-mid-elements hidden-lg hidden-md">
				<?php if ($languages_switcher) : ?>
					<div class="header-element languages-switcher">
						<?php echo $languages_switcher; ?>
					</div>
				<?php endif; ?>

				<?php if ($links_n_icons) :
					echo $links_n_icons;
				endif; ?>

				<?php if ($current_site == 'shop' && $shop_cart_popup) : ?>
					<div class="header-element shop-cart-popup">
						<?php echo $shop_cart_popup; ?>
					</div>
				<?php endif; ?>

				<?php if ($newsletter_header_mid_popup) : ?>
					<div class="header-element newsletter-popup">
						<?php echo $newsletter_header_mid_popup; ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<div class="header-bottom <?php echo $current_site; ?> visible-lg visible-md">
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

			<?php if ($featured_page) : ?>
				<div class="featured-page">
					<a href="<?php echo get_permalink($featured_page->ID); ?>"><?php echo $featured_page->post_title; ?></a>
				</div>
			<?php endif; ?>

		</div>
	</div>

</header>