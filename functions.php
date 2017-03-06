<?php

require_once "jam_functions.php";
require_once('cus_functions.php');
require_once('includes/kino_wc_ajax_class.php');

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

//jQuery Insert From Google

add_action('pre_get_posts', 'alter_query');

function alter_query($query) {

	global $wp_query;
	
	if (!is_admin() && is_tax() && $query->is_main_query()) {
	
		add_filter('posts_where', 'kino_where');
		
	}

}

global $filter_where;
$filter_where = 0;

function kino_where($where) {

	global $filter_where;

	if ($filter_where < 2) {
	
		$filter_where++;
		return " AND 1=0";

	}

	return $where;

}

//add_filter('style_loader_tag', 'postpone_style_load');

function postpone_style_load($html, $handle, $href, $media) {

	$onload = "this.rel='stylesheet'";

	//var_dump($handle);
	$style = "<link rel='preload' id='$handle-css' as='style' href='$href' type='text/css' media='$media' />";
	//var_dump($onload);

	//$style = str_replace('stylesheet', "preload\" as=\"style\" onload=\"this.rel=" . $onload, $style);

	return $style;

}

add_action('woocommerce_product_query', 'kino_product_query');

function kino_product_query($q) {
        
        $meta_query = $q->get('meta_query');

        if (isset($_GET['filter'])) {

                $cat_param = htmlspecialchars($_GET["filter"]);

                $meta_query[] = array(

                        'key' => 'product_options',
                        'value' => $cat_param,
                        'compare' => 'LIKE',

                );

        }

        $q->set('meta_query', $meta_query);

}

function add_scripts() {
	wp_enqueue_script('whatinput', get_template_directory_uri() . '/bower_components/what-input/what-input.min.js', array(), false, true);
	wp_enqueue_script('foundation', get_template_directory_uri() . '/bower_components/foundation-sites/dist/foundation.min.js', array(), false, true);
	wp_enqueue_script('appjs', get_template_directory_uri() . '/js/app.js', array('foundation'), false, true);
	wp_enqueue_script('owl', get_template_directory_uri() . '/owl/owl.carousel.min.js', array(), false, true);
	wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), false, true);
	wp_enqueue_script('wp-util', false, array(), false, true);
	wp_enqueue_script('filter', get_template_directory_uri() . '/js/filter.js', array(), false, true);
	wp_localize_script('filter', 'filter', array('url' => admin_url('admin-ajax.php'))); //admin_url('admin-ajax.php');
	wp_enqueue_script('languageswitcher', get_template_directory_uri() . '/js/language-switcher.js', array(), false, true);

	if (is_singular('product')) {

		global $post;

		$product_id = $post->ID;

		$cusdir = get_template_directory_uri() . '/js/';

		if (!is_null(get_field('model_post', $product_id))) {
		
			$cusdir .= 'customiser';	

		} else {

			$cusdir .= 'old_customiser';

		}

		wp_enqueue_script('optionselector', get_template_directory_uri() . '/js/option_selector.js');
		wp_localize_script('optionselector', 'ajax', array('url' => admin_url('admin-ajax.php')));
		wp_enqueue_script('urls', get_template_directory_uri() . '/js/customiser/urls.js');
		wp_enqueue_script('spinner', get_template_directory_uri() . '/js/customiser/spin.min.js');
		wp_enqueue_script('threejs', get_template_directory_uri() . '/js/customiser/three.js');
		wp_enqueue_script('mtlloader', get_template_directory_uri() . '/js/customiser/MTLLoader.js', array('threejs'));
		wp_enqueue_script('objloader', get_template_directory_uri() . '/js/customiser/OBJLoader.js', array('threejs'));
		wp_enqueue_script('objmtlloader', get_template_directory_uri() . '/js/customiser/OBJMTLLoader.js', array('mtlloader', 'objloader', 'threejs'));
		wp_enqueue_script('copyshader', $cusdir . '/shaders/CopyShader.js');
		wp_enqueue_script('dotscreenshader', $cusdir . '/shaders/DotScreenShader.js');
		wp_enqueue_script('fxaashader', $cusdir . '/shaders/FXAAShader.js');
		wp_enqueue_script('effectcomposer', $cusdir . '/postprocessing/EffectComposer.js');
		wp_enqueue_script('renderpass', $cusdir . '/postprocessing/RenderPass.js');
		wp_enqueue_script('maskpass', $cusdir . '/postprocessing/MaskPass.js');
		wp_enqueue_script('shaderpass', $cusdir . '/postprocessing/ShaderPass.js');
		wp_enqueue_script('lightingjs', $cusdir . '/Lighting.js', array(), '1.0');
		wp_enqueue_script('tablejs', $cusdir . '/Table.js', array('objmtlloader', 'copyshader', 'dotscreenshader', 'fxaashader', 'effectcomposer', 'renderpass', 'maskpass', 'shaderpass', 'threejs'), '1.0');
		wp_localize_script('tablejs', 'ajax', array('url' => admin_url('admin-ajax.php')));
		wp_enqueue_script('customiserjs', $cusdir . '/customiser.js', array('threejs', 'tablejs', 'modernizr'), '1.0');
		wp_enqueue_script('quantityjs', get_template_directory_uri() . '/js/product.js');

	}

}

