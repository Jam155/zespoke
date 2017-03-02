<?php get_header(); ?>

<?php $header = get_field('header'); ?>

<div class="threepanels row show-for-medium">

	<div class="center nopadding small-12 columns">
	
		<h2 class="upper"><?php echo $header; ?></h2>
	
	</div>

	
	<?php $three_panels = get_field('three_panel'); ?>

	<?php foreach($three_panels as $panel): ?>
	
		<div class="panel nopadding small-4 columns">
		
			<img src="<?php echo $panel['image']; ?>" />
	
			<div class="content">
		
				<h3><?php echo $panel['header']; ?></h3>
		
				<p class="home-three-steps"><?php echo $panel['text']; ?></p>
	
			</div>
		
		</div>
	
	<?php endforeach; ?>

</div>

<div class="threepanels row show-for-small-only">

	<div class="center nopadding small-12 columns">
	
		<h2 class="upper"><?php echo $header; ?></h2>
	
	</div>

	
	<?php $three_panels = get_field('three_panel'); ?>

	<?php foreach($three_panels as $panel): ?>
	
		<div class="panel nopadding small-12 columns">
		
			<div class="row">
				<div class="small-4 columns">
					<img src="<?php echo $panel['image']; ?>" />
				</div>
				<div class="content small-8 columns">
			
					<h3><?php echo $panel['header']; ?></h3>
			
					<p><?php echo $panel['text']; ?></p>
		
				</div>
			</div>
		
		</div>
	
	<?php endforeach; ?>

</div>

<div class="row sections anchor-row no-border">
	<div class="small-12">
		<a href="#products" class="anchor-btn"><span class="screen-reader-text">Scroll to categories</span></a>
	</div>
</div>

<script>
$('a[href^="#"]').on('click', function(event) {
    var target = $(this.getAttribute('href'));
    if( target.length ) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top + -30
        }, 1000);
    }
});
</script>
	
<div class="row sections no-border home-product-features" id="products">

	<?php $categories = get_field('category_panels'); ?>
	
	<?php foreach($categories as $category): ?>
	
		<div class="medium-6 small-12 columns section">
		
			<img src="<?php echo $category['background_image']; ?>" />
		
			<div class="content <?php echo $category['position']; ?>">
		
				<h2 class="upper"><?php echo $category['category']->name; ?></h2>
			
				<p><?php echo $category['sub_heading']; ?></p>
				<?php
					$pannel_cat = get_category ($category['category']);
				?>
				<a href="<?php echo home_url() ?>/product-category/<?php echo $pannel_cat->slug; ?>/?filter=customise-it" class="cat_button">View the collection</a>
		
			</div>
		
		</div>		
	
	<?php endforeach; ?>

</div>

<div class="row information">

	<?php $info = get_field('information'); ?>

	<div class="medium-7 small-12 small-order-2 medium-order-1 columns nopaddingleft">
	
		<?php echo $info[0]['information']; ?>
	
	</div>
	
	<div class="medium-5 small-12 small-order-1 medium-order-2 columns nopaddingright">
	
		<img class="info_image" src="<?php echo $info[0]['picture']; ?>" />
	
	</div>

</div>

<?php get_footer(); ?>
