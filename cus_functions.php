<?php

	function create_model_post() {

		register_post_type('model', array(

                        'labels' => array(

                                'name' => __('Models'),
                                'singular_name' => __('Model'),

                        ),
                        'public' => true,
                        'supports' => array(

                                'title',

                        ),

                ));

	}

	add_action('init', 'create_model_post');

	function create_texture_post() {

		register_post_type('texture', array(

			'labels' => array(
				'name' => __('Textures'),
				'singular_name' => __('Texture'),
			),
			'public' => true,
			'taxonomies' => array('category', 'post_tag'),
			'supports' => array(
				'title',
			),
			'has_archive' => false,
		));

		register_taxonomy('graphic', 'texture', array(

			'label' => 'Graphic Range',
			'hierarchical' => true,

		));

	}

	add_action('init', 'create_texture_post');

	function create_option_post() {
		
		register_post_type('option', array(

			'labels' => array(
				'name' => __('Options'),
				'singular_name' => __('Option'),
			),
			'public' => true,
			'supports' => array(
				'title'
			),
			'has_archive' => false,

		));
	}

	add_action('init', 'create_option_post');

	/**function load_default_textures($value, $post_id, $field) {

		$title_field = search($field['sub_fields'], 'name', 'texture_name')[0];
		$texture_field = search($field['sub_fields'], 'name', 'texture_image')[0];
		$thumb_field = search($field['sub_fields'], 'name', 'texture_thumb')[0];

		$title_key = $title_field['key'];
		$texture_key = $texture_field['key'];
		$thumb_key = $thumb_field['key'];

		$default_title_key = 'field_5746d27865de4';
		$default_texture_key = 'field_57594a96dd24b';
		$default_thumb_key = 'field_5746d28d65de5';

		if (!$value) {

			$value = array();

		}

		$value2 = get_field("outside_colour", 212, false);

                foreach($value2 as $texture) {

                        $texture_name = $texture[$default_title_key];
			$texture_image = $texture[$default_texture_key];
			$texture_thumb = $texture[$default_thumb_key];                  
			$found = count(search($value, $title_key, $texture_name)) > 0;

                        if (!$found) {

				//var_dump($value);
                                $value[] = array($title_key => $texture_name, $thumb_key => $texture_thumb, $texture_key => $texture_image);

                        }

                }

		return $value;

	}**/

	function load_default_textures($value, $post_id, $field) {

		$texture_field = search($field['sub_fields'], 'name', 'texture')[0];	
		$texture_key = $texture_field['key'];

		$name = $field["name"];
		$category = get_category_by_slug($name);
		$cat_id = $category->term_id;

		$textures = new WP_Query(array(
		
			'post_type' => 'texture',
			'cat' => $cat_id

		));

		if (!$value) {
			
			$value = array();

		}

		if ($textures->have_posts()) {

			while ($textures->have_posts()) {

				$textures->the_post();
				$post_id = $textures->post->ID;
				$found = count(search($value, $texture_key, $post_id));

				if (!$found) {
					
					$value[] = array($texture_key => $textures->post->ID);

				}	

			}

		}


		return $value;

	}

	add_filter('acf/load_value/key=field_575922524ddb7', 'load_default_textures', 10, 3);
	add_filter('acf/load_value/key=field_575fd013e1b32', 'load_default_textures', 10, 3);
	add_filter('acf/load_value/key=field_575ff55ca0ff3', 'load_default_textures', 10, 3);

	function customiser_texture_request() {

		if (isset($_REQUEST)) {
		
			$texture = $_REQUEST['texture'];
			
		}

		$result = array();

		if (isset($texture)) {

			$result['Ref'] = 'texture_outside';
			$result['name'] = get_the_title($texture);	
			//$result['thumbnail'] = get_field('thumbnail', $texture);

			$thumbnail = get_field('thumbnail', $texture);

			if (is_numeric($thumbnail)) {

				$thumbnail = wp_get_attachment_url($thumbnail);

			}

			$result['thumbnail'] = $thumbnail;

			$result['texture'] = get_field('texture_image', $texture);

			$the_texture = get_field('texture_image', $texture);

			if (is_numeric($the_texture)) {

				$the_texture = wp_get_attachment_url($the_texture);

			}

			$result['texture'] = $the_texture;

			$result['matt'] = get_field('matt', $texture);
			$result['reflection'] = get_field('reflection', $texture);
			$result['repeatable'] = get_field('repeatable', $texture);
			$result['intensity'] = get_field('intensity', $texture);

		}

		$result = json_encode($result);
		echo $result;
		wp_die();

	}

	/*function get_lighting() {

		$lights = array('PointLights' => array(), 'AmbientColour' => '');

		$lights['PointLights'] = get_field('lighting', 'options');
		$lights['AmbientColour'] = get_field('ambient_colour', 'options');

		echo json_encode($lights);
		die(0);

	}

	add_action('wp_ajax_get_lighting', 'get_lighting');
	add_action('wp_ajax_nopriv_get_lighting', 'get_lighting');*/

	/*function get_camera() {

		$camera = get_field('camera', 'options');

		echo json_encode($camera);
		die(0);

	}

	add_action('wp_ajax_get_camera', 'get_camera');
	add_action('wp_ajax_nopriv_get_camera', 'get_camera');*/

	function kino_get_cart_totals() {

		woocommerce_cart_totals();
		die();

	}

	function _get_texture_thumbnail($texture) {

		$thumbnail = get_field('thumbnail', $texture);

		if (is_numeric($thumbnail)) {

			$thumbnail = wp_get_attachment_url($thumbnail);

		}

		return $thumbnail;

	}

	function _get_texture_image($texture) {

		$the_texture = get_field('texture_image', $texture);

		if (is_numeric($the_texture)) {

			$the_texture = wp_get_attachment_url($the_texture);

		}

		return $the_texture;

	}

	function _get_texture($texture) {

		$result = array();

		if (isset($texture)) {

			$result['name'] = get_the_title($texture);
			$result['thumbnail'] = _get_texture_thumbnail($texture);
			$result['texture'] = _get_texture_image($texture);
			$result['matt'] = get_field('matt', $texture);
			$result['reflection'] = get_field('reflection', $texture);
			$result['repeatable'] = get_field('repeatable', $texture);
			$result['intensity'] = get_field('intensity', $texture);

		}

		return $result;

	}

	function customiser_texture_requests() {

		if (isset($_REQUEST)) {

			$textures = $_REQUEST['textures'];
			$sides = $_REQUEST['sides'];

		}

		$results = array();

		foreach($textures as $i => $texture) {

			$results[] = _get_texture($texture);
			$results[$i]['Ref'] = $sides[$i];

		}

		echo json_encode($results);
		die(0);

	}

	add_action('wp_ajax_customiser_texture_requests', 'customiser_texture_requests');
	add_action('wp_ajax_nopriv_customiser_texture_requests', 'customiser_texture_requests');

	add_action( 'wp_ajax_kino_get_cart_totals', 'kino_get_cart_totals');
	add_action( 'wp_ajax_nopriv_kino_get_cart_totals', 'kino_get_cart_totals');

	add_action( 'wp_ajax_customiser_texture_request', 'customiser_texture_request');
	add_action( 'wp_ajax_nopriv_customiser_texture_request', 'customiser_texture_request');

	function customiser_all_textures_request() {

		if (isset($_REQUEST)) {

			$product = $_REQUEST['product'];

		}	

		$result = array();

		if (isset($product)) {

			foreach(get_field('outside_colour', $product) as $texture) {
				
				$texture = $texture['texture'];

				$resultValue = array();
				$resultValue['Ref'] = 'texture_outside';
				$resultValue['name'] = get_the_title($texture);
				$resultValue['thumbnail'] = get_field('thumbnail', $texture);
				$resultValue['texture_image'] = get_field('texture_image', $texture);
				$resultValue['matt'] = get_field('matt', $texture);
				$resultValue['reflection'] = get_field('reflection', $texture);
				$resultValue['repeatable'] = get_field('repeatable', $texture);
				$resultValue['intensity'] = get_field('intensity', $texture);

				$result[] = $resultValue;
			}

		}

		$result = json_encode($result);
		echo $result;
		die();

	}

	add_action( 'wp_ajax_customiser_all_textures_request', 'customiser_all_textures_request');
	add_action( 'wp_ajax_nopriv_customiser_all_textures_request', 'customiser_all_textures_request');
	
	function customiser_get_table_request() {
	
		if (isset($_REQUEST)) {
			
			$product = $_REQUEST['product'];

		}

		$result = array();
		
		if (isset($product)) {

			$model_id = get_field('model_post', $product);

			if (is_null($model_id)) {

				$result['Object_File'] = get_field('model', $product);
				$result['Material_File'] = get_field('materials', $product);

			} else {
			
				$result['Object_File'] = get_field('model', $model_id);
				$result['Material_File'] = get_field('materials', $model_id);

			}

			if (is_numeric($result['Object_File'])) {

				$result['Object_File'] = wp_get_attachment_url($result['Object_File']);

			}

			if (is_numeric($result['Material_File'])) {

				$result['Material_File'] = wp_get_attachment_url($result['Material_File']);

			}

		}

		$result = json_encode($result);
		echo $result;
		die();
	}

	add_action( 'wp_ajax_customiser_get_table_request', 'customiser_get_table_request');
	add_action( 'wp_ajax_nopriv_customiser_get_table_request', 'customiser_get_table_request');

	function textures_where($where) {

		$where = str_replace("meta_key = 'colours_%", "meta_key LIKE 'colours_%", $where);

		return $where;

	}

	add_filter('posts_where', 'textures_where');

	function mixitup_products() {

		$the_textures = $_POST['textures'];
		$the_category = $_POST['category'];

		$meta_query = array(

			'relation' => 'AND',
			array(

				'key' => 'product_options',
				'value' => 'mix-n-match',
				'compare' => 'LIKE',
			),
		
		);

		foreach($the_textures as $texture) {

			$meta_query[] = array(

				'key' => 'colours_%_colour',
				'value' => $texture,
				'compare' => '=',

			);

		}

			

		$the_query = new WP_Query(array(
			'post_type' => 'product',
			'posts_per_page' => 50,
			'fields' => 'ids',
			'meta_query' => $meta_query,
			'tax_query' => array(

				array(
					
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' =>  $the_category,

				),

			),
		));

		echo json_encode($the_query->posts);
		wp_die();

	}

	add_action('wp_ajax_mixitup_products', 'mixitup_products');
	add_action('wp_ajax_nopriv_mixitup_products', 'mixitup_products');

	function single_colour_products() {

		$the_texture = $_POST['textures'];
		$the_category = $_POST['category'];

		$the_query = new WP_Query(array(

			'post_type' => 'product',
			'posts_per_page' => 20,
			'fields' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'product_options',
					'value' => 'solo-colour',
					'compare' => 'LIKE'
				),
				array(

					'key' => 'solo_colour',
					'value' => $the_texture,
					'compare' => '='

				)

			),
			'tax_query' => array(

				array(

					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $the_category,

				)

			)

		));

		echo json_encode($the_query->posts);
		wp_die();

	}

	add_action( 'wp_ajax_single_colour_products', 'single_colour_products');
	add_action( 'wp_ajax_nopriv_single_colour_products', 'single_colour_products');

	function graphic_range_products() {

		$the_texture = $_POST['textures'];
		$the_category = $_POST['category'];

		$the_query = new WP_Query(array(

			'post_type' => 'product',
			'posts_per_page' => 20,
			'fields' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
				array(

					'key' => 'product_options',
					'value' => 'graphic',
					'compare' => 'LIKE',

				),
				array(
					'key' => 'graphic_texture',
					'value' => $the_texture,
					'compare' => 'IN',
				),

			),
			'tax_query' => array(

				array(

					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $the_category,

				),

			),

		));

		echo json_encode($the_query->posts);
		wp_die();

	}
	
	function get_object_file() {

		echo json_encode("Object File");
		wp_die();

	}

	add_action('wp_ajax_get_object', 'get_object_file');
	add_action('wp_ajax_nopriv_get_object', 'get_object_file');

	add_action('wp_ajax_graphic_range_products', 'graphic_range_products');
	add_action('wp_ajax_nopriv_graphic_range_products', 'graphic_range_products');

	function get_graphic_textures() {

		$type = $_POST['textures'];

		$the_query = new WP_Query(array(

			'post_type' => 'texture',
			'posts_per_page' => -1,
			'tag' => $type,

		));

		echo json_encode($the_query->posts);
		wp_die();

	}

	add_action( 'wp_ajax_get_graphic_textures', 'get_graphic_textures');
	add_action( 'wp_ajax_nopriv_get_graphic_textures', 'get_graphic_textures');

	function get_product_savings() {

		error_reporting(0);

		$product_id = $_POST['product'];
		$quantity = $_POST['quantity'];
		$options = $_POST['product_options'];

		$_product = wc_get_product($product_id);
		$rrp_price = $_product->get_regular_price();
		$sale_price = $_product->get_sale_price();

		foreach($options as $id => $value) {

			if ($value == 'Yes') {

				$add_price = get_field('additional_price', $id);
				$rrp_price += $add_price;
				$sale_price += $add_price;

			}

		}

		$savings = (($rrp_price - $sale_price) / $rrp_price) * 100;
		echo $savings;
		wp_die();

	}

	add_action('wp_ajax_get_product_savings', 'get_product_savings');
	add_action('wp_ajax_nopriv_get_product_savings', 'get_product_savings');

	function get_graphic_products() {

		$graphic_categories = $_POST['graphic_categories'];
		$category = $_POST['category'];
		
		$meta_query = array(

			array(

				'key' => 'product_options',
				'value' => 'graphic',
				'compare' => 'LIKE',

			)

		);

		if ($graphic_categories && count($graphic_categories) > 0) {

			$meta_query[] = array(

				'key' => 'graphic_texture',
				'value' => $graphic_categories,
				'compare' => 'IN',

			);

		}

		$the_query = new WP_Query(array(

			'post_type' => 'product',
			'posts_per_page' => 20,
			'fields' => 'ids',
			'meta_query' => $meta_query,
			'tax_query' => array(

				array(

					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $category,

				),

			),

		));

		echo json_encode($the_query->posts);
		wp_die();

	}

	add_action('wp_ajax_get_graphic_products', 'get_graphic_products');
	add_action('wp_ajax_nopriv_get_graphic_products', 'get_graphic_products');

	function get_additional_price($options) {

		$add_price = 0;

		foreach ($options as $id => $value) {

			if ($value == 'Yes') {

				$t_option = icl_object_id($id, 'option', false, ICL_LANGUAGE_CODE);	
				$add_price += get_field('additional_price', $t_option);

			}

		}

		return $add_price;

	}

	/*function get_cart_item_saving($cart_item_key) {

		$rrp = get_cart_item_rrp($cart_item_key);
		$sale = get_cart_item_sale($cart_item_key);

		$saving = (($rrp - $sale) / $rrp) * 100;
		return (int) $saving;

	}

	function get_cart_item_sale($cart_item_key) {
		
		global $woocommerce;
		$cart = $woocommerce->cart;

		$_item = $cart->get_cart_item($cart_item_key);
		$_product = wc_get_product($_item['product_id']);

		$sale_price = $_product->get_display_price();
		$options = $_item['options'];

		$sale_price += get_additional_price($options);
		return $sale_price;


	}

	function get_cart_item_rrp($cart_item_key) {

		global $woocommerce;
		$cart = $woocommerce->cart;

		$_item = $cart->get_cart_item($cart_item_key);
		$_product = wc_get_product($_item['product_id']);

		$rrp_price = $_product->get_display_price($_product->get_regular_price());
		$options = $_item['options'];

		foreach ($options as $id => $value) {

			if ($value == 'Yes') {
			
				$t_option = icl_object_id($id, 'option', false, ICL_LANGUAGE_CODE);	
				$add_price = get_field('additional_price', $t_option);
				$rrp_price += $add_price;

			}


		}

		//$product_id = $_item['product_id'];
		return $rrp_price;

	}*/
	
	function get_product_rrp() {

		$product_id = $_POST['product'];
		$quantity = $_POST['quantity'];
		$options = $_POST['product_options'];

		$_product = wc_get_product($product_id);
		$rrp_price = $_product->get_display_price($_product->get_regular_price());

		foreach($options as $id => $value) {

			if ( $value == 'Yes' ) {

				$add_price = get_field('additional_price', $id);
				$rrp_price += $add_price;

			}

		}

		echo wc_price($rrp_price * $quantity);
		wp_die();

	}

	add_action('wp_ajax_get_product_rrp', 'get_product_rrp');
	add_action('wp_ajax_nopriv_get_product_rrp', 'get_product_rrp');
	//add_action('wc_ajax_get_product_rrp', 'get_product_rrp');

	function get_product_price() {

		$product_id = $_POST['product'];
		$quantity = $_POST['quantity'];
		$options = $_POST['product_options'];

		$_product = wc_get_product($product_id);
		
		//$sale_price = $_product->get_display_price();
		$sale_price = 0;

		foreach($options as $id => $value) {
		
			if ( $value == 'Yes' ) {

				$add_price = get_field('additional_price', $id);
				$sale_price += $add_price;

			}
			
		}

		echo wc_price($_product->get_display_price(/*$sale_price, $quantity*/));
		wp_die();


	}

	add_action('wp_ajax_get_product_price', 'get_product_price');
	add_action('wp_ajax_nopriv_get_product_price', 'get_product_price');

	function calculate_saving($product) {

		$regu_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();

		$saving = (($regu_price - $sale_price) / $regu_price) * 100;

		return (int) $saving;

	}

	/** Get Product as HTML **/
	function single_product_template() {
		
		$_pf = new WC_Product_Factory();
		$id = $_POST['product_id'];
		$the_product = $_pf->get_product($id);

		$product = array(
			
			'title' => get_the_title($id),
			'url' => get_the_permalink($id),
			'customise' => $the_product->get_attribute('customisable'),
			'image' => $the_product->get_image('full'),
			'rrp' => $the_product->get_display_price($the_product->get_regular_price()),
			'sale' => $the_product->get_display_price($the_product->get_sale_price()),
			'saving' => calculate_saving($the_product),

		);

		echo json_encode($product);
		//echo json_encode(get_post($id));
		//echo json_encode($the_product);

		wp_die();

	}

	add_action('wp_ajax_single_product_template', 'single_product_template');
	add_action('wp_ajax_nopriv_single_product_template', 'single_product_template');

	/** Get the default options for product **/
	function get_default_options() {

		// Get the Product ID
		$product_id = $_POST['product_id'];

		// Get the Product Options
		$options = get_field('options', $product_id);

		//Set up Default Array
		$defaults = array();

		// Loop through options and get the defaults for each side
		foreach ($options as $option) {

			$the_side = array();

			$the_side['side'] = $option['side'];
			$the_side['value'] = $option['default'];

			//$defaults[$option['side']] = $option['default'];
			$defaults[] = $the_side;

		}

		// Output the Defaults as JSON.
		echo json_encode($defaults);
		wp_die();

	}

	function get_default_textures() {

		//Get the Product ID
		$product_id = $_POST['product_id'];

		//Get the Default Textures
		$textures = get_field('default_textures', $product_id);

		//Set up Default Array
		$defaults = array();

		foreach($textures as $texture) {

			$the_side = array();

			$the_side['side'] = $texture['side'];
			$the_side['texture'] = get_field('texture_image', $texture['texture']);

			$defaults[] = $the_side;

		}

		echo json_encode($defaults);
		wp_die();

	}

	function get_graphic_subcategories() {

		$parent = $_POST['categories'];
		$ids = array();

		$results = get_terms(array(

			'taxonomy' => 'graphic',
			'hide_empty' => false,
			'parent' => $parent[0],

		));

		$categories = array();

		foreach($results as $result) {
			
			$categories[] = array(
			
				'term_id' => $result->term_id,
				'name' => $result->name,
				'image' => get_field('thumbnail', 'graphic_' . $result->term_id),

			);

		}

		echo json_encode($categories);
		wp_die();

	}

	add_action('wp_ajax_get_graphic_subcategories', 'get_graphic_subcategories');
	add_action('wp_ajax_nopriv_get_graphic_subcategories', 'get_graphic_subcategories');

	add_action('wp_ajax_get_default_options', 'get_default_options');
	add_action('wp_ajax_nopriv_get_default_options', 'get_default_options');

	add_action('wp_ajax_get_default_textures', 'get_default_textures');
	add_action('wp_ajax_nopriv_get_default_textures', 'get_default_textures');

	/** Stuff for the Cart **/
	add_filter('woocommerce_add_cart_item_data', 'add_cart_item_custom_texture_options', 10, 2);

	function add_cart_item_custom_texture_options($cart_item_meta, $product_id) {
		
		global $woocommerce;
		$cart_item_meta['textures'] = $_POST['textures'];
		$cart_item_meta['options'] = $_POST['options'];
		$cart_item_meta['thumbnail'] = $_POST['thumbnail'];
		return $cart_item_meta;

	}

	add_filter('woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 1, 3);

	function get_cart_items_from_session($item, $values, $key) {
		
		if ( array_key_exists('textures', $values)) {

			$item['textures'] = $values['textures'];

		}

		if (array_key_exists('options', $values)) {

			$item['options'] = $values['options'];

		}
		
		return $item;

	}

	add_action('woocommerce_add_order_item_meta', 'kino_add_values_to_order_item_meta', 1, 2);

	if (!function_exists('kino_add_values_to_order_item_meta')) {

		function kino_add_values_to_order_item_meta($item_id, $values) {

			global $woocommerce, $wpdb;
			$user_custom_values = $values['textures'];
			//var_dump($values);
			//exit(0);

			if (!empty($user_custom_values)) {

				foreach($user_custom_values as $key => $texture) {
					
					$name = get_term($key)->name;
					$value = get_the_title($texture);

					$result = wc_add_order_item_meta($item_id, $name, $value);

				}

			}

			$user_custom_values = $values['options'];

			if (!empty($user_custom_values)) {

				foreach ($user_custom_values as $key => $option) {

					$name = get_the_title($key);
					$result = wc_add_order_item_meta($item_id, $name, $option);

				}

			}

		}

	}

	add_action('woocommerce_before_item_quantity_zero', 'kino_remove_user_custom_data_options_from_cart', 1, 1);

	if (!function_exists('kino_remove_user_custom_data_options_from_cart')) {

		function kino_remove_user_custom_data_options_from_cart() {
			
			global $woocommerce;

			$cart = $woocommerce->cart->get_cart();

			foreach($cart as $key => $values) {
				
				if ($values['textures'] == $cart_item_key) {

					unset($woocommerce->cart->cart_contents[$key]);

				}

				if ($values['options'] == $cart_item_key) {

					unset($woocommerce->cart->cart_contents[$key]);

				}
			}

		}

	}

	/** Add the data to the cart object **/

	/*add_filter('woocommerce_get_cart_item_from_session', 'kino_get_cart_items_from_session', 1, 3);

	if (!function_exists('kino_get_cart_items_from_session', 1, 3)) {

		function kino_get_cart_items_from_session($item, $values, $key) {

			if (array_key_exists('textures', $values)) {

				$item['textures'] = $values

			}

		}

	}*/

	/**add_action( 'woocommerce_cart_calculate_fees', 'kino_add_option_charge');

	function kino_add_option_charge($cart_object) {

		write_log('Add Option Charge');

		global $woocommerce;

		$optionPrice = 0;

		foreach ($cart_object->cart_contents as $key => $value) {
			
			$options = $value['options'];

			if ($options) {
			
				$optionTotal = count($options);
				$optionPrice = $optionTotal * 25;

			}

			$value['line_subtotal'] += $optionPrice;

		}

	}**/

	//add_filter('woocommerce_cart_item_subtotal', 'filter_woocommerce_cart_item_subtotal', 10, 3);

	/**function filter_woocommerce_cart_item_subtotal($product, $cart_item, $cart_item_key) {

		global $woocommerce;

		$options = $cart_item['options'];

		$optionCount = count($options);
		$optionPrice = 25 * $optionCount;

		$saleprice = $cart_item['sale_price'] + $optionPrice;
		$newPrice = wc_price($salePrice);

		return $product;

	}**/

	if (!function_exists('write_log')) {
	
		function write_log($log) {

			if (true === WP_DEBUG) {

				if (is_array($log) || is_object($log)) {
					error_log(print_r($log, true));
				} else {
					error_log($log);
				}
			}
		}

	}

	require_once('includes/kino_wc_cart.php');
	require_once('includes/kino_customizer.php');

	function kino_product_template() {

		include_once get_stylesheet_directory() . "/templates/product-templates.php";
		include_once get_stylesheet_directory() . "/templates/sidebar-template.php";

	}

	add_action("wp_footer", "kino_product_template");

	function custom_excerpt_length($length) {

		return 10;

	}

	add_filter('excerpt_length', 'custom_excerpt_length', 999);



?>