function cart_custom_redirect_continue_shopping() {

	return 'https://www.zespoke.com/';

}

add_filter('woocommerce_continue_shopping_redirect', 'cart_custom_redirect_continue_shopping');

function add_styles() {

	wp_enqueue_style('app2css', get_template_directory_uri() . '/css/n_app.css');
	wp_enqueue_style('lightboxcss', get_template_directory_uri() . '/css/lightbox.css');
	wp_enqueue_style('owlcss', get_template_directory_uri() . '/owl/assets/owl.carousel.css');
	wp_enqueue_style('languageswitcher', get_template_directory_uri() . '/css/language-switcher.css');

}

add_action('init', 'create_zespoke_content');
add_action('init', 'create_zespoke_customer_reviews');
add_action('init', 'create_banner');
add_action('init', 'register_zespoke_menus');

function multistep_checkout_override_script(){

	if ( is_checkout() ) {
	wp_enqueue_script('sidebar', get_template_directory_uri() . '/js/sidebar.php');

	wp_dequeue_script('jcmc-checkout');
	wp_deregister_script('jcmc-checkout');
	wp_register_script('jcmc-checkout', get_template_directory_uri() . '/js/checkout_mod.js', TRUE);
	wp_enqueue_script('jcmc-checkout');
	}
}
add_action( 'wp_enqueue_scripts', 'multistep_checkout_override_script', 1 );


function create_zespoke_customer_reviews() {
	register_post_type('zespoke-reviews', array(
		'labels' => array(
		
			'name' => __('Zespoke Reviews'),
			'singular_name' => __('Zespoke Review')
		
		),
		'public' => true,
		'rewrite' => array('slug' => 'zespoke-review'),
		'supports' => array(
		
			'title',
			'thumbnail',
			'page-attributes'
		
		)
	
	));
}


function create_zespoke_content() {
	register_post_type('zespoke-content', array(
		'labels' => array(
		
			'name' => __('Zespoke Content'),
			'singular_name' => __('Zespoke Content')
		
		),
		'public' => true,
		'rewrite' => array('slug' => 'zespoke-content'),
		'supports' => array(
		
			'title',
			'page-attributes'
		
		)
	
	));
}

function create_banner() {

	register_post_type('banner', array(
	
		'labels' => array(
		
			'name' => __('Banner'),
			'singular_name' => __('Banners')
		
		),
		'public' => true,
		'rewrite' => array('slug' => 'banner'),
		'supports' => array(
		
			'title',
			'page-attributes'
		
		)
	
	));

}

function register_zespoke_menus() {

	register_nav_menus(
	
		array(
		
			'header_menu' => __('Main Menu')
		
		)
	
	);

}

add_action('wp_enqueue_scripts', 'add_scripts');
add_action('wp_enqueue_scripts', 'add_styles');


// filter the Gravity Forms button type on Form ID 1
/*
add_filter("gform_submit_button", "form_submit_button", 10, 2);
function form_submit_button($button, $form) {
	if ($form['id'] == 1) {
		return "";
	} else {
		return "<button class='button' id='gform_submit_button_{$form["id"]}'>Submit</button>";
	}
}

add_filter( 'gform_field_content', 'form_submit_field', 10, 5 );
function form_submit_field($button, $form) {
	if ($form['id'] == 1) {
		return "<div class='ginput_container ginput_container_email row collapse'>
			<div class='small-9 columns'>
				<input name='input_1' id='input_1_1' type='text' value='' class='large' tabindex='1' placeholder='Enter your email address'>
			</div>
			<div class='small-3 columns'>
				<button class='button' id='gform_submit_button_{$form["id"]}'><span>Send</span></button>
			</div>
		</div>";
	} else {
		return "<div>Test</div>";
	}
}

*/

/* remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0); */

function is_parent_category($parentcat) {
	$result = 'false';
	$cat = get_the_terms( $product->ID, 'product_cat' );

	foreach ($cat as $categoria) {
		if($categoria->parent == 0){
			$topcat = $categoria->slug;
			if ($topcat = $parentcat) {
				$result = 'true';
			}
		}
	}
	return $result;
}

// array of filters (field key => field name)
$GLOBALS['my_query_filters'] = array( 
	'field_1'	=> 'city', 
	'field_2'	=> 'bedrooms'
);


// action
add_action('pre_get_posts', 'my_pre_get_posts', 10, 1);

function my_pre_get_posts( $query ) {
	
	// bail early if is in admin
	if( is_admin() ) {
		
		return;
		
	}
	
	
	// get meta query
	$meta_query = $query->get('meta_query');

	
	// loop over filters
	foreach( $GLOBALS['my_query_filters'] as $key => $name ) {
		
		// continue if not found in url
		if( empty($_GET[ $name ]) ) {
			
			continue;
			
		}
		
		
		// get the value for this filter
		$value = explode(',', $_GET[ $name ]);
		
		
		// append meta query
    	$meta_query[] = array(
            'key'		=> $name,
            'value'		=> $value,
            'compare'	=> 'IN',
        );
        
	} 
	
	
	// update meta query
	$query->set('meta_query', $meta_query);

}

add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_delimiter' );
function jk_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' &gt; ';
	return $defaults;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

add_action( 'zespoke_product_price', 'woocommerce_template_single_price', 5);

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );
 
function woo_custom_cart_button_text() {
	return __( 'Add to Basket', 'woocommerce' );
}

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}

function wpbeginner_numeric_posts_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="navigation"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li class="back-link">%s</li>' . "\n", get_previous_posts_link('<span class="screen-reader-text">Back</span>') );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li class="next-link">%s</li>' . "\n", get_next_posts_link('<span class="screen-reader-text">Next</span>') );

	echo '</ul></div>' . "\n";

}

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'id' => 'sidebar-1',
    'name' => 'Blog Post Sidebar',
    'before_widget' => '<div class="blog-post-sidebar">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'id' => 'sidebar-2',
    'name' => 'Checkout Sidebar',
    'before_widget' => '<div class="checkout-sidebar">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

