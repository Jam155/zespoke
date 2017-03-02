<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<div itemprop="offers" class="product-top-price" itemscope itemtype="http://schema.org/Offer">

	<?php
	
		$regu_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		
		$saving = (($regu_price - $sale_price) / $regu_price) * 100;
	
	?>
	
	<p data-price="<?php echo $sale_price; ?>" class="price"><span class="sale"><?php echo wc_price($sale_price); ?></span><span class="rrp">RRP: <?php echo wc_price($product->get_display_price($product->get_regular_price())); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span></p>

	<meta itemprop="price" content="<?php echo esc_attr( $product->get_price() ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
	
</div>
