<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('row'); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		/* do_action( 'woocommerce_before_single_product_summary' ); */
	?>
	
	<div class="prodtoprow row">
		<div class="small-12 medium-6 columns">
			<h1 class="entry-title product-title"><?php echo get_the_title() ?></h1>
		</div>
		<div class="small-12 medium-6 columns">
			<div class="pricerow row">
				<div class="left small-12 medium-6 columns">
					<?php do_action( 'zespoke_product_price' ); ?>
				</div>
				<div class="right small-12 medium-6 columns">
					<p><?php echo get_field('delivery', 'options'); ?></p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row customiser-wrapper">
	
	<div class="small-12 medium-6 product-left">

		<?php if (current_user_can('edit_post')): ?>
		<button name="featured_image">Use Image as Featured</button>
		<?php endif; ?>
		
		<div class="customiser" id="customiser">
			
			<?php if (!is_null(get_field('model_post')) && !get_field('model_post')): ?>

				<div class="loader"></div>
				<div class="overlay hidden">
					<img src="<?php echo get_template_directory_uri() . "/imgs/360_Transparent.png" ?>"/>
				</div>
				
				<div class="top-controls left hidden">

					<button name="random">Inspire Me</button>

				</div>

				<div class="top-controls right hidden">

					<div class="zoom-in"><i class="fa fa-search-plus" aria-hidden="true"></i></div>
					<div class="zoom-out"><i class="fa fa-search-minus" aria-hidden="true"></i></div>

				</div>

				<div class="notification">

					<p>Drag to Rotate</p>

				</div>

			<?php endif; ?>

		</div>

		<div class="main-image hidden">

			<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>

		</div>

		<input type="hidden" value="<?php echo get_the_ID(); ?>" name="product" />
		<input type="hidden" value="<?php the_field('model', $model); ?>" name="object_file" />
		<input type="hidden" value="<?php the_field('materials', $model); ?>" name="materials_file" />
		<input type="hidden" value="<?php the_field('scale', $model); ?>" name="scale" />
		<input type="hidden" value="<?php the_field('rotation', $model); ?>" name="rotation" />
		<input type="hidden" value="<?php the_field('translation_y', $model); ?>" name="translation_y" />
		<input type="hidden" value="<?php the_field('translation_x', $model); ?>" name="translation_x" />

		<script>

			jQuery(document).ready(function(e) {

				//setTimeout(function() {
				init(125);
				//}, 1000);
				
			});
		</script>
	</div>

	<div class="small-12 medium-6 row product-right">

		<div class="row">
			<div class="small-6">
				<h3 class="prod-customiser-title fancy">Customise It!</h3>
			</div>

			<div class="small-6">
				<script>

					jQuery(document).ready(function(e) {

						jQuery("button[name=random]").on('click', function(e) {

							var sides = jQuery(".ac-row.texture");
							
							sides.each(function(index) {

								var textures = jQuery(this).find('.swatch-label');
								var random = Math.floor(Math.random() * textures.length);
								var input = jQuery(textures[random]).find('input');
								var sides = input.closest('.ac-row').data('side');
								var id = input.data('texture');

								selectTexture(input);

								input.prop('checked', true);

								console.log(sides);

								changeTexture(sides, id);

								//jQuery(textures[random]).find('input').click();
								//console.log(random);

							});
							//console.log(sides);

						});

					});

				</script>
			</div>
		</div>
		<section class="ac-container">

			<?php

				if (isset($_GET['key'])) {

					$key = $_GET['key'];

					global $woocommerce;
					$cart = $woocommerce->cart;
					$_product = $cart->get_cart_item($key);
					$_textures = $_product['textures'];

					foreach($_textures as $key => $texture) {

						$_term = get_term($key);

					}

				}

			?>

			<?php

				$textures = get_transient('textures-' . get_the_ID());

				if ($textures === false) {

					$textures = get_field('textures');
					set_transient('textures-' . get_the_ID(), $textures, WEEK_IN_SECONDS);

				}

			?>
			<?php $defaults = get_field('default_textures'); ?>
			<?php if ($textures): ?>
			<?php foreach($textures as $i => $texture_row): ?>
				<?php $side = $texture_row['side']; ?>
				<?php $the_default; ?>

				<?php
					foreach($defaults as $default) {

						if ($side == $default['side']) {

							$the_default = $default;

						}

					}
				?>
				<?php $texture_row = $texture_row['texture_values']; ?>
				<?php $category = $texture_row['category']; ?>
				<?php $tags = $texture_row[$category]; ?>
				<?php $term = get_term($category); ?>
				<div class="ac-row texture" data-side="<?php echo $side; ?>">
					<input class="prod-options-ac-ckeck" id="ac-<?php echo $i; ?>" name="accordion-1" type="checkbox">

					<?php

						$default_img = get_field('thumbnail', $the_default['texture']->ID);
						if (is_numeric($default_img)) {
							$default_img = wp_get_attachment_url($img);
						}

					?>

					<label for="ac-<?php echo $i; ?>"><?php echo $term->name; ?><span class="prod-set-option"><img src="<?php echo $default_img; ?>" /><?php echo $the_default['texture']->post_title; ?>&nbsp;</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></label>
					<article class="ac-small">
						<?php if ($tags): foreach($tags as $tag => $tag_value): ?>
							<label class="swatch-label">
								
								<input type="radio" 
								
								<?php 

									if (isset($_textures) && $_textures) {

										echo ($_textures[$term->term_id] == $tag ? 'checked="checked"' : '');

									} else {
								
										echo ($the_default['texture']->ID == $tag ? 'checked="checked"' : '');  

									}

								?>
								
								data-category="<?php echo $category;  ?>" data-texture="<?php echo $tag; ?>" name="<?php echo $term->slug; ?>" value="<?php echo get_the_title($tag); ?>" />

								<span class="swatch-icon">
									<?php $img = get_field('thumbnail', $tag);
									if (is_numeric($img)) {
										$img = wp_get_attachment_url($img);
									} ?>

									<img src="<?php echo $img; ?>" alt="<?php echo get_the_title($tag); ?>" />
								</span>
							</label>
						<?php endforeach; endif; ?>
					</article>
				</div>

			<?php endforeach; ?>
			<?php endif; ?>

			<script>

				$('.ac-small input[type=radio]').on('click', function(e) {

					console.log("Change selected value.");
					var category = $(this).data('category');
					var texture = $(this).data('texture');

					$('input[name="textures[' + category + ']"]').val(texture);
					console.log(this);

				});

			</script>
			<!-- Optional Parts -->
			
			<?php $options = get_field('options'); ?>

			<?php if ($options): ?>

			<!-- Check if key exists, if so get values -->
			<?php

				if (isset($_GET['key'])) {

					$key = $_GET['key'];

					global $woocommerce;
					$cart = $woocommerce->cart;
					$_product = $cart->get_cart_item($key);
					$_options = $_product['options'];

				}

			?>

			<?php foreach($options as $option): ?>
				<?php $option_id = icl_object_id($option['option'], 'post', true); ?>
				<?php $side = $option['side']; ?>
				<?php $default = $option['default']; ?>
				<?php $default_img = get_field(strtolower($default), $option_id); ?>
				<?php $option = $option_id; ?>
				<div class="ac-row option" data-side="<?php echo $side; ?>">
					<input class="prod-options-ac-ckeck" id="ac-<?php echo $option; ?>" name="accordion-1" type="checkbox">
					<label for="ac-<?php echo $option; ?>"><?php echo get_the_title($option); ?><span class="prod-set-option"><img src="<?php echo $default_img; ?>" /><?php echo $default; ?>&nbsp;</span><span class="price-container" data-price="<?php echo get_field('additional_price', $option); ?>"><?php echo wc_price(get_field('additional_price', $option)); ?></span> <i class="fa fa-chevron-down" aria-hidden="true"></i></label>
					<article class="ac-small">
						<!-- Yes Option -->
						<?php $enabled = get_field('yes', $option); ?>
						<label class="swatch-label">
							<input type="radio" name="<?php echo $option . '-options'; ?>" data-option="<?php echo $option; ?>" value="<?php echo 'Yes'; ?>" 
							
							<?php 
							
								if (isset($_options)) {

									if ($_options[$option] == 'Yes') {

										echo 'checked="checked"';

									}

								} else {

									echo ($option['default'] == 'Yes' ? 'checked="checked"' : ''); 
								
								} ?>
							
							/>
							<span class="swatch-icon"><img src="<?php echo $enabled; ?>" alt="<?php echo 'Yes'; ?>"/></span>
						</label>

						<!-- No Option -->
						<?php $disabled = get_field('no', $option); ?>
						<label class="swatch-label">
							<input type="radio" name="<?php echo $option . '-options'; ?>" data-option="<?php echo $option; ?>" value="<?php echo 'No'; ?>" 
							
							<?php 
								
								if (isset($_options)) {

									if ($_options[$option] == 'No' || $_options[$option] == '') {

										echo 'checked="checked"';

									}

								} else {
									
									echo ($option['default'] == 'No' ? '' : 'checked="checked"'); 
									
								} ?> 
								
							/>
							<span class="swatch-icon"><img src="<?php echo $disabled; ?>" alt="<?php echo 'No'; ?>" /></span>
						</label>
					</article>
				</div>

			<?php endforeach; ?>

			<?php endif; ?>
			<?php wp_reset_query(); ?>
			
			<script>				
				$(document).ready(function() {
					
					$('.prod-options-ac-ckeck').first().attr('checked', true);
					
					$('.prod-options-ac-ckeck').click(function(){
						if ($(this).is(':checked')) {
							$('.prod-options-ac-ckeck').attr('checked', false);
							$(this).attr('checked', true);
							$('.ac-row').find('i').removeClass( 'fa-rotate-180' );
							$(this).parent().find('i').toggleClass( 'fa-rotate-180' );					
						} else {
							$('.prod-options-ac-ckeck').attr('checked', false);
							$(this).parent().find('i').removeClass( "fa-rotate-180" );
						}
					});

					$('.option input[type=radio]').on('click', function(e) {

						var option = $(this).data('option');
						var value = $(this).val();
						
						if (option) {

							$('input[name="options[' + option + ']"]').val(value);

						}

						var row = $(this).closest('.ac-row');
						var amount = row.find('.amount');
						var price = row.find('.price-container').data('price');

						if (option && value == 'Yes') {
							
							amount.addClass('visible');
							console.log(price);

						} else if (option && value == 'No') {
							
							amount.removeClass('visible');

						}

					});
					
				});
			</script>

			<!-- End of Optional Parts -->

		</section>
		
		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->
	
	</div>
	
	<div class="banner row">

		<?php $banner = get_field('banner', 169); 
		
			$header = get_field('header', $banner->ID);
			$button = get_field('button_text', $banner->ID);
			$post = get_field('post', $banner->ID);
		
		?>

		<div class="small-12 columns nopadding">
		
			<div class="the_banner">
			
				<div class="content">
				
					<?php echo $header; ?><a class="button" href="<?php echo get_the_permalink($post); ?>"><?php echo $button; ?></a>
			
				</div>
			
			</div>
		
		</div>
		
		<?php wp_reset_query(); ?>

	</div>
	
	<div class="row description-image">
		<div class="small-12 medium-7">
			<?php echo the_content(); ?>
		</div>
		<div class="small-12 medium-5 product-carousel">
			<?php
			global $product;
			
			$attachment_ids = get_transient('product-gallery-' . get_the_ID());

			if ($attachment_ids === false) {

				$attachment_ids = $product->get_gallery_attachment_ids();
				set_transient('product-gallery-' . get_the_ID(), $attachment_ids, WEEK_IN_SECONDS);

			}


			//$attachment_ids = $product->get_gallery_attachment_ids();

			foreach( $attachment_ids as $attachment_id ) 
			{
				$image_link = wp_get_attachment_url( $attachment_id ); ?>
				<div class="small-12 columns"><img src="<?php echo $image_link ?>" /></div>
			<?php } ?>
	
		</div>
		
	</div>
	
	<div class="row product-reviews">
		<script type="text/javascript">
		  _tsRatingConfig = {
			tsid: 'X7C65A861F9B1F19720DDF7B32D36DEE6',
			variant: 'vertical', 
			theme: 'light',
			introtext: "<span class='fancy'>Real</span> Customer Reviews",
			reviews: 4, 
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
	
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
