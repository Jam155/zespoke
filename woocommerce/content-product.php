<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}
?>

<?php
	$classes[] = 'columns';
	$classes[] = 'small-6';
	$classes[] = 'medium-3';
	$classes[] = 'small-12';
	$classes[] = 'popular-designs';
?>
<div class="small-6 medium-3">
<a href="<?php echo get_permalink($product_id) ?>" title="<?php echo get_the_title($product_id) ?>" alt="<?php echo get_the_title($product_id) ?>" <?php post_class( $classes ); ?>>

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
	
	<p class="price"><span class="rrp">RRP: <?php echo wc_price($product->get_display_price($product->get_regular_price())); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span><span class="sale"><?php echo wc_price($sale_price); ?></span></p>
	
</a>
</div>
