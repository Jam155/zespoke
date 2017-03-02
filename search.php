<?php get_header(); ?>
<div class="pagecontentwrap row">
	<header class="entry-header">
		<h1 class="entry-title">Search</h1>
		<p>Search results for: <b><?php echo get_search_query(); ?></b></p>
	</header>
<?php global $query_string; ?>
<?php query_posts($query_string . '&post_type=product&showposts=20&order=ASC'); ?>
<?php if ( have_posts() ) : ?>
<div class="products row">
<?php while ( have_posts() ) : the_post(); ?>

	<!-- Start of Product -->

	<?php
		$classes = array();

		$classes[] = 'columns';
		$classes[] = 'small-6';
		$classes[] = 'medium-3';
		$classes[] = 'small-12';
		$classes[] = 'popular-designs';

	?>
	<div class="small-6 medium-3">

		<a href="<?php echo get_permalink() ?>" title="<?php echo get_the_title($product_id) ?>" alt="<?php echo get_the_title($product_id); ?>" <?php post_class($classes); ?>>
	
			<div class="image">

		        	<?php $customise = $product->get_attribute('customisable'); ?>

		        	<?php if (true || $customise === "true"): ?>

		                	<span>Customise It</span>
		                	<img class="customise_logo" src="<?php echo get_template_directory_uri(); ?>/imgs/customise-logo.png" />

		        	<?php endif; ?>

		        	<?php echo $product->get_image('full'); ?>

			</div>

			<h2><?php the_title(); ?></h2>

			<?php

		        	$regu_price = $product->get_regular_price();
		        	$sale_price = $product->get_sale_price();

		        	$saving = (($regu_price - $sale_price) / $regu_price) * 100;

			?>

			<p class="price"><span class="rrp">RRP: £<?php echo $product->get_display_price($product->get_regular_price()); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span><span class="sale">£<?php echo $sale_price; ?></span></p>
		
		</a>

	</div>

	<!-- End of Product -->



<?php endwhile; ?>
</div>
<?php else : ?>
<div id="post-0" class="medium-12 columns post no-results not-found">
<section class="entry-content search-page-none">
<p>Sorry, we couldnt find any products for this search.</p>
</section>
</div>
<?php endif; ?>
<?php wpbeginner_numeric_posts_nav(); ?>
</div>
<?php get_footer(); ?>
