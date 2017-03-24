
jQuery(document).ready(function() {

	console.log("Filter JS");

var productTemplate = wp.template('product-single');
var cat = $('input[name=category-filter]').val();

var getProducts = function(ajaxurl, action, textures, callback) {
                                                
        var data = {

                'action': /*'single_colour_products'*/ action, 
		'category': cat,
                'textures': textures,

        };

        jQuery.post(ajaxurl, data, callback);
};

var getHtml = function(data) {

	jQuery('.shopcatalogue .products').append(productTemplate(data));
	productTemplate(data);

}

var getProduct = function(ajaxurl, id) {

        var data = {

                'action': 'single_product_template',
                'product_id': id,

        }

        jQuery.post(/*ajaxurl*/ '?wc-ajax=single_product_template', data, function(response) {
		
		var product = JSON.parse(response);
		console.log(product);
		console.log(getHtml(product));
		//var product_html = productTemplate(product);
                //console.log(product_html);
		//var product = singleTemplate(response);

        });

}

/*jQuery('.flags').find('input[type=checkbox]').prop('checked', true);

jQuery('#graphic input[name=graphic]').on('click', function(e) {

	jQuery('.flags').toggleClass('hidden');


});*/


var $graphicradios = jQuery('#graphic input[name=graphic]').change(function() {

	var $radios = $graphicradios.filter(':checked');
	var categories = [];

	$radios.each(function() {

		categories.push(parseInt($(this).val()));

	});

	var data = {

		'action': 'get_graphic_subcategories',
		'categories': categories,
		//'category': cat,

	}

	jQuery('.sub-categories').empty();

	console.log(categories);

	jQuery.post(ajax, data, function(response) {

		console.log(response);
		response = JSON.parse(response);

		response.forEach(function(category) {

			var html = "<label class='swatch-label'>";
			html	+= 	'<input type="checkbox" name="outer-colour" value="' + category.term_id + '" />';
			html	+=	'<span class="swatch-icon"><img src="' + category.image + '" alt="' + category.name + '" /></span>'
			html	+= '</label>'

			jQuery('.sub-categories').append(html);

		});
		
	});

});

var $categories = jQuery('#graphic').on('change', 'input[name=outer-colour]', function() {

	var $radios = $categories.find('input[name=outer-colour]').filter(':checked');
	var ids = [];

	$radios.each(function() {

		ids.push(parseInt($(this).val()));
		console.log($(this).val());

	});

	var data = {

		'action': 'get_graphic_products',
		'category': cat,
		'graphic_categories': ids,

	}

	jQuery.post(ajax, data, function(response) {

		jQuery('.shopcatalogue .products').empty();
		response = JSON.parse(response);

		console.log(response);

		response.forEach(function(product) {
			
			getProduct(filter.url, product);

		});

	});

});

/*var $graphicradios = jQuery('#graphic input[name=outer-colour]').change(function() {

	var $radio = $graphicradios.filter(':checked');

	$radio.each(function() {

		console.log($(this).val());

	});

});*/

/*var $graphicradios = jQuery('#graphic input[name=outer-colour]').change(function() {

	var $radio = $graphicradios.filter(':checked');
	var id = [];
	
	$radio.each(function() {

		id.push($(this).val());

	});

	getProducts(filter.url, 'graphic_range_products', id, function(response) {
		
		if (response) {

			console.log(response);
			jQuery('.shopcatalogue .products').empty();
			response = JSON.parse(response);

			response.forEach(function(product) {

				getProduct(filter.url, product);

			});

		}
	});

});*/

var $soloradios = jQuery('#solo-colour input[name=outer-colour]').change(function() {

	var $radio = $soloradios.filter(':checked');

	var id = $radio.val();

	var title = $radio.parent('.swatch-label').find('input[name=colour_name]').val();
	$(".outer-set-option").html(title);

	getProducts(filter.url, 'single_colour_products', id, function(response) {

		if (response) {

			console.log(response);
			jQuery('.shopcatalogue .products').empty();
			response = JSON.parse(response);

			response.forEach(function(product) {

				getProduct(filter.url, product);

			});

		}

	});

});

var $radios1 = $('#mixitup input[name=outer-colour]').change(function () {
	
	var $radio = $radios1.filter(':checked');

	var ids = [];
	console.log($radio);

	$radio.each(function() {
		
		ids.push($(this).val());
	});
	var id = $radio.val();
	console.log("Texture IDs");
	console.log(ids);
	var title = $radio.parent('.swatch-label').find('input[name=colour_name]').val();
	$(".outer-set-option").html(title);
	getProducts(filter.url, 'mixitup_products', ids, function(response) {

		console.log(response);
		
		if (response) {

			jQuery('.shopcatalogue .products').empty();

		        response = JSON.parse(response);

		        response.forEach(function(product) {

		                getProduct(filter.url, product);

		        });

		}

	});

});

});
