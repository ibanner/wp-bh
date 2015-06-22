<?php
/**
 * The Template for displaying product search page
 *
 * @author 		Beit Hatfutsot
 * @package 	bh/views/woocommerce/archive
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="container">
	<?php
		/**
		 * woocommerce_breadcrumb
		 */
		woocommerce_breadcrumb();
	?>
</div>

<div class="container">
	<div class="row shop-archive-section">
	
		<div class="col-sm-11 shop-body-left">
			<div class="row">
			
				<?php if ( have_posts() ) : ?>
				
					<div class="col-sm-12 products-list">
					
						<?php woocommerce_product_loop_start(); ?>
						
							<?php while ( have_posts() ) : the_post(); ?>
							
								<?php wc_get_template_part('content', 'product'); ?>
								
							<?php endwhile; // end of the loop. ?>
							
						<?php woocommerce_product_loop_end(); ?>
						
						<?php get_template_part('views/components/not-found'); ?>
						
					</div>
					
				<?php else : ?>
				
					<div class="col-sm-12">
					
						<?php get_template_part('views/components/not-found'); ?>
					
					</div>
				
				<?php endif; ?>
				
			</div>
		</div>
		
		<div class="col-sm-1 shop-body-right">
		
			<?php get_template_part('views/sidebar/sidebar-shop', 'recently-viewed'); ?>
			
		</div>
		
	</div>
</div>