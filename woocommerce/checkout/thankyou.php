<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $order ) : ?>
<div class="row ze-multicart-head order-thankyou-head">
	<div class="small-12 medium-6 columns nopadding">
		<h1 class="entry-title">Order Confirmation</h1>
	</div>
		<ul id="jcmc-tabs" class="jcmc-tabs ze-cart-multistep-wrapper small-12 medium-6 columns nopadding  jcmc-tabs-default jcmc-blocks jcmc-sm">
			<li class="jcmc-odd">
				<div>
					<span class="jcmc-tab-span">
						<span class="jcmc-number"><i class="fa fa-check" aria-hidden="true"></i></span>Delivery
					</span>
				</div>
			</li>
			<li class="jcmc-even">
				<div>
					<span class="jcmc-tab-span">
						<span class="jcmc-number"><i class="fa fa-check" aria-hidden="true"></i></span>Payment
					</span>
				</div>
			</li>
			<li class="jcmc-odd">
				<div>
					<span class="jcmc-tab-span">
						<span class="jcmc-number"><i class="fa fa-check" aria-hidden="true"></i></span>Complete
					</span>
				</div>
			</li>
		</ul>
</div>

<?php $billing_email = get_post_meta($order->id,'_billing_email',true); ?>
		
<div class="row order-confirmation-wrap">
	<div class="small-12 medium-8 columns left">
		<h2 class="fancy">Thank You</h2>
		<p class="fancy">For shopping at Zespoke, we realy appriciate your order</p>
		
		<div class="order-thanks">
			<p>Your order number is <?php echo $order->get_order_number(); ?></p>
			<p>You will recieve an email confirmation shortly at <?php echo $billing_email."<br>"; ?></p>
			
			<p class="padded">Should you have any query regarding your order, please contact customer services at <a href="mailto:sales@zespoke.com">sales@zespoke.com</a> or call us at <a href="tel:0800 170 1077">0800 170 1077</a>.</p>
			
			<p class="padded">Why not tell your friends about your experience with Zespoke and as a thank you recieve 10% OFF your next purchase (use code SHARE10 at the checkout).</p>
			
			<ul class="social-icons">
				<li><a href="https://www.facebook.com/Zespoke" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i><span class="screen-reader-text">Facebook</span></a></li>
				<li><a href="https://twitter.com/Zespoke" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i><span class="screen-reader-text">Twitter</span></a></li>
				<li><a href="https://plus.google.com/+zespokedotcom" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i><span class="screen-reader-text">Google+</span></a></li>
				<li><a href="https://www.instagram.com/zespoke/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i><span class="screen-reader-text">Instagram</span></a></li>
				<li><a href="https://www.flickr.com/photos/zespoke" target="_blank"><i class="fa fa-flickr" aria-hidden="true"></i><span class="screen-reader-text">Flickr</span></a></li>
				<li><a href="https://uk.pinterest.com/zespoke" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i><span class="screen-reader-text">Pinterest</span></a></li>
			</ul>

			<p>If you want to keep up to date with Zespoke and make it quicker to order, you can create an account here...</p>
		</div>
		
		<div class="thanks-buttons">
			<a href="<?php echo home_url(); ?>/my-account" class="button create-acc">Create an Account</a>
			<a href="<?php echo home_url(); ?>" class="button continue">Continue Shopping</a>
		</div>
	
		<?php $order2 = new WC_Order( $order->id ); ?>
		<?php $order_item = $order2->get_items(); ?>
		

	</div>
	<div class="small-12 medium-4 columns right">
	<?php
		echo '<table class="thanksItems">';
		$reguprice = ''; $saleprice = ''; $quant = ''; $saving = '';
		foreach( $order_item as $product => $values ) {
			
			echo "<tr><th>".$values['name'].'</th><td><span class="checkout-cart-item">'.$values['qty'].'</span></td></tr>';
			
			$getregprice = get_post_meta( $values['product_id'], '_regular_price');
			$reguprice += $getregprice[0] * $values['qty'];
			$saleprice += $values['line_total'];
			
		}
		
		echo "</table>";
		
		echo '<div class="cartPrices">';
		$saving = $saleprice - $reguprice;
		echo "<p><span>RRP</span>&pound;".number_format($reguprice, 2, '.', '')."</p>";
		setlocale(LC_MONETARY, 'en_GB.UTF-8');
		echo "<p><span>Saving</span>". money_format('%n', $saving) ."</p>";
		echo '<p class="bold"><span>Subtotal</span>&pound;'.number_format($saleprice, 2, ".", "").'</p>';
		$shippingCost = $order2->get_shipping_methods();

		foreach($shippingCost as $shipping ) {
			echo "<p><span>Delivery</span>".money_format('%n', $shipping['item_meta']['cost'][0])."</p>";
		}
		
		echo '<p class="grandTotal"><span>Grand Total</span>'.money_format('%n', $order2->get_total()).'</p>';
		echo "</div>";

	?>
	</div>
</div>
	<?php if ( $order->has_status( 'failed' ) ) : ?>
		<p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

	<?php endif; ?>


<?php else : ?>


<?php endif; ?>
