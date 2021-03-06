<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<footer>

	<?php if( is_wc_endpoint_url('order-received') || is_single() || is_home() || is_page('customer-gallery') || is_checkout() ) {
		
	} else { ?>
	<div class="popular-designs row">

		<div class="small-12 columns nopadding">
		
			<h2>Popular Designs this week</h2>
		
		</div>
		
		<?php
		
			$products = get_transient('kino_popular_designs');

			if (false === $products) {

				$query_args = array(

					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page' => 4,
					'meta_key' => 'total_sales',
					'orderby' => 'meta_value_num',
					'meta_query' => WC()->query->get_meta_query(),
					'post__not_in' => array(117392, 117263),

				);

				$products = new WP_Query($query_args);

				if (!is_admin()) {

					set_transient('kino_popular_designs', $products, DAY_IN_SECONDS);

				}

			}

			if ($products->have_posts()): ?>
			
				<?php while ($products->have_posts()): $products->the_post(); ?>
				
					<?php $product = new WC_Product(get_the_ID()); ?>
					<a href="<?php echo get_permalink($product_id) ?>" title="<?php echo get_the_title($product_id) ?>" alt="<?php echo get_the_title($product_id) ?>" class="small-12 medium-6 large-3 columns">
					
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
						
							$regu_price = $product->get_display_price($product->get_regular_price());
							$sale_price = $product->get_display_price();
							
							$saving = (($regu_price - $sale_price) / $regu_price) * 100;
						
						?>

						<p class="price"><span class="rrp">RRP: <?php echo wc_price($product->get_display_price($product->get_regular_price())); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span><span class="sale"><?php echo wc_price($product->get_display_price()); ?></span></p>
											
					</a>
				
				<?php endwhile; ?>
				
			<?php endif; ?>
			
			<?php wp_reset_query(); ?>

	</div>
	<?php } ?>
	

	<?php if( is_product() ) { ?>

	<div class="popular-designs row related-prod-row">

		<div class="small-12 columns nopadding">
		
			<h2>You may also like</h2>
		
		</div>
		<?php
		
		global $product;

		$trans = 'kino_related_products_' . $product->get_id();
		
		$products = get_transient($trans);

		if (false === $products) {

			$related = $product->get_related(6);

			$args = apply_filters(

				'woocommerce_related_products_args',
				array(

					'post_type' => 'product',
					'ignore_sticky_posts' => 1,
					'no_found_rows' => 1,
					'posts_per_page' => 4,
					'orderby' => 'rand',
					'post__in' => $related,
					'post__not_in' => array($product->id),

				)

			);

			$products = new WP_Query($args);

			set_transient($trans, $products, WEEK_IN_SECONDS);

		}

		if ( $products->have_posts() ) : ?>

				<?php woocommerce_product_loop_start(); ?>

					<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php $product_id = get_the_ID(); ?>
					<?php $product = new WC_Product(get_the_ID()); ?>
					<a href="<?php echo get_permalink($product_id) ?>" title="<?php echo get_the_title($product_id) ?>" alt="<?php echo get_the_title($product_id) ?>" class="small-12 medium-6 large-3 columns">
					
						<div class="image">
						
							<?php $customise = $product->get_attribute('customisable'); ?>
							
							<?php if ($customise === "true"): ?>
								
								<span>Customise It</span>
								<img class="customise_logo" src="<?php echo get_template_directory_uri(); ?>/imgs/customise-logo.png" />
							
							<?php endif; ?>
						
							<?php echo $product->get_image('full'); ?>
						
						</div>
						
						<h2><?php the_title(); ?></h2>
						
						<?php
						
							$regu_price = $product->get_display_price($product->get_regular_price());
							$sale_price = $product->get_display_price();
							
							$saving = (($regu_price - $sale_price) / $regu_price) * 100;
						
						?>
						
						<p class="price"><span class="rrp">RRP: <?php echo wc_price($product->get_display_price($product->get_regular_price())); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span><span class="sale"><?php echo wc_price($sale_price); ?></span></p>
											
					</a>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>

		<?php endif;
		wp_reset_postdata();
		?>
	</div>
	<?php } ?>
	

	<?php if( !is_cart() && !is_checkout() && !is_wc_endpoint_url('order-received') && !is_single() && !is_wc_endpoint_url() && !is_home() && !is_page('customer-gallery') ) { ?>
		
	<div class="banner row">

		<?php $banner = get_field('banner', 169); 
		
			$header = get_field('header', $banner->ID);
			$background = get_field('background_image', $banner->ID);
			$button = get_field('button_text', $banner->ID);
			$post = get_field('post', $banner->ID);
		
		?>

		<div class="small-12 columns nopadding">
		
			<div class="the_banner">
			
				<img src="<?php echo $background; ?>" class="bg"/>
				
				<div class="content">
				
					<?php echo $header; ?>
				
					<a class="button" href="<?php echo get_the_permalink($post); ?>"><?php echo $button; ?></a>
			
				</div>
			
			</div>
		
		</div>
		
		<?php wp_reset_query(); ?>

	</div>
	<?php } ?>

	
	<?php

		$reviews = get_transient('kino_reviews');
		
		if (false === $reviews) {

			$args = array(

				'posts_per_page' => 5,
				'post_type' => 'zespoke-reviews',
				'posts_per_page' => -1,

			);

			$reviews = get_posts($args);

			set_transient('kino_reviews', $reviews, WEEK_IN_SECONDS);

		}

	?>
			
	<div class="review row">
		<div class="small-12 columns nopadding ">
			<h3><span class="fancy">Real</span> Customer Reviews</h3>
			<a href="<?php echo get_permalink(659); ?>">	
			<div class="owl-review">
				<?php
				foreach ( $reviews as $review ) :
					setup_postdata( $post );
					$quote = get_field('customer_review', $review->ID);
					$name = get_field('customer_name', $review->ID);
					$location = get_field('customer_location', $review->ID);
					$feat_image = wp_get_attachment_url( get_post_thumbnail_id($review->ID) );
				?>
				<div class="owl-review-wrapper">
					<q><?php echo $quote; ?></q>
					<div class="quote-author">
						<span class="quote-name"><?php echo $name; ?></span>, <span span="quote-location"><?php echo $location; ?></span>
					</div>
					<?php /* <img class="quote-image" src="<?php echo $feat_image ?>" /> */ ?>
				</div>
				<?php endforeach;			
				wp_reset_postdata();
				?>
			</div>
			</a>
			<img class="quote-image" src="<?php echo get_bloginfo('template_directory'); ?>/imgs/reviews.jpg" />	
		</div>
	</div>

	<?php if( !is_cart() && !is_checkout() && !is_wc_endpoint_url('order-received') && !is_home() && !is_single() ) { ?>

	<div class="blog row no-border">

		<?php $posts = get_field('blog_posts', 169); ?>
		
		<?php if ($posts): ?>
		
			<?php foreach ($posts as $i => $post): ?>
			
				<?php $post = $post['post']; ?>
				
				<div class="<?php echo $i == 0 ? 'large-6 medium-4 small-12' : 'large-3 medium-4 small-12'; ?> columns post <?php echo $i == 0 ? 'main nopaddingleft' : ''; ?>">
				
				<?php

					if ($i == 0) {

						$image = get_field('image_large', $post->ID);

					} else {

						$image = get_field('image_small', $post->ID);

					}

				if (!$image) {

					$image = get_field('featured_image', $post->ID);

				}

				if (is_numeric($image)) {

					$image = wp_get_attachment_image_src($image);

				}

				if (is_array($image)) {

					$image = $image[0];

				}

				?>

				<img src="<?php echo $image; ?>" alt="<?php echo $post->post_title; ?>" title="<?php echo $post->post_title; ?>" />
			
				<div class="content">
			
					<h2><?php echo $post->post_title; ?></h2>
					
					<div class="excerpt <?php echo $i == 0 ? 'hide-for-large' : ''; ?>">
						
						<p><?php echo get_the_excerpt($post->ID); ?></p>
			
					</div>
			
					<a class="button" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">Read More</a>
					
					<p class="date"><?php echo mysql2date('F dS Y', $post->post_date); ?></p>
			
				</div>
			
			</div>
			
			<?php endforeach; ?>
		
		<?php endif; ?>
		
		<?php wp_reset_query(); ?>

	</div>
	<?php } ?>

	<div class="featured">

		<div class="inner">
		
		<div class="row">
			<div class="brand-carousel nopadding">
				<?php $brands = get_field('brand_carousel', 169); ?>
				<?php foreach($brands as $brand): ?>
					<div class="small-12 columns"><img src="<?php echo $brand["image"]; ?>" /></div>
				<?php endforeach; ?>
			</div>
		
		</div>	
		
		<?php wp_reset_query(); ?>
	
