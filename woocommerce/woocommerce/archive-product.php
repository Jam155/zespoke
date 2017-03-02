<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php

	if (!isset($_GET["filter"])) {

		$_GET["filter"] = 'customise-it';

	}

?>

<?php if (htmlspecialchars($_GET["filter"]) == 'customise-it') { ?>
	<div class="productcatintro row">
		<div class="small-12 medium-5 left columns nopaddingleft">
			<?php the_field('left_text', 170); ?>
		</div>
		<div class="small-12 medium-7 right columns nopaddingright">
				<?php 

				$image = get_field('right_image', 170);
				if( !empty($image) ): ?>
					<img src="<?php echo $image; ?>" />
				<?php endif; ?>
			<?php wp_reset_query(); ?>
		</div>
	</div>
	
	<div class="row sections anchor-row no-border">
		<div class="small-12">
			<button class="anchor-btn" onclick="jump('shopcatalogue')"><span class="screen-reader-text">Scroll to categories</span></a>
		</div>
	</div>
<?php } ?>

<div class="shopcatalogue row" id="shopcatalogue">

<?php if (htmlspecialchars($_GET["filter"]) == 'customise-it') { ?>
	<h1 class="entry-title"><span class="fancy">Customise It!</span> Customise Your <?php echo substr(single_term_title("", false), 0, -1) ?></h1>
<?php } ?>

