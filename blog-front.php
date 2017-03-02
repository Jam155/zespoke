<article id="post-<?php the_ID(); ?>" <?php post_class('medium-3 post-wrapper'); ?>>
	<header class="entry-header">
		<img src="<?php echo get_field('featured_image', $post->ID); ?>" />
		<a class="button" href="<?php the_permalink(); ?>">Read More</a>
	
		<p class="date">March 16th 2016</p>
	</header><!-- .entry-header -->
</article><!-- #post -->