</div>
	
	</div>
	
	<div class="newsletter">
	
		<div class="inner">
		
			<div class="row">
		
				<div class="small-12 large-8 columns">
					<div class="row">
						<div class="small-12 medium-6 columns first">
							<span>Stay in the loop! Get exclusive offers</span>
						</div>
						<div class="small-12 medium-6 columns">
							<!-- Begin MailChimp Signup Form -->
							<div id="mc_embed_signup" class="">
								<form action="//zespoke.us7.list-manage.com/subscribe/post?u=03e7c5b761108f05afa421ae0&amp;id=58926baab5" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate footer-form" target="_blank" novalidate>
									<input type="email" placeholder="Enter your email address..." value="" name="EMAIL" class="input-group-field required email" id="mce-EMAIL">
									<input class="hidden" type="text" name="b_03e7c5b761108f05afa421ae0_58926baab5" tabindex="-1" value="">
									<div class="input-group-button">
										<button type="submit" class="button">Send <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
										<!--input type="submit" value="Send" name="subscribe" id="mc-embedded-subscribe" class="button"-->
									</div>
								</form>
							</div>

<!--End mc_embed_signup-->
						</div>
					</div>
				</div>
				
				<div class="small-12 large-4 columns">
			
					<div class="row">
				
						<div class="small-4 medium-4 columns follow-us-footer">
							<span>Follow Us</span>
						</div>
						
						<div class="small-8 medium-8 columns last">
							<ul class="social-icons">
								<li><a href="https://www.facebook.com/Zespoke" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i><span class="screen-reader-text">Facebook</span></a></li>
								<li><a href="https://twitter.com/Zespoke" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i><span class="screen-reader-text">Twitter</span></a></li>
								<li><a href="https://plus.google.com/+zespokedotcom" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i><span class="screen-reader-text">Google+</span></a></li>
								<li><a href="https://www.instagram.com/zespoke/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i><span class="screen-reader-text">Instagram</span></a></li>
								<li><a href="https://www.flickr.com/photos/zespoke" target="_blank"><i class="fa fa-flickr" aria-hidden="true"></i><span class="screen-reader-text">Flickr</span></a></li>
								<li><a href="https://uk.pinterest.com/zespoke" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i><span class="screen-reader-text">Pinterest</span></a></li>
							</ul>
						</div>
					</div>
			
				</div>
			
			</div>
		
		</div>
	
	</div>
	
	<div class="footer">
	
		<div class="inner">
		
			<div class="row">
			
				<div class="large-2 medium-4 small-12 small-order-3 medium-order-1 show-for-large columns nopadding">
				
					<h2>About Zespoke</h2>
					
					<ul>
				
						<!-- About Zespoke -->
						<li><a href="<?php echo get_permalink(603); ?>">About</a></li>
						<li><a href="<?php echo get_permalink(605); ?>">Handmade in the UK</a></li>
						<li><a href="<?php echo get_permalink(240); ?>">Blog</a></li>
						<li><a href="<?php echo get_permalink(607); ?>">Privacy Policy</a></li>
						<li><a href="<?php echo get_permalink(111); ?>">Frequently Asked Questions</a></li>
						<li><a href="<?php echo get_permalink(442); ?>">Contact Us</a></li>
						
					</ul>
				
				</div>
				
				<div class="large-2 medium-4 small-12 small-order-4 medium-order-2 show-for-large columns nopadding">
				
					<h2>Our Products</h2>
					
					<ul>
					
						<li><a href="<?php echo get_term_link(7, 'product_cat'); ?>">Coffee Tables</a></li>
						<li><a href="<?php echo get_term_link(8, 'product_cat'); ?>">Console Tables</a></li>
						<li><a href="<?php echo get_term_link(6, 'product_cat'); ?>">Side Tables</a></li>
						<li><a href="<?php echo get_term_link(9, 'product_cat'); ?>">TV Stands</a></li>
						<li><a href="<?php echo get_permalink(109); ?>">Customer Gallery</a></li>
					
					</ul>
				
				</div>
				
				<div class="large-2 medium-4 small-12 small-order-5 medium-order-3 show-for-large columns nopadding">
					<h2>Customer Services</h2>
					<ul>
						<li><a href="<?php echo get_permalink(27); ?>">My Account</a></li>
						<li><a href="<?php echo get_permalink(27); ?>">Order History</a></li>
						<li><a href="<?php echo get_permalink(609); ?>">Delivery Information</a></li>
						<li><a href="<?php echo get_permalink(611); ?>">Product Care</a></li>
						<li><a href="<?php echo get_permalink(613); ?>">Terms & Conditions</a></li>
					</ul>
				</div>
				
				<div class="large-2 medium-4 small-12 small-order-1 medium-order-1 large-order-4 columns nopadding">
					<ul class="footer-contact-info">
						<li>0800 170 1077</li>
						<li>0044 2886764647</li>
						<li><?php echo antispambot('sales@zespoke.com'); ?></li>
					</ul>
					<ul class="payment-icons">
						<li><span class="screen-reader-text">Mastercard</span></li>
						<li><span class="screen-reader-text">Visa</span></li>
						<li><span class="screen-reader-text">American Express</span></li>
						<li><span class="screen-reader-text">Discover</span></li>
						<li><span class="screen-reader-text">PayPal</span></li>
					</ul>

				</div>
				
				<div class="large-4 medium-4 small-12 small-order-2 medium-order-2 large-order-5 columns nopadding">
				
					<h2>Our Pricing Policy</h2>
				
					<p>Our pricing policy is to bring you the most consistently competitive prices possible for bespoke furniture. We sell directly to you, so can rest assured that you're getting your furniture at the best possible price. Due to the nature of bespoke products, exact like-for-like comparisons are impossible, but based on the closest equivalent products you can make a saving of at least 15% on your purchases with Zespoke.</p>
				
				</div>
				
				<section class="ac-container medium-4 small-order-3 small-order-3 hide-for-large">
					<div class="ac-row">
						<input id="ac-foot1" name="accordion-foot-1" type="checkbox">
						<label for="ac-foot1">About Zespoke</label>
						<article class="ac-medium">
							<ul>
							
								<li><a href="<?php echo get_permalink(603); ?>">About</a></li>
								<li><a href="<?php echo get_permalink(605); ?>">Handmade in the UK</a></li>
								<li><a href="<?php echo get_permalink(240); ?>">Blog</a></li>
								<li><a href="<?php echo get_permalink(607); ?>">Privacy Policy</a></li>
								<li><a href="<?php echo get_permalink(111); ?>">Frequently Asked Questions</a></li>
								<li><a href="<?php echo get_permalink(442); ?>">Contact Us</a></li>
								
							</ul>
						</article>
					</div>
					<div class="ac-row">
						<input id="ac-foot2" name="accordion-foot-1" type="checkbox">
						<label for="ac-foot2">Our Products</label>
						<article class="ac-medium">
							<ul>
							
								<li><a href="<?php echo get_term_link(7, 'product_cat'); ?>">Coffee Tables</a></li>
								<li><a href="<?php echo get_term_link(8, 'product_cat'); ?>">Console Tables</a></li>
								<li><a href="<?php echo get_term_link(6, 'product_cat'); ?>">Side Tables</a></li>
								<li><a href="<?php echo get_term_link(9, 'product_cat'); ?>">TV Stands</a></li>
								<li><a href="<?php echo get_permalink(109); ?>">Customer Gallery</a></li>
							
							</ul>
						</article>
					</div>
					<div class="ac-row">
						<input id="ac-foot3" name="accordion-foot-1" type="checkbox">
						<label for="ac-foot3">Customer Services</label>
						<article class="ac-medium">
							<ul>
								<li><a href="<?php echo get_permalink(27); ?>">My Account</a></li>
								<li><a href="<?php echo get_permalink(27); ?>">Order History</a></li>
								<li><a href="<?php echo get_permalink(609); ?>">Delivery Information</a></li>
								<li><a href="<?php echo get_permalink(611); ?>">Product Care</a></li>
								<li><a href="<?php echo get_permalink(613); ?>">Terms & Conditions</a></li>
							</ul>
						</article>
					</div>
				</section>
		
			
			</div>
		
		</div>
	
	</div>
	
	<div class="footer-bottom">
	
		<div class="row">
	
			<div class="medium-6 small-12 columns">
	
				Zespoke Design Ltd &copy; 2016
	
			</div>
			
			<div class="medium-6 small-12 columns">
	
				Web Design & Development by <a href="http://www.kinocreative.co.uk/" alt="Kino Creative | Busy making digital products for very happy customers" title="Kino Creative | Busy making digital products for very happy customers">Kino Creative</a>
	
			</div>
	
		</div>
		
	</div>

</footer>

<?php wp_footer(); ?>
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:25514,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- PadiAct Code -->
<script type="text/javascript">
(function() {
  var pa = document.createElement('script'), ae = document.getElementsByTagName('script')[0]
  , protocol = (('https:' == document.location.protocol) ? 'https://' : 'http://');pa.async = true;  
  pa.src = protocol + 'd2xgf76oeu9pbh.cloudfront.net/53255c24a9d1cb24fbccce3fc30dfc5a.js'; pa.type = 'text/javascript'; ae.parentNode.insertBefore(pa, ae);
})();
</script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Poppins:300,400,500,600,700|Varela|Varela+Round|Zeyada" rel="stylesheet">

</body>
</html>
