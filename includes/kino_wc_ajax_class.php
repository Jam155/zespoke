<?php

	/** This should be interesting... **/
	class Kino_WC_AJAX extends WC_AJAX {

		/** Add in the AJAX Handlers **/
		public static function init() {

			add_action('init', array(__CLASS__, 'define_ajax'), 0);
			add_action('template_redirect', array(__CLASS__, 'do_wc_ajax'), 0);
			self::add_ajax_events();

		}

		/** Get WC AJAX Endpoints **/
		public static function get_endpoint($request = '') {

			return esc_url_raw(add_query_arg('wc-ajax', $request, remove_query_arg(array('remove_item', 'add-to-cart', 'added-to-cart'))));

		}

		/** Set WC AJAX Constant and Headers **/
		public static function define_ajax() {

			if (!empty($_GET['wc-ajax'])) {

				define('DOING_AJAX', true);

			}

			if (!defined('WC_DOING_AJAX')) {

				define('WC_DOING_AJAX', true);

			}

			if (!WP_DEBUG || (WP_DEBUG && ! WP_DEBUG_DISPLAY)) {

				@ini_set('display_errors', 0);

			}

			$GLOBALS['wpdb']->hide_errors();

		}

		/** Send Headers for WC AJAX Requests **/
		private static function wc_ajax_headers() {

			send_origin_headers();
			@header('Content-Type: text/html; charset=' . get_option('blog_charset'));
			@header('X-Robots-Tag: noindex');
			send_nosniff_header();
			nocache_headers();
			status_header(200);

		}

		/** Check for WC AJAX request and fire action **/
		public static function do_wc_ajax() {
			
			global $wp_query;
			if (!empty($_GET['wc-ajax'])) {
				$wp_query->set('wc-ajax', sanitize_text_field($_GET['wc-ajax']));
			}
			if ($action = $wp_query->get('wc-ajax')) {
				self::wc_ajax_headers();
				do_action('wc_ajax_' . sanitize_text_field($action));
				die();
			}
		}

		/** Add Custom AJAX events **/
		public static function add_ajax_events() {

			$ajax_events = array(
				
				'test_wc_ajax' => true,
				'get_product_rrp' => true,
				'get_product_price' => true,
				'get_as_price' => true,
				'single_product_template' => true,
				'kino_update_product' => true,
			);

			foreach($ajax_events as $ajax_event => $nopriv) {

				add_action('wp_ajax_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));

				if ($nopriv) {

					add_action('wp_ajax_nopriv_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));
					add_action('wc_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));

				}

			}

		}

		public static function get_product_price() {

			$product_id = $_POST['product'];
			$quantity = $_POST['quantity'];
			$options = $_POST['product_options'];

			$_product = wc_get_product($product_id);
			$sale_price = $_product->get_display_price();

			foreach($options as $option) {

				if ($option['value'] == 'Yes') {

					$add_price = get_field('additional_price', $option['id']);
					$sale_price += $add_price;

				}

			}

			echo wc_price($sale_price * $quantity);
			wp_die();

		}
		
		public static function get_product_rrp() {

			$product_id = $_POST['product'];
			$quantity = $_POST['quantity'];
			$options = $_POST['product_options'];

			$_product = wc_get_product($product_id);
			$rrp_price = $_product->get_display_price($_product->get_regular_price());

			//echo json_encode($options);
			//wp_die();

			foreach($options as $option) {

				if ($option['value'] == 'Yes') {

					$add_price = get_field('additional_price', $option['id']);
					$rrp_price += $add_price;

				}

			}

			echo wc_price($rrp_price * $quantity);
			wp_die();

		}

		public static function get_as_price() {

			$price = $_POST['price'];

			echo wc_price($price);
			wp_die();

		}

		public static function single_product_template() {

			$_pf = new WC_Product_Factory();
			$id = $_POST['product_id'];
			$the_product = $_pf->get_product($id);

			$product = array(

				'title' => get_the_title($id),
				'url' => get_the_permalink($id),
				'customise' => $the_product->get_attribute('customisable'),
				'image' => $the_product->get_image('full'),
				'rrp' => wc_price($the_product->get_regular_price()),
				'sale' => wc_price($the_product->get_sale_price()),
				'saving' => calculate_saving($the_product),

			);

			echo json_encode($product);
			wp_die();

		}

		public static function kino_update_product() {

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
			$new_key = $cart->add_to_cart($product_id, $quantity, 0, array(), $product);

			echo $cart->get_cart_url();
			wp_die();

		}

		public static function test_wc_ajax() {

			$test = "This is an ajax response";
			die(json_encode($test));

		}

	}

	$custom_wc_ajax = new Kino_WC_AJAX();
	$custom_wc_ajax->init();


?>
