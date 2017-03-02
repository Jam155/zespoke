<?php

function search($array, $key, $value) {

                $results = array();

                if (is_array($array)) {

                        if (isset($array[$key]) && $array[$key] == $value) {

                                $results[] = $array;

                        }

                        foreach ($array as $subarray) {

                                $results = array_merge($results, search($subarray, $key, $value));

                        }

                }

                return $results;

	}

function kino_update_quantity() {

	//define('WOOCOMMERCE_CART', true);
	global $woocommerce;
	$cart = $woocommerce->cart;
	
	$value = $_POST['quantity'];
	$product = $_POST['product'];
	
	$cart->set_quantity($product, $value, true);
	define('WOOCOMMERCE_CART', true);
	$cart->calculate_totals();
	wp_die();

}

function kino_delete_product() {

	global $woocommerce;
	$cart = $woocommerce->cart;

	$product_key = $_POST['product_key'];

	$cart->remove_cart_item($key);

}

add_action('wp_ajax_kino_delete_product', 'kino_delete_product');
add_action('wp_ajax_nopriv_kino_delete_product', 'kino_delete_product');

function kino_update_product() {

	global $woocommerce;
	$cart = $woocommerce->cart;

	$product_key = $_POST['product_key'];
	$product_id = $_POST['product_id'];
	$quantity = $_POST['quantity'];
	$options = $_POST['options'];
	$textures = $_POST['textures'];
	$thumbnail = $_POST['thumbnail'];

	$product = $cart->get_cart_item($product_key);

	$product['options'] = $options;
	$product['textures'] = $textures;
	$product['quantity'] = $quantity;
	$product['thumbnail'] = $thumbnail;

	$cart->remove_cart_item($product_key);
	$new_key = $cart->add_to_cart($product_id, $quantity,  0, array(), $product);
	
	echo $cart->get_cart_url();
	wp_die();

}

add_action('wp_ajax_kino_update_product', 'kino_update_product');
add_action('wp_ajax_nopriv_kino_update_product', 'kino_update_product');

add_action( 'wp_ajax_kino_update_quantity', 'kino_update_quantity');
add_action( 'wp_ajax_nopriv_kino_update_quantity', 'kino_update_quantity');

function override_woo_frontend_scripts() {

	wp_deregister_script('wc-cart');
	wp_enqueue_script('wc-cart', get_template_directory_uri() . '/js/woocommerce/cart.min.js', array('jquery', 'wc-country-select', 'wc-address-i18n'), null, true);
	wp_localize_script('wc-cart', 'ajax', array('url' => admin_url('admin-ajax.php')));

	wp_deregister_script('wc-add-to-cart');
	wp_enqueue_script('wc-add-to-cart', get_template_directory_uri() . '/js/woocommerce/add-to-cart.js', array('jquery'), false, true);

	wp_deregister_script('wc-checkout');
	wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/js/woocommerce/checkout.js', array('jquery'), false, true);

}

add_action('wp_enqueue_scripts', 'override_woo_frontend_scripts');


/*
 * Update shipping methods when country has been changed
 */
function kino_update_shipping() {


	if(!isset($_POST['country']))
		die();

	$country  = wc_clean( $_POST['country'] );
	$state    = wc_clean( isset( $_POST['calc_shipping_state'] ) ? $_POST['calc_shipping_state'] : '' );
	$postcode = apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ? wc_clean( $_POST['calc_shipping_postcode'] ) : '';
	$city     = apply_filters( 'woocommerce_shipping_calculator_enable_city', false ) ? wc_clean( $_POST['calc_shipping_city'] ) : '';

	if ( $country ) {
		WC()->customer->set_location( $country, $state, $postcode, $city );
		WC()->customer->set_shipping_location( $country, $state, $postcode, $city );
	} else {
		WC()->customer->set_to_base();
		WC()->customer->set_shipping_to_base();
	}


	die();

}

add_action('wp_ajax_kino_update_shipping', 'kino_update_shipping');
add_action('wp_ajax_nopriv_kino_update_shipping', 'kino_update_shipping');
?>
