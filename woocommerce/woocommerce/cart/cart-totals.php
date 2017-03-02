<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
	
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	
	<?php
		$reguprice = ''; $saleprice = ''; $quant = ''; $saving = '';
		
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			
				//$reguprice += $_product->regular_price * $cart_item['quantity'];
				$reguprice += get_cart_item_rrp($cart_item_key) * $cart_item['quantity'];
				//$saleprice += $_product->sale_price * $cart_item['quantity'];
				$saleprice += get_cart_item_sale($cart_item_key) * $cart_item['quantity'];
				$quant += $cart_item['quantity'];
			}
		
		$saving = $saleprice - $reguprice;
		
	?>
	
	<table class="ze-cart-totals">
		<tr>
			<th>RRP</th>
			<td><?php echo wc_price($reguprice); //number_format($reguprice, 2, '.', '') ?></td>
		</tr>
		<tr class="savings-blue">
			<th>SAVINGS</th>
			<td><?php echo wc_price($saving); //setlocale(LC_MONETARY, 'en_GB.UTF-8'); echo money_format('%n', $saving); ?></td>
		</tr>
		<tr class="ze-subtotal">
			<th>Subtotal</th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php endif; ?>
	</table>
	<p class="cart-shipping-description">Standard shipping will be dispatched in 2-3 weeks, "Quick Ship It" items will be dispatched within 1 week.</p>
	<table class="ze-cart-totals">
		<tr class="ze-grandtotal">
			<th>Grand Total</th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>
	</table>

	<div class="cart-payment-options">
		<img src="<?php echo get_template_directory_uri(); ?>/imgs/cart-card-icons.png" />
	</div>

	<!--div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		<?php do_action('woocommerce_after_cart_totals'); ?>
	</div-->
	
</div>