add_filter( 'woocommerce_order_button_text', create_function( '', 'return "Confirm Order";' ) );

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_filter( 'jcmc_custom_section','jc_custom_section' );
function jc_custom_section($sections){

	ob_start();

	echo '<h3>Order Review</h3>';
	?>
	<div id="jc_order_review" class="woocommerce-checkout-review-order">
		<div>Test</div>
	</div>
	<?php
	$content = ob_get_clean();

	$sections['jc_order_review'] = array(
		'content' => $content, 	// content of the section
	);

	return $sections;
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {

	unset($fields['billing']['billing_company']);
	unset($fields['shipping']['shipping_company']); 

	if (ICL_LANGUAGE_CODE !== "usa") {
		unset($fields['billing']['billing_state']);
	}
	$fields['order']['order_comments']['placeholder'] = '';
	return $fields;
}


function my_ajax() {
	global $wpdb;
	dynamic_sidebar('checkout-sidebar');
}
add_action( 'wp_ajax_nopriv_my_ajax', 'my_ajax' );

function custom_upload_mimes($existing_mimes = array()) {

	$existing_mimes['obj'] = 'text/plain';
	$existing_mimes['mtl'] = 'text/plain';

	return $existing_mimes;

}

add_filter('upload_mimes', 'custom_upload_mimes');

// http://www.jordancrown.com/multi-column-gravity-forms/
function gform_column_splits( $content, $field, $value, $lead_id, $form_id ) {
	if( !IS_ADMIN ) { // only perform on the front end

		// target section breaks
		if( $field['type'] == 'section' ) {
			$form = RGFormsModel::get_form_meta( $form_id, true );

			// check for the presence of multi-column form classes
			$form_class = explode( ' ', $form['cssClass'] );
			$form_class_matches = array_intersect( $form_class, array( 'two-column', 'three-column' ) );

			// check for the presence of section break column classes
			$field_class = explode( ' ', $field['cssClass'] );
			$field_class_matches = array_intersect( $field_class, array('gform_column') );

			// if field is a column break in a multi-column form, perform the list split
			if( !empty( $form_class_matches ) && !empty( $field_class_matches ) ) { // make sure to target only multi-column forms

				// retrieve the form's field list classes for consistency
				$form = RGFormsModel::add_default_properties( $form );
				$description_class = rgar( $form, 'descriptionPlacement' ) == 'above' ? 'description_above' : 'description_below';

				// close current field's li and ul and begin a new list with the same form field list classes
				return '</li></ul><ul class="gform_fields '.$form['labelPlacement'].' '.$description_class.' '.$field['cssClass'].'"><li class="gfield gsection empty">';

			}
		}
	}

	return $content;
}
add_filter( 'gform_field_content', 'gform_column_splits', 10, 5 );

// Enqueue Scripts on Contact Page
add_action( 'wp_enqueue_scripts', 'sk_enqueue_scripts' );
function sk_enqueue_scripts() {
	if ( ! is_page( 'contact-us' ) ) {
		return;
	}
	
	wp_enqueue_script( 'contact-page', get_stylesheet_directory_uri() . '/js/contact-form.js', array( 'jquery' ), '', true );
}

// add styles and button to visual editor
function wpb_mce_buttons_2($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}
add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

function my_mce_before_init_insert_formats( $init_array ) {  

// Define the style_formats array

	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => 'Zesopke Fancy Title',  
			'block' => 'h2',  
			'classes' => 'fancy',
			'wrapper' => false,
			
		),  
		array(  
			'title' => 'Zesopke Heading',  
			'block' => 'h3',  
			'classes' => 'ze-heading',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Subheading',  
			'block' => 'h4',  
			'classes' => 'ze-subheading',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Underline',  
			'block' => 'span',
			'classes' => 'ze-underline',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Strikethrough',  
			'block' => 'span',
			'classes' => 'ze-strikethrough',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Bullet List',  
			'block' => 'ul',
			'classes' => 'ze-bullet-list',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Numbered List',  
			'block' => 'ol',
			'classes' => 'ze-numbered-list',
			'wrapper' => false,
		),
		array(  
			'title' => 'Zesopke Two Column Text',  
			'block' => 'div',
			'classes' => 'two-col-text',
			'wrapper' => false,
		),
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );

