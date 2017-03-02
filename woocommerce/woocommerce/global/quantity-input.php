<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php /*
<div class="ze-quantity quantity <?php echo esc_attr( $class_name ); ?>">
    <div class="ze-minus fff"><p class="ddd" href="#" data-multi="-1">-</p></div>
    <div class="ze-input">
		<input type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="quntity-input input-text qty text" size="4" />
    </div>
    <div class="ze-plus fff"><p class="ddd" href="#" data-multi="1">+</p></div>
	
	<script>
	$(".ddd").on("click", function () {
    var $button = $(this);
    var $input = $button.closest('.ze-quantity').find("input.quntity-input");
    
    $input.val(function(i, value) {
		console.log(i);
        return +value + (1 * +$button.data('multi'));
    });
});
	</script>


</div>
*/ ?>


<?php if ( is_cart() ) { ?>
	<div class="ze-quantity-<?php echo esc_attr( $class_name ); ?> quantity">
		<div class="ze-minus fff"><p class="ddd-<?php echo esc_attr( $class_name ); ?>" href="#" data-multi="-1">-</p></div>
		<div class="ze-input">
			<input type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="quntity-input-<?php echo esc_attr( $class_name ); ?> input-text qty text" size="4" />
		
		</div>
		<div class="ze-plus fff"><p class="ddd-<?php echo esc_attr( $class_name ); ?>" href="#" data-multi="1">+</p></div>
		
		<!--script>
		
		var $js_class_name = '<?php echo esc_attr( $class_name ); ?>';

		$(".ddd-" + $js_class_name).on("click", function () {
		console.log(".ddd-" + $js_class_name);
		var $button = $(this);
		var $input = $button.closest('.ze-quantity-' + $js_class_name).find("input.quntity-input-" + $js_class_name);
		
		$input.val(function(i, value) {

			var total = +value + (1 * $button.data('multi'));
			
			console.log(total);
			return +value + (1 * +$button.data('multi'));
		});
	});
		</script-->
	</div>
<?php } else { ?>
	<div class="ze-quantity quantity">
		<div class="ze-minus fff"><div class="ddd" href="#" data-multi="-1">-</div></div>
		<div class="ze-input">
			<input type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="quntity-input input-text qty text" size="4" />
		
		</div>
		<div class="ze-plus fff"><div class="ddd" href="#" data-multi="1">+</div></div>
		
		<!--script>

		$(".ddd").on("click", function () {
		var $button = $(this);
		var $input = $button.closest('.ze-quantity').find("input.quntity-input");
		
		$input.val(function(i, value) {
			return +value + (1 * +$button.data('multi'));
		});
	});
		</script-->
	</div>

<?php } ?>
