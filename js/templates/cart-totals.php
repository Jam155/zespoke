<script type="text/html" id="tmpl-cart-totals">

	<div class="cart_totals">

		<?php do_action('woocommerce_before_cart_totals'); ?>

		<h2><?php _e('Cart Totals', 'woocommerce'); ?></h2>

		<table cellspacing="0" class="shop_table shop_table_responsive">

			<tr class="cart-subtotal">

				<th><?php _e('Subtotal', 'woocommerce'); ?></th>
				<td data-title="<?php _e('Subtotal', 'woocommerce'); ?>">{{{data.subtotal}}}</td>

			</tr>

		</table>



</script>
