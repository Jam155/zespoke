<?php get_header(); ?>
<div class="pagecontentwrap row">
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="postwrapper row">
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<p class="date"><?php echo get_the_date('F jS Y'); ?></p>
			</header>

			<div class="small-12 medium-8 postcol">
				<?php the_post_thumbnail('full'); ?>
				
				<div class="post-after-thumb">
					<?php the_content(); ?>
				</div>
			
				<?php comments_template( '', true ); ?>
			</div><!-- .entry-content -->
			
			<div class="small-12 medium-4 sidebar">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Blog Post Sidebar") ) : ?>
				<?php endif;?>
			</div>

		</div><!-- #post -->
		
		<?php // comments_template( '', true ); ?>
	<?php endwhile; // end of the loop. ?>

</div>
<?php get_footer(); ?>