<?php

	class KC_WC_Cart extends WC_Cart {

		public function __construct() {
			
			parent::__construct();
			add_action ('woocommerce_before_calculate_totals', array($this, 'kc_add_option_prices'));

		}

		

		public function kc_add_option_prices() {

			$custom_price = 0;

                	foreach ($this->cart_contents as $key => $value) {

                	        //Stops a fee being added to the product twice.
                	        if (!$value['data']->added_fee) {

                	                $value['data']->added_fee = true;
                        
                	                $custom_price = 0;

                	                $options = $value['options'];

                	                if (isset($options)) {

                	                        foreach($options as $option => $o_value) {

                        	                        if ($o_value == "Yes") {
                                        		
								$t_option = icl_object_id($option, 'option', false, ICL_LANGUAGE_CODE);
                        	                                $option_price = get_field('additional_price', $t_option);

								if (ICL_LANGUAGE_CODE == "usa") {

									$option_price *= 1.2;

								}

                        	                                $custom_price += $option_price;

                                	                }

                                	        }

                                	}
					
                                	$value['data']->price = (float) $value['data']->price + $custom_price;
                                	write_log("Final Price");
                                	write_log($value['data']->price);

                        	}

                	}

		}

		private function cart_item_additional_price($cart_item_key) {

			$_item = $this->get_cart_item($cart_item_key);
			$options = $_item['options'];

			$add_price = 0;

			foreach( $options as $id => $value ) {

				if ($value == 'Yes') {

					$t_option = icl_object_id($id, 'option', true, ICL_LANGUAGE_CODE);
					$add_price += get_field('additional_price', $t_option);

				}

			}

			return $add_price;

		}

		public function cart_item_rrp($cart_item_key) {

			$_item = $this->get_cart_item($cart_item_key);
			$_product = wc_get_product($_item['product_id']);

			$rrp_price = $_product->get_display_price($_product->get_regular_price());
			$add_price = $this->cart_item_additional_price($cart_item_key);
			$rrp_price += $add_price;
			
			return $rrp_price;

		}

		public function cart_item_sale($cart_item_key) {
		
			$_item = $this->get_cart_item($cart_item_key);
			$_product = wc_get_product($_item['product_id']);

			$sale_price = $_product->get_display_price();

			$add_price = $this->cart_item_additional_price($cart_item_key);
			$sale_price += $add_price;

			return $sale_price;

		}

		public function cart_item_saving($cart_item_key) {

			$rrp = $this->cart_item_rrp($cart_item_key);
			$sale = $this->cart_item_sale($cart_item_key);

			$saving = (($rrp - $sale) / $rrp) * 100;
			return (int) $saving;

		}

	}

        add_action ('woocommerce_init', 'kino_woocommerce_init');

        function kino_woocommerce_init() {

                global $woocommerce;

                if (!is_admin() || defined( 'DOING_AJAX')) {
                        $woocommerce->cart = new KC_WC_Cart();
                }

        }

        if (!function_exists('woocommerce_empty_cart')) {

                function woocommerce_empty_cart() {

                        global $woocommerce;

                        if ( !isset($woocommerce->cart) || $woocommerce->cart == '') {

                                $woocommerce->cart = new KC_WC_Cart();
                                $woocommerce->cart->empty_cart(false);
                        }

                }

        }

?>
