<script type="text/html" id="tmpl-sidebar">
	<?php

		global $woocommerce;
		$items = $woocommerce->cart->get_cart();

	?>
	<div class="checkout-sidebar-widget checkout-sidebar">
	<h3 class="sidebar-order-title">Order</h3>

	<table class="cartItems">

		<?php foreach($items as $item => $values): ?>

			<?php $_product = $values['data']->post; ?>

			<tr>

				<th>

					<?php echo $_product->post_title; ?>

				</th>

				<td>

					<span class="checkout-cart-item">

						<?php echo $values['quantity']; ?>

					</span>

				</td>

			</tr>

		<?php endforeach; ?>

	</table>

	<?php

		$reguprice = ''; $saleprice = ''; $quant = ''; $saving = '';

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			$reguprice += WC()->cart->cart_item_rrp($cart_item_key) * $cart_item['quantity'];
		        //$reguprice += get_cart_item_rrp($cart_item_key) * $cart_item['quantity'];
		        //$saleprice += get_cart_item_sale($cart_item_key) * $cart_item['quantity'];
			$saleprice += WC()->cart->cart_item_sale($cart_item_key) * $cart_item['quantity'];
		        $quant += $cart_item['quantity'];
						        
		}

		$saving = $saleprice - $reguprice;

	?>

	<div class="cartPrices">

		<p>

			<span>RRP</span><?php echo wc_price($reguprice); ?>

		</p>

		<p class="savings-blue">

			<span>Saving</span><?php echo wc_price($saving); ?>

		</p>

		<p class="bold">

			<span>Subtotal</span><?php wc_cart_totals_subtotal_html(); ?>

		</p>

		<?php foreach(WC()->cart->get_coupons() as $code => $coupon): ?>

			<p>

				<span><?php wc_cart_totals_coupon_label($coupon); ?></span><?php wc_cart_totals_coupon_html($coupon); ?>

			</p>

		<?php endforeach; ?>

		<!-- Shipping Stuff -->

		<p>
		<?php

			$packages = WC()->shipping->get_packages();
			$chosen_shipping = WC()->session->chosen_shipping_methods;

			foreach($packages as $i => $package) {
			
				$chosen_method = isset($chosen_shipping[$i]) ? $chosen_shipping[$i] : '';
				//var_dump($package['rates']);

				foreach($package['rates'] as $rate) {
					
					if ($rate->id == $chosen_method) {

						echo "<span>Shipping: $rate->label</span> " . wc_price($rate->cost);

					}

				}
				//wc_cart_totals_shipping_method_label($package);
				//var_dump($package);

			}


		?>
		</p>

		<p class="grandTotal">

			<span>Grand Total</span><?php wc_cart_totals_order_total_html(); ?>

		</p>

	</div>
</div>

</script>
