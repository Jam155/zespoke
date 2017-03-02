<?php get_header(); ?>
<div class="pagecontentwrap row">
	<header class="entry-header">
		<h1 class="entry-title">Blog</h1>
		<p>Read all the latest news and articles from Zespoke</p>
	</header>
<?php $c = 0; $style=''; ?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php 
	$c++;
	if( $c == 1 ) {
		$style='small-12 medium-6 large-6';
	} elseif( $c == get_option( 'posts_per_page' ) ) {
		$style='small-12 medium-6 large-6';
	} else {
		$style='small-12 medium-6 large-3';
	} ?>
	
	<?php $paddedPost='paddedPost'.$c; ?>
	<?php $classes = array("post-wrapper", $style, $paddedPost); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

		<?php

			$s_image = get_field('blog_small', $post->ID);

			if (!$s_image) {

				$s_image = get_field('featured_image', $post->ID);

			}

			if (is_numeric($s_image)) {

				$s_image = wp_get_attachment_image_src($s_image);

			}

			if (is_array($s_image)) {

				$s_image = $s_image[0];

			}

			//$image = get_field('blog_small', $post->ID);

			$l_image = get_field('image_large', $post->ID);

			if (!$l_image) {

				$l_image = get_field('featured_image', $post->ID);

			}

			if (is_numeric($l_image)) {

				$l_image = wp_get_attachment_image_src($l_image);

			}

			if (is_array($l_image)) {

				$l_image = $l_image[0];

			}

		?>

		<img src="<?php echo $s_image; ?>" class="small_image" />
		<img src="<?php echo $l_image; ?>" class="large_image" />
		
		<div class="blog-post-content">
			<h3><?php the_title(); ?></h3>
			<a class="button" href="<?php the_permalink(); ?>">Read More</a>
			<p class="date"><?php echo get_the_date('F jS Y'); ?></p>
		</div>
		
	</article><!-- #post -->
<?php $style=''; ?>
<?php endwhile; // end of the loop. ?>

<?php wpbeginner_numeric_posts_nav(); ?>
</div>

<?php get_footer(); ?>
