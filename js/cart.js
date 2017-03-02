jQuery(document).ready(function() {

	console.log("Cart Ready!");

	setTimeout(function() {
	jQuery('select[name=calc_shipping_country]').on('change', function(e) {

		//jQuery('button[name=calc_shipping]').click();

	})}, 1000);

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

					$button.closest('.cart_item').find('.product-subtotal .amount').html(price.toLocaleString('en-GB', { style: 'currency', currency: 'GBP'}));
					console.log(price.toLocaleString('en-GB', { style: 'currency', currency: 'GBP'}));
					console.log(price);
					console.log(value);
				}

			})
			return total;

		});

	});

	/**jQuery('a.cart-amazon').on('click', function(e) {
		console.log("Clicked");
		e.preventDefault();
		jQuery('#pay_with_amazon').click();

	});**/

});
