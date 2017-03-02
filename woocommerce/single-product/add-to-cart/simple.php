<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<?php

		$quantity = ( isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1 );

	?>

	<?php if (isset($_GET['key'])) {

		$key = $_GET['key'];

		global $woocommerce;
		$cart = $woocommerce->cart;
		$_product = $cart->get_cart_item($key);
		$quantity = $_product['quantity'];

	} ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php
	 		if ( ! $product->is_sold_individually() ) {
	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => $quantity /**( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 5 )**/
	 			) );
	 		}
	 	?>

	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<?php if (isset($_GET['key'])) {
			
			$key = $_GET['key']; 
			$default_options = array(); 
		
			global $woocommerce;
			$cart = $woocommerce->cart;
			$cart_product = $cart->get_cart_item($key);

			$options = $cart_product['options'];
			$textures = $cart_product['textures'];
			$default_options = $options;
			$default_textures = $textures;

		}

		$o_default_textures = get_field('default_textures', $product->id);

		//var_dump($o_default_textures);

		?>


		<?php $textures = get_field('textures', $product->id); ?>
		<?php //var_dump($textures); ?>
		<?php if ($textures): ?>
		<?php foreach ($textures as $i => $texture): ?>
			<input type="hidden" name="textures[<?php echo $texture['texture_values']['category']; ?>]" 
			
				<?php 
				
					if (isset($default_textures)) {

						echo 'value="' . $default_textures[$texture['texture_values']['category']] . '"';

					} else {

						echo 'value="' . $o_default_textures[$i]["texture"]->ID . '"';

					}

				?>
				
				/>

		<?php endforeach; ?>
		<?php endif; ?>

		<?php $options = get_field('options', $product->id); ?>
		<?php if ($options): ?>
		<?php foreach ($options as $option): ?>
			<input type="hidden" name="options[<?php echo $option['option']; ?>]" value="<?php echo isset($default_options[$option['option']]) ? $default_options[$option['option']] : $option['default']; ?>" />
		<?php endforeach; ?>
		<?php endif; ?>

		<input type="hidden" name="thumbnail" value="" />

		<?php if (!isset($_GET['key'])): ?>
	 		<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		<?php endif; ?>

		<?php if (isset($_GET['key'])): ?>
			
			<button type="submit" class="single_modify_product_button button alt">Update Product</button>

		<?php endif; ?>

		<script type="text/javascript">

			jQuery('button.single_modify_product_button').on('click', function(e) {
				
				e.preventDefault();
				e.stopPropagation();

				var product_id = jQuery('input[name=add-to-cart]').val();
				var quantity = jQuery('input[name=quantity]').val();
				var option_inputs = jQuery('.ac-row.option input:checked');
				var texture_inputs = jQuery('.ac-row.texture article input:checked');
				
				if (document.getElementById('customiserCanvas')) {

					var thumbnail = document.getElementById('customiserCanvas').toDataURL();

				}

				var options = {};
				var textures = {};
				var $option;

				option_inputs.each(function(index) {
					
					$option = jQuery(this);
					
					if ($option.data('option')) {

						options[$option.data('option')] = $option.val();
					
					}

				});

				texture_inputs.each(function(index) {

					$texture = jQuery(this);

					textures[$texture.data('category')] = $texture.data('texture'); 

				});

				console.log(textures);

				
				jQuery.post('?wc-ajax=kino_update_product', {
					
					'action': 'kino_update_product',
					'product_id': product_id,
					'product_key': '<?php echo isset($_GET['key']) ? $_GET['key'] : '' ?>',
					'quantity': quantity,
					'options': options,
					'textures': textures,
					'thumbnail': thumbnail,

				}, function(result) {
				
					console.log(result);
					window.location = result;

				});
				//alert("Cart Modified");
				//e.preventDefault();

			});

			jQuery('button.single_add_to_cart_button').on('click', function(e) {
				
				e.preventDefault();
				e.stopPropagation();

				var product_id = jQuery('input[name=add-to-cart]').val();
				var quantity = jQuery('input[name=quantity]').val();
				var option_inputs = jQuery('.ac-row.option input:checked');
				var texture_inputs = jQuery('.ac-row.texture article input:checked');
				var thumbnail = "";

				if (document.getElementById('customiserCanvas')) {

					
					thumbnail = document.getElementById('customiserCanvas').toDataURL();

				}

				var options = {};
				var textures = {};
				var $option;

				option_inputs.each(function(index) {
					
					$option = jQuery(this);
					
					if ($option.data('option')) {

						options[$option.data('option')] = $option.val();
					
					}

				});

				texture_inputs.each(function(index) {

					$texture = jQuery(this);

					textures[$texture.data('category')] = $texture.data('texture'); 

				});

				console.log(textures);

				
				jQuery.post('?wc-ajax=kino_update_product', {
					
					'action': 'kino_update_product',
					'product_id': product_id,
					'product_key': '<?php echo isset($_GET['key']) ? $_GET['key'] : '' ?>',
					'quantity': quantity,
					'options': options,
					'textures': textures,
					'thumbnail': thumbnail,

				}, function(result) {
				
					console.log(result);
					window.location = result;

				});
				//alert("Cart Modified");
				//e.preventDefault();

			});

		</script>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