<?php if (htmlspecialchars($_GET["filter"]) == 'solo-colour') { ?>

<div id="solo-colour">
	<h1 class="entry-title">Our Solo Colour Range</h1>
	
	<div class="productcatintro swatchesintro row">
		<div class="small-12 columns nopadding">
			<p>Filter by Colour:</p>
			<?php
			$thefilter = htmlspecialchars($_GET["filter"]);
			$args = array( 
				'post_type' => 'texture',
				'tag' => $thefilter,
				'posts_per_page' => -1
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$postID = get_the_ID();
				$swatches = get_field('thumbnail', $postID); ?>
				<label class="swatch-label">
					<input type="radio" name="outer-colour" value="<?php echo $postID; ?>" />
					<input type="hidden" name="colour_name" value="<?php the_title(); ?>" />
					<span class="swatch-icon"><img src="<?php echo $swatches ?>" alt="<?php the_title(); ?>" /></span>
				</label>
				<?php endwhile;	?>
				
				<!--script type="text/javascript">

					var productTemplate = wp.template('product-single');

					var getProducts = function(ajaxurl, texture, callback) {
						
						var data = {

							'action': 'single_colour_products',
							'texture': texture,

						};

						jQuery.post(ajaxurl, data, callback);
					};

					var getProduct = function(ajaxurl, id) {

						var data = {

							'action': 'single_product_template',
							'product_id': id,

						}

						jQuery.post(ajaxurl, data, function(response) {

							console.log(response);

						});

					}
					var $radios1 = $('input[name=outer-colour]').change(function () {
						var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
						var $radio = $radios1.filter(':checked');
						var id = $radio.val();
						var title = $radio.parent('.swatch-label').find('input[name=colour_name]').val();
						var url1 = <?php echo json_encode( $swatches ); ?>;
						$(".outer-set-option").html(title);
						var products = getProducts(ajaxurl, id);
						getProducts(ajaxurl, id, function(response) {
							
							if (response) {

								response = JSON.parse(response);

								response.forEach(function(product) {

									getProduct(ajaxurl, product);

								});

							}

						});

					});
				</script-->
				
				<span class="prod-set-option outer-set-option">&nbsp;</span>
		</div>
	</div>
</div>
<?php } ?>

<?php if (htmlspecialchars($_GET["filter"]) == 'graphic') { ?>
<div id="graphic">
	<h1 class="entry-title">Our Graphic Range</h1>
	
	<div class="productcatintro swatchesintro row">
		<div class="small-12 columns nopadding">
			<p>Filter by Collection:</p>

			<?php 
			
				$collections = get_terms(array(

					'taxonomy' => 'graphic',
					'hide_empty' => false,
					'parent' => 0,

				));

				foreach($collections as $collection): ?>

					<label class="swatch-label graphic-cat-label">

						<input type="radio" name="graphic" value="<?php echo $collection->term_id; ?>">

							<span class="swatch-icon">

								<?php echo $collection->name; ?>

							</span>

						</input>

					</label>

				<?php endforeach; ?>

			<div class="sub-categories">

			</div>

			<?php
			$thefilter = htmlspecialchars($_GET["filter"]);
			$args = array( 
				'post_type' => 'texture',
				'tag' => $thefilter,
				'posts_per_page' => -1
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$postID = get_the_ID();
				$swatches = get_field('thumbnail', $postID); ?>
				<label class="swatch-label">
					<input type="checkbox" name="outer-colour" value="<?php the_title(); ?>" />
					<span class="swatch-icon"><img src="<?php echo $swatches ?>" alt="<?php the_title(); ?>" /></span>
				</label>
				<?php endwhile;	?>
				
				<span class="prod-set-option outer-set-option">&nbsp;</span>
		</div>
	</div>
</div>
<?php } ?>

<?php if (htmlspecialchars($_GET["filter"]) == 'mix-it-up' || htmlspecialchars($_GET["filter"]) == 'mix-it-up/' || htmlspecialchars($_GET["filter"]) == 'mix-n-match') { ?>

<div id="mixitup">
	<h1 class="entry-title">Our Mix It Up Range</h1>
	
	<div class="productcatintro swatchesintro row">
		<div class="small-12 columns nopadding">
			<p>Filter by Colour:</p>
			<?php
	
				$trans = 'kino_solo_colours_textures';

				$loop = get_transient($trans);

				if (false === $loop) {

					$thefilter = htmlspecialchars($_GET["filter"]);

					$args = array(

						'post_type' => 'texture',
						'tag' => $thefilter,
						'posts_per_page' => -1,

					);

					$loop = new WP_Query($args);

					set_transient($trans, $loop, WEEK_IN_SECONDS);

				}

				while ( $loop->have_posts() ) : $loop->the_post();
					$postID = get_the_ID();
					$swatches = get_field('thumbnail', $postID); ?>
				
					<label class="swatch-label">
						<input type="radio" name="outer-colour" value="<?php echo $postID; ?>" />
						<input type="hidden" name="colour_name" value="<?php the_title(); ?>" />
						<span class="swatch-icon"><img src="<?php echo $swatches ?>" alt="<?php the_title(); ?>" /></span>
					</label>

				<?php endwhile;	?>
				
				<span class="prod-set-option outer-set-option">&nbsp;</span>
			
		</div>
	</div>
</div>
<?php } ?>

<?php if ( !isset($_GET['filter'])) { ?>
	<h1 class="entry-title"><?php echo single_term_title(); ?></h1>
<?php } ?>

<script>
$.ajax({
  type: "POST",
  url: "https://www.zespoke.com/wp-admin/admin-ajax.php?action=single_colour_products",
  success: function(data) {
    console.log(data);
  }
});
</script>

<div class="products row">
	<?php
		if (isset($_GET['filter'])) {
			global $wp_query;
			$cat_param = htmlspecialchars($_GET["filter"]);
			
			if ($cat_param == "mix-it-up") {

				$cat_param = "mix-n-match";

			}

			$cat_tax = $wp_query->get_queried_object();
			?>

			<?php
			
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => 50,
				'orderby' => 'rand',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'product_options',
						'value' => $cat_param,
						'compare' => 'LIKE'
					),
				),
				//'orderby' => 'meta_value',
				//'meta_key' => '_featured',
			);

			if ($cat_tax->post_title !== "Shop") {

				$args['tax_query'] = array(

					array(
						
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $cat_tax,

					),

				);

			}

			$trans = "kino_filter_" . $cat_param . "_" . $cat_tax->term_id;

			$loop = get_transient($trans);
			$loop = false;

			if (false === $loop) {

				$loop = new WP_Query($args);

				set_transient($trans, $loop, WEEK_IN_SECONDS);

			}

		} elseif ( is_shop() ){
			$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			);

			$loop = new WP_Query($args);

		} else {
			global $wp_query;
			$cat_param = $wp_query->get_queried_object();
			$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query'     => array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug', 
					'terms'     => $cat_param
				)
			)
			);

			$loop = new WP_Query($args);

		}
		

		//$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>

	<?php $cat_tax = $wp_query->get_queried_object(); ?>
	<input type="hidden" name="category-filter" value="<?php echo $cat_tax->slug; ?>" />
</div><!--/.products-->


</div>

<?php get_footer(); ?>
