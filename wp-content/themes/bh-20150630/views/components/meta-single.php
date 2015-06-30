<div class="post-author">
	<?php echo __('By ', 'BH') . '<a href="' . get_author_posts_url( get_the_author_meta('ID') ) . '">' . get_the_author_meta('display_name') . '</a>'; ?>
</div>

<div class="post-meta-wrapper">
	<div class="post-meta post-date">
		<?php echo get_the_date(); ?>
	</div>
	
	<div class="post-meta post-comments-count" data-href="<?php the_permalink(); ?>"></div><span class="comments-count">&nbsp;<?php _e('Comments', 'BH'); ?></span>
</div>