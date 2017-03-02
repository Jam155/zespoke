jQuery(document).ready(function(e) {

	jQuery('.ze-quantity').on('click', function(e) {

		console.log("Ze Quantity");
	
	});

	jQuery(".ddd").on("click", function() {
		
		var $button = $(this);
		var $input = $button.closest('.ze-quantity').find("input.quntity-input");

		$input.val(function(i, value) {
			var total = +value + (1 * +$button.data('multi'));
			return total;
		});

		getProductPrice();
		getProductRRP();
		getProductSavings();

	});

	jQuery("input[name=quantity]").on('change', function() {

		getProductPrice();
		getProductRRP();
		getProductSavings();

	});

	getProductPrice();
	getProductRRP();
	getProductSavings();

});
