<?php
/*
Template Name: Standard Zespoke Page 
*/
?>

<?php get_header(); ?>
<div class="pagecontentwrap row"> 
<?php while ( have_posts() ) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>">
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</div><!-- #post -->
	
	<?php // comments_template( '', true ); ?>
<?php endwhile; // end of the loop. ?>

</div>

<?php get_footer(); ?>