add_filter ( 'wc_add_to_cart_message', 'wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter($message, $product_id = null) {
    $titles[] = get_the_title( $product_id );

    $titles = array_filter( $titles );
    $added_text = sprintf( _n( '%s has been added to your basket.', '%s have been added to your basket.', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

    $message = sprintf( '%s <a href="%s" class="button">%s</a>',
                    esc_html( $added_text ),
                    esc_url( wc_get_page_permalink( 'cart' ) ),
                    esc_html__( 'View Basket', 'woocommerce' ));

    return $message;
}

function woo_add_country( $country ) {
	$country["UK-IS"] = 'United Kingdom (Offshore Islands)';
	$country["UK-NI"] = 'Northern Ireland';
	return $country;
}
add_filter( 'woocommerce_countries', 'woo_add_country', 10, 1 );

function exclude_countries( $country ) {
	unset($country['AF']);
	unset($country['AX']);
	unset($country['AL']);
	unset($country['DZ']);
	unset($country['AD']);
	unset($country['AO']);
	unset($country['AI']);
	unset($country['AQ']);
	unset($country['AG']);
	unset($country['AR']);
	unset($country['AM']);
	unset($country['AW']);
	unset($country['AU']);
	unset($country['AZ']);
	unset($country['BS']);
	unset($country['BH']);
	unset($country['BD']);
	unset($country['BB']);
	unset($country['BY']);
	unset($country['PW']);
	unset($country['BZ']);
	unset($country['BJ']);
	unset($country['BM']);
	unset($country['BT']);
	unset($country['BO']);
	unset($country['BQ']);
	unset($country['BA']);
	unset($country['BW']);
	unset($country['BV']);
	unset($country['BR']);
	unset($country['IO']);
	unset($country['VG']);
	unset($country['BN']);
	unset($country['BG']);
	unset($country['BF']);
	unset($country['BI']);
	unset($country['KH']);
	unset($country['CM']);
	//unset($country['CA']);
	unset($country['CV']);
	unset($country['KY']);
	unset($country['CF']);
	unset($country['TD']);
	unset($country['CL']);
	unset($country['CN']);
	unset($country['CX']);
	unset($country['CC']);
	unset($country['CO']);
	unset($country['KM']);
	unset($country['CG']);
	unset($country['CD']);
	unset($country['CK']);
	unset($country['CR']);
	unset($country['HR']);
	unset($country['CU']);
	unset($country['CW']);
	unset($country['CY']);
	unset($country['DJ']);
	unset($country['DM']);
	unset($country['DO']);
	unset($country['EC']);
	unset($country['EG']);
	unset($country['SV']);
	unset($country['GQ']);
	unset($country['ER']);
	unset($country['ET']);
	unset($country['FK']);
	unset($country['FO']);
	unset($country['FJ']);
	unset($country['GF']);
	unset($country['PF']);
	unset($country['TF']);
	unset($country['GA']);
	unset($country['GM']);
	unset($country['GE']);
	unset($country['GH']);
	unset($country['GI']);
	unset($country['GR']);
	unset($country['GL']);
	unset($country['GD']);
	unset($country['GP']);
	unset($country['GT']);
	//unset($country['GG']);
	$country['GG'] = 'United Kingdom (Guernsey)';
	unset($country['GN']);
	unset($country['GW']);
	unset($country['GY']);
	unset($country['HT']);
	unset($country['HM']);
	unset($country['HN']);
	unset($country['HK']);
	unset($country['IS']);
	unset($country['IN']);
	unset($country['ID']);
	unset($country['IR']);
	unset($country['IQ']);
	//unset($country['IM']);
	$country['IM'] = 'United Kingdom (Isle of Man)';
	unset($country['IL']);
	unset($country['CI']);
	unset($country['JM']);
	unset($country['JP']);
	//unset($country['JE']);
	$country['JE'] = 'United Kingdom (Jersey)';
	unset($country['JO']);
	unset($country['KZ']);
	unset($country['KE']);
	unset($country['KI']);
	unset($country['KW']);
	unset($country['KG']);
	unset($country['LA']);
	unset($country['LB']);
	unset($country['LS']);
	unset($country['LR']);
	unset($country['LY']);
	unset($country['MO']);
	unset($country['MK']);
	unset($country['MG']);
	unset($country['MW']);
	unset($country['MY']);
	unset($country['MV']);
	unset($country['ML']);
	unset($country['MT']);
	unset($country['MH']);
	unset($country['MQ']);
	unset($country['MR']);
	unset($country['MU']);
	unset($country['YT']);
	unset($country['MX']);
	unset($country['FM']);
	unset($country['MD']);
	unset($country['MC']);
	unset($country['MN']);
	unset($country['ME']);
	unset($country['MS']);
	unset($country['MA']);
	unset($country['MZ']);
	unset($country['MM']);
	unset($country['NA']);
	unset($country['NR']);
	unset($country['NP']);
	unset($country['AN']);
	unset($country['NC']);
	unset($country['NZ']);
	unset($country['NI']);
	unset($country['NE']);
	unset($country['NG']);
	unset($country['NU']);
	unset($country['NF']);
	unset($country['KP']);
	unset($country['OM']);
	unset($country['PK']);
	unset($country['PS']);
	unset($country['PA']);
	unset($country['PG']);
	unset($country['PY']);
	unset($country['PE']);
	unset($country['PH']);
	unset($country['PN']);
	unset($country['QA']);
	unset($country['RE']);
	unset($country['RO']);
	unset($country['RU']);
	unset($country['RW']);
	unset($country['BL']);
	unset($country['SH']);
	unset($country['KN']);
	unset($country['LC']);
	unset($country['MF']);
	unset($country['SX']);
	unset($country['PM']);
	unset($country['VC']);
	unset($country['SM']);
	unset($country['ST']);
	unset($country['SA']);
	unset($country['SN']);
	unset($country['RS']);
	unset($country['SC']);
	unset($country['SL']);
	unset($country['SG']);
	unset($country['SB']);
	unset($country['SO']);
	unset($country['ZA']);
	unset($country['GS']);
	unset($country['KR']);
	unset($country['SS']);
	unset($country['LK']);
	unset($country['SD']);
	unset($country['SR']);
	unset($country['SJ']);
	unset($country['SZ']);
	unset($country['SY']);
	unset($country['TW']);
	unset($country['TJ']);
	unset($country['TZ']);
	unset($country['TH']);
	unset($country['TL']);
	unset($country['TG']);
	unset($country['TK']);
	unset($country['TO']);
	unset($country['TT']);
	unset($country['TN']);
	unset($country['TR']);
	unset($country['TM']);
	unset($country['TC']);
	unset($country['TV']);
	unset($country['UG']);
	unset($country['UA']);
	unset($country['AE']);
	unset($country['UY']);
	unset($country['UZ']);
	unset($country['VU']);
	unset($country['VA']);
	unset($country['VE']);
	unset($country['VN']);
	unset($country['WF']);
	unset($country['EH']);
	unset($country['WS']);
	unset($country['YE']);
	unset($country['ZM']);
	unset($country['ZW']);


	if (ICL_LANGUAGE_CODE === "en") {

                unset($country['US']);

        } else if (ICL_LANGUAGE_CODE === "usa") {

                unset($country['IM']);
                unset($country['JE']);
                unset($country['GG']);
                unset($country['UK-IS']);
                unset($country['UK-NI']);
                unset($country['GB']);

        }

	return $country;
}
add_filter( 'woocommerce_countries', 'exclude_countries', 10, 1 );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

//add_filter('woocommerce_email_recipient_customer_processing_order', 'kino_add_recipient', 10, 2);

function kino_add_recipient($recipient, $order) {

	$recipient = $recipient . ",jamlendrem@gmail.com";
	return $recipient;

}

add_filter('woocommerce_email_headers', 'kino_headers_filter_function', 1, 2);

function kino_headers_filter_function($headers, $id, $order) {

	if ($id == 'customer_completed_order') {

		$headers .= 'BCC: Filter <Jamlendrem@gmail.com>' . "\r\n";

	}

	return $headers;

}

add_action('post_updated', 'kino_delete_textures_transient', 10, 3);

function kino_delete_textures_transient($post_id, $post_after, $post_before) {

	$type = get_post_type($post_id);

	if ($type == 'product') {

		delete_transient('textures-' . $post_id);

	}

}

function kino_get_children_product_categories($filter) {

        $term_id = $category_id;

        if (is_product_category()) {

                $category = get_queried_object();
                $category_id = $category->term_id;

		$slug = $filter;

		if ($category_id != 7) {

			$slug = $slug . '-' . $category->slug;

		}

                $terms = get_terms(array(

                        'taxonomy' => 'product_cat',
                        'hide_empty' => 'false',
                        'parent' => $category_id,
                        'slug' => $slug,

                ));

                if (count($terms) >= 1) {

                        $term_id = $terms[0]->term_id;

                }

        }

        return $term_id;

}

function kino_get_header_mobile_slider_images($filter = null) {

        $cache_key = 'mobile_header_slider';

        $page = 169;

        if (is_product_category()) {

                if ($filter == 'mix-n-match') {

                        $filter = 'mix-it-up';

                }

		if ($filter == 'graphic') {

			$filter = 'graphic-range';

		}

                $category_id = kino_get_children_product_categories($filter);

                $cache_key = 'kino_header_' . $category_id . '_slider';

                $page = 'product_cat_' . $category_id;

        }

        $images = get_transient($cache_key);
        $images = false;

        if ($images === false) {

                $images = get_field('mobile_header_slider', $page);
                set_transient($cache_key, $images, WEEK_IN_SECONDS);

        }

        return $images;

}

function kino_get_header_slider_images($filter = null, $mobile = false) {

        $cache_key = 'kino_header_slider';

        $page = 169;

        if (is_product_category()) {

                if ($filter == 'mix-n-match') {

                        $filter = 'mix-it-up';

                }

		if ($filter == 'graphic') {

			$filter = 'graphic-range';

		}

                $category_id = kino_get_children_product_categories($filter);

                $cache_key = 'kino_header_' . $category_id . '_slider';

                $page = 'product_cat_' . $category_id;

        }

        $images = get_transient($cache_key);
        $images = false;

        if ($images === false) {

                $images = get_field('header_slider', $page);
                set_transient($cache_key, $images, WEEK_IN_SECONDS);

        }

        return $images;

}

add_action('woocommerce_before_cart', 'clear_paypal_session_data');

function clear_paypal_session_data() {
        unset( WC()->session->paypal );
}

function kc_is_paypal() {

	return isset(WC()->session->paypal);

}

if (function_exists('acf_add_options_page')) {

$options = acf_add_options_page(array(

	'page_title' => 'Zespoke Settings',
	'menu_title' => 'Zespoke Settings',
	'redirect' => false,

));

acf_add_options_sub_page(array(

	'page_title' => 'Footer Options',
	'menu_title' => 'Footer',
	'parent_slug' => $options['menu_slug'],

));

acf_add_options_sub_page(array(

        'page_title' => 'Header Options',
        'menu_title' => 'Header',
        'parent_slug' => $options['menu_slug'],

));

acf_add_options_sub_page(array(

        'page_title' => 'Product Options',
        'menu_title' => 'Product',
        'parent_slug' => $options['menu_slug'],

));

acf_add_options_sub_page(array(

	'page_title' => 'Customiser Lighting',
	'menu_title' => 'Lighting',
	'parent_slug' => $options['menu_slug'],

));

}

/** Custom Language Switcher **/

add_action('kino_language_switcher', 'kino_language_switcher');

function kino_language_switcher() {

	$default_language = ICL_LANGUAGE_NAME;

	if ($default_language == "English") {

		$default_language = "UK";

	}

	$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
	$switcher = "<div class=\"kino-language-switcher\">";

	//Selected Language
	$switcher = $switcher . "<div class=\"selected\"><i class=\"left fa fa-globe\" aria-hidden=\"true\"></i><span>" . $default_language . "</span><i class=\"right open-closed fa fa-angle-down\"></i></div>";

	//Other Languages
	$switcher = $switcher . "<div class=\"other-languages hidden\">";

	foreach($languages as $language) {

		$switcher = $switcher . "<div><a href=\"" . $language["url"] . "\">" . ($language['native_name'] == "English" ? "UK" : $language['native_name']) . "</a></div>";

	}

	$switcher = $switcher . "</div>";

	echo $switcher;

}

add_filter('woocommerce_get_regular_price', 'rrp_add_shipping_costs', 10, 2);
add_filter('woocommerce_get_regular_price', 'add_tax', 10, 2);
//add_filter('woocommerce_get_regular_price', 'round_price', 10, 2);

function add_tax($price, $product) {

	global $woocommerce_wpml;

	$currency = $woocommerce_wpml->multi_currency->get_client_currency();
	$tax = get_field('tax', 'options');

	$price = ($price / 1.2) * $tax;

	return $price;

}

function rrp_add_shipping_costs($price, $product) {

	global $woocommerce_wpml;

	$shipping = get_field('shipping_fee', 'options');

	$currency = $woocommerce_wpml->multi_currency->get_client_currency();
	$exchange_rates = $woocommerce_wpml->multi_currency->get_exchange_rates();

	$shipping = ($shipping * 1.2 ) * $exchange_rates[$currency];

	$price = $price + $shipping;
	return $price;

}

add_filter('woocommerce_get_sale_price', 'add_shipping_costs', 10, 2);
add_filter('woocommerce_get_sale_price', 'add_tax', 10, 2);
//add_filter('woocommerce_get_sale_price', 'round_price', 10, 2);

function add_shipping_costs($price, $product) {

	global $woocommerce_wpml;

	$shipping = get_field('shipping_fee', 'options');

	$currency = $woocommerce_wpml->multi_currency->get_client_currency();
	$exchange_rates = $woocommerce_wpml->multi_currency->get_exchange_rates();

	$shipping = ($shipping * 1.2) * $exchange_rates[$currency];
	
	$price = $price + $shipping;
	//$price = (ceil($price / 10) * 10) -1;

	//error_log("Add Shipping Costs", 3, "~/jams_log.error");
	return $price;

}

function round_price($price, $product) {

	global $woocommerce_wpml;

	$is_whole = 0.001 > $price - floor($price);
	
	if (!$is_whole) {
	
		$price = ceil($price);
	
	}

	//$price = round($price, -1);
	return $price;

}

add_filter('woocommerce_get_price', 'add_shipping_costs', 10, 2);
add_filter('woocommerce_get_price', 'add_tax', 10, 2);
add_filter('woocommerce_get_price', 'round_price', 10, 2);
//add_filter('woocommerce_get_price_including_tax', 'add_shipping_costs', 10, 2);
//add_filter('woocommerce_get_price_including_tax', 'round_price', 10, 2);
//add_filter('woocommerce_get_price_excluding_tax', 'round_price', 10, 2);

function kino_currency_rates($rates_array) {

	$request = new WP_Http();
	
	$url = 'http://api.fixer.io/latest?base=GBP';

	$response = $request->get($url);

	if (!is_wp_error($response)) {

	$json = json_decode($response['body']);
	$rates = $json->rates;

	foreach ($rates as $currency => $rate) {

		if (isset($rates_array[$currency])) {

			$rates_array[$currency] = number_format($rate, 2);

		}

	}

	}

	return $rates_array;

}

//add_filter('wcml_exchange_rates', 'kino_currency_rates');

function filter_woocommerce_calc_tax($taxes, $price, $rates, $price_includes_tax, $suppress_rounding) {

	global $sitepress;

	//var_dump($taxes);
	if(!is_admin() && $sitepress->get_current_language()=="usa") {
	        $taxes = array();
	}	  
	return $taxes;

}

//add_filter('woocommerce_cart_totals_order_total_html', 'add_sales_tax_saving');

function add_sales_tax_saving($html) {

	if (wc_tax_enabled()) {

		$tax_string_array = array();

		if (get_option('woocommerce_tax_total_display') == 'itemized') {

			foreach( WC()->cart->get_tax_totals() as $code => $tax )
				$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label);

		} else {

			$tax_string_array[] = sprintf( '%s %s', wc_price(WC()->cart->get_taxes_total(true, true)), WC()->countries->tax_or_vat());

		}

		if (empty($tax_string_array)) {

			$html = $html . '<small class="exclusive_tax">Save 20% Sales Tax</small>';

		}

	}

	return $html;

}

add_filter('woocommerce_customer_default_location_array', 'set_default_location');

function set_default_location($location) {

	if (ICL_LANGUAGE_CODE === "usa") {

		$location['country'] = 'US';

	}

	return $location;

}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5849711469651',
	'title' => 'Lighting Settings',
	'fields' => array (
		array (
			'key' => 'field_584971227f0d9',
			'label' => 'Lighting',
			'name' => 'lighting',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 4,
			'max' => 4,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_584971347f0da',
					'label' => 'Intensity',
					'name' => 'intensity',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 0,
					'max' => '',
					'step' => '0.1',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_5849718e7f0db',
					'label' => 'X Position',
					'name' => 'x_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => 1,
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_584971a27f0dc',
					'label' => 'Y Position',
					'name' => 'y_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => 1,
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_584971c27f0dd',
					'label' => 'Z Position',
					'name' => 'z_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => 1,
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
		array (
			'key' => 'field_584e718c96efb',
			'label' => 'Ambient Colour',
			'name' => 'ambient_colour',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-lighting',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_58498cc14e203',
	'title' => 'Camera Settings',
	'fields' => array (
		array (
			'key' => 'field_58498cd7d7827',
			'label' => 'Camera',
			'name' => 'camera',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 1,
			'max' => 1,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array (
				array (
					'key' => 'field_58498cedd7828',
					'label' => 'X Position',
					'name' => 'x_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_58498d07d7829',
					'label' => 'Y Position',
					'name' => 'y_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_58498d2ad782a',
					'label' => 'Z Position',
					'name' => 'z_position',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-lighting',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

add_action('wp_head', 'important_styles', 100);

function important_styles() {

	$styles = "<style>" . file_get_contents(get_template_directory() . "/css/important.css") . "</style>";

	//$styles = "<style>";
	//$styles .= "body { background-color: white; padding: 0; margin: 0; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-weight: normal; line-height: 1.5; color: #0a0a0a; background: #fefefe; -webkit-font-smoothing: antialiased; } html,body { height: 100%; }";
	//$styles .= "</style>";

	echo $styles;
}

?>
