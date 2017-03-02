/* global wc_cart_params */
jQuery( function( $ ) {

	// wc_cart_params is required to continue, ensure the object exists
	if ( typeof wc_cart_params === 'undefined' ) {
		return false;
	}

	// Shipping calculator
	$( document ).on( 'click', '.shipping-calculator-button', function() {
		$( '.shipping-calculator-form' ).slideToggle( 'slow' );
		return false;
	}).on( 'change', 'select.shipping_method, input[name^=shipping_method]', function() {
		var shipping_methods = [];

		$( 'select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]' ).each( function() {
			shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
		});

		$( 'div.cart_totals' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			security: wc_cart_params.update_shipping_method_nonce,
			shipping_method: shipping_methods
		};

		$.post( wc_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_shipping_method' ), data, function( response ) {
			$( 'div.cart_totals' ).replaceWith( response );
			$( document.body ).trigger( 'updated_shipping_method' );
		});
	});

	$( '.shipping-calculator-form' ).hide();
});

jQuery(document).ready(function(e) {

	$(".product-quantity").find('.ze-minus, .ze-plus').find('p').on('click', function(e) {

                var $button = $(this);
                var $input = $button.closest('.quantity').find('input[type=number]');
                var name = $input.attr('name');
                var id = name.substring(5, name.length - 6);

                $input.val(function(i, value) {

                        var total = +value + (1 * $button.data('multi'));
                        jQuery.post({

                                url: ajax.url,
                                data: {

                                        'action': 'kino_update_quantity',
                                        'quantity': total,
                                        'product': id,

                                },
                                success: function(value) {

                                        price = $button.closest('.product-quantity').find('.sale .amount').html();
                                        price = Number(price.replace(/[^0-9\.]+/g, ""));
                                        price = price * total;
					refreshCart();

					/** AJAX so we can get the correct currency **/
					jQuery.post({

						url: '?wc-ajax=get_as_price',
						data: {

							'price': price,

						},
						success: function(value) {

							$button.closest('.cart_item').find('.product-subtotal .amount').html(value);
							//refreshCart();

						},

					});

                                        //$button.closest('.cart_item').find('.product-subtotal .amount').html(price.toLocaleString('en-GB', { style: 'currency', currency: 'GBP'}));

					/*jQuery.post({

						url: ajax.url,
						data: {
							'action': 'kino_get_cart_totals',

						},
						success: function(value) {


							refreshCart();

							//console.log("Totals");
							console.log(value);
							//$('div.cart_totals').replaceWith(value);
							$(document.body).trigger('updated_shipping_method');

						}
					});*/
                                }

                        });

			var shipping_methods = [];

                	/*$( 'select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]' ).each( function() {
                        	shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
                	});

                	/*$( 'div.cart_totals' ).block({
                        	message: null,
                        	overlayCSS: {
                        	        background: '#fff',
                        	        opacity: 0.6
                        	}
                	})*/

			var data = {

				security: wc_cart_params.update_shipping_method_nonce,
				//shipping_method: shipping_methods,

			}

			/*$.post(wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_cart_totals'), {}, function(response) {
				
				console.log(response);
				$('div.cart_totals').replaceWith(response);
				//$(document.body).trigger('updated_shipping_method');

			});*/

                        return total;

                });

        });



	WCCountryUpdater();

	function WCCountryUpdater() {

		$('.cart-delivery #calc_shipping_country').change(function(){


				jQuery.post({
	
					url: ajax.url,
					data: {
						'action': 'kino_update_shipping',
						'country' : $('#calc_shipping_country').val()
	
					},
					success: function(response) {

						refreshCart();

					}
				});


				
		});

	}

	function refreshCart() {
		var shipping_methods = [];

		jQuery( 'div.cart_totals' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			security: wc_cart_params.update_shipping_method_nonce,
			shipping_method: shipping_methods
		};

		jQuery.post( wc_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_shipping_method' ), data, function( response ) {
			jQuery( 'div.cart_totals' ).replaceWith( response );
			jQuery( document.body ).trigger( 'updated_shipping_method' );
		});

	}

	console.log("The Cart is Active.");

	jQuery('#pay_with_amazon img').attr('src', 'thisisanimg');

	jQuery('a.cart-amazon').on('click', function(e) {

		e.preventDefault();
		jQuery('#pay_with_amazon img').click();

	});

	jQuery('a.cart-paypal').on('click', function(e) {

		e.preventDefault();
		jQuery('#woo_pp_ec_button img').click();


	});



});
