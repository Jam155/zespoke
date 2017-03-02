
jQuery(document).ready(function(e) {

	$('.ac-row.texture article input[type=radio]').change(function() {

		selectTexture(this);

	});

	$('.ac-row.option article input[type=radio]').change(function() {

		selectOption(this);
		var price = getProductPrice();
		getProductRRP();
		getProductSavings();

	});

});

function selectTexture(option) {

	var $this = $(option);
	var $texture = $this.val();
	var thumb = $this.siblings('.swatch-icon').find('img').attr('src');
	var image = "<img src='" + thumb + "' />";
        var row = $this.closest('.ac-row');
        var label = $(row).find('label')[0];
        $(label).find('.prod-set-option').html(image + $texture);
}

function selectOption(option) {

	var $this = $(option);
	var value = $this.val();
	var img;

	if (value == 'No') {
	
		img = '<img src="https://www.zespoke.com/wp-content/themes/zespoke/imgs/customiser-options/no.png" />';

	} else {
		
		img = '<img src="https://www.zespoke.com/wp-content/themes/zespoke/imgs/customiser-options/yes.png" />';
	}
	
	var label = $this.closest('.ac-row').find('label')[0];
	$(label).find('.prod-set-option').html(img + value);
	
}

function getProductRRP() {

	var quantity = jQuery('input[name=quantity]').val();
	var productID = jQuery('input[name=add-to-cart]').val();
	var options = [];

	var input_options = jQuery('.option').find('input[type=radio]:checked');

	input_options.each(function(i) {

		var option = jQuery(this).data('option');
		var value = jQuery(this).val();

		options.push( {'id': option, 'value': value} );
		//options[option] = value;

	});

	console.log("Options");
	console.log(options);

	/** We need to add a WooCommerce URL, or, work out why the prices are different depending the urls **/
	jQuery.post('?wc-ajax=get_product_rrp',
		
		{

			//'action': 'get_product_rrp',
			'product': productID,
			'quantity': quantity,
			'product_options': options,

		}, function(response) {
			
			jQuery('.price .rrp').html(response);
			console.log("RRP: ");
			console.log(response);

		}
	);

	return options;

}

function getProductSavings() {

	var quantity = jQuery('input[name=quantity]').val();
	var productID = jQuery('input[name=add-to-cart]').val();
	var options = [];

	var input_options = jQuery('.option').find('input[type=radio]:checked');

	input_options.each(function(i) {

		var option = jQuery(this).data('option');
		var value = jQuery(this).val();

		options[option] = value;

	});

	jQuery.post(ajax.url,

		{

			'action': 'get_product_savings',
			'product': productID,
			'quantity': quantity,
			'product_options': options,

		},
		function(response) {

			console.log("Savings:");
			jQuery('.price .saving').html("Save: " + Math.floor(response) + '%');
			console.log(response);

		}
	);

}

function getProductPrice() {

	var quantity = jQuery('input[name=quantity]').val();
	var productID = jQuery('input[name=add-to-cart]').val();
	var options = [];

	var input_options = jQuery('.option').find('input[type=radio]:checked');

	input_options.each(function(i) {

		var option = jQuery(this).data('option');
		var value = jQuery(this).val();

		options.push( {'id': option, 'value': value} );
		//options[option] = value;

	});

	jQuery.post('?wc-ajax=get_product_price', 
	{
		/*'action': 'get_product_price',*/
		'product': productID,
		'quantity': quantity,
		'product_options' : options,

	},
	function(response) {
		
		jQuery('.price .sale').html(response);
		console.log(response);

	});

	return options;

}

