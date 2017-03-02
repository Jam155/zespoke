<?php
/*
Template Name: Reviews Page 
*/
?>

<?php get_header(); ?>
<div class="pagecontentwrap row"> 
<?php while ( have_posts() ) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>">
		<div class="entry-content">
			<?php the_content(); ?>
			<div class="row product-reviews">
				<script type="text/javascript">
				  _tsRatingConfig = {
					tsid: 'X7C65A861F9B1F19720DDF7B32D36DEE6',
					variant: 'vertical', 
					theme: 'light',
					introtext: "<span class='fancy'>Real</span> Customer Reviews",
					reviews: 10, 
					colorclassName: 'product-review-box',
					richSnippets: 'off'
				  };
				  var scripts = document.getElementsByTagName('SCRIPT'),
				  me = scripts[scripts.length - 1];
				  var _ts = document.createElement('SCRIPT');
				  _ts.type = 'text/javascript';
				  _ts.async = true;
				  _ts.charset = 'utf-8';
				  _ts.src ='//widgets.trustedshops.com/reviews/tsSticker/tsSticker.js';
				  me.parentNode.insertBefore(_ts, me);
				  _tsRatingConfig.script = _ts;
				</script>	
			</div>
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