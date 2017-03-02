<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table shop_table_responsive cart" cellspacing="0">
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		//var_dump(WC()->cart->get_cart());
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-thumbnail">
						<?php
							
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if (isset($cart_item['thumbnail']) && $cart_item['thumbnail'] !== "") {

								$thumbnail = "<img width='530' height='397' src='" . $cart_item['thumbnail'] . "' class='attachment-full size-full wp-post-image' />";

							}

							if ( ! $_product->is_visible() ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s?key=%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $cart_item_key, $thumbnail );
							}
							
						?>
					</td><!--

					--><td class="product-name" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
						<?php
							if ( ! $_product->is_visible() ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s?key=%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $cart_item_key, $_product->get_title() ), $cart_item, $cart_item_key );
							}
							
						?>
						
						<span class="cart-responsive-remove-btn">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
						</span>
							
							<table class="cart-prod-options">
								<?php if ($cart_item['textures']): ?>

									<?php foreach($cart_item['textures'] as $name => $value): ?>

										<?php $term = get_term($name); ?>
										<tr>
											<th><?php echo $term->name; ?></th>
											<td><img src="<?php echo get_field('thumbnail', $value); ?>" />&nbsp;<?php echo get_the_title($value); ?></th>

										</tr>

									<?php endforeach; ?>

								<?php endif; ?>

								<?php if ($cart_item['options']): ?>

									<?php foreach($cart_item['options'] as $name => $value): ?>

										<?php $option = get_post($name); ?>
										<?php $val_field = get_field(strtolower($value), $name); ?>
										<?php //$add_price = get_field('additional_price', $name); ?>

										<tr>

											<th><?php echo $option->post_title; ?></th>
											<td><img src="<?php echo $val_field; ?>" />&nbsp;<?php echo $value; ?></th>

										</tr>

									<?php endforeach; ?>

								<?php endif; ?>
							</table>
							
						<?php 

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
							}
						?>
					</td>

					<td class="product-quantity" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">
						<div class="cart-responsive-price-left">
						<h4>Unit Price</h4>
						<?php
						
						?>

							<?php $cartrrp = get_cart_item_rrp($cart_item_key); ?>
							<?php $cartsale = get_cart_item_sale($cart_item_key); ?>
							<?php $saving = get_cart_item_saving($cart_item_key); ?>

							<p class="price"><span class="rrp">RRP: <?php echo woocommerce_price( $cartrrp ); ?></span> <span class="saving">Save <?php echo (int) $saving; ?>%</span></p>
							<p class="price"><span class="sale"><?php echo woocommerce_price( $cartsale ); ?></span></p>
						</div>

						<div class="cart-responsive-quantity-right">
							<h4 class="qty-title">Quantity</h4>
		
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0',
									'class_name' => $cart_item_key
								), $_product, false );
							}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
						</div>
					</td>

					<td class="product-subtotal" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
						<h4>Subtotal</h4>
						<?php
							echo /*apply_filters( 'woocommerce_cart_item_subtotal', */WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );/*, $cart_item, $cart_item_key );	*/
						?>

					</td>
					<td class="product-remove hide-for-small">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions update-cart-row">

				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update Basket', 'woocommerce' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>


		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals row">
	<div class="small-12 medium-12 large-12 columns cart-delivery">
		<div class="row nopadding">
			<div class="small-12 medium-6 large-3 columns">
				<strong>DELIVERY - Choose a country*</strong>
				<p>UK and Ireland FREE Delivery</p>
			</div>
			<div class="small-12 medium-6 large-5 columns">
				<?php woocommerce_shipping_calculator(); ?>
			</div>
			<div class="medium-12 large-4 columns delivery-extra-info">
				<p>Don't worry we will contact you with your tracking details as soon as your order is ready to ensure a stress free delivery.</p>
			</div>
		</div>
	</div>
	<div class="small-12 medium-6 large-3 columns cart-promo">
		<?php if ( wc_coupons_enabled() ) { ?>
			<strong><a class="show-promo">Do you have a promotional code? <i class="fa fa-chevron-right" aria-hidden="true"></i></a></strong>
			
			<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post" class="promo-form">
			
			<script>
				$(document).ready(function() {
					$(".promo-form").hide();			
					
					$(".show-promo").click(function() {
							$(".promo-form").toggle();
							$(this).find('i').toggleClass('fa-rotate-90');
					});
				});
			</script>

			<table class="shop_table cart" cellspacing="0">
				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<tr>
						<td colspan="6" class="actions">

							<?php if ( WC()->cart->coupons_enabled() ) { ?>
								<div class="coupon">

									<label for="coupon_code"><?php _e( 'Coupon', 'woocommerce' ); ?>:</label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Enter Promo Code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply', 'woocommerce' ); ?>" />

									<?php do_action('woocommerce_cart_coupon'); ?>

								</div>
							<?php } ?>

							<?php wp_nonce_field( 'woocommerce-cart' ); ?>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>

			</form>
		<?php } ?>		
	</div>
	<div class="small-12 show-for-large medium-6 large-5 columns"></div>
	<div class="small-12 medium-6 large-4 columns show-cart-totals">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		<div class="wc-proceed-to-checkout">
        		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
        		<?php do_action('woocommerce_after_cart_totals'); ?>
		</div>
	</div>
</div>
