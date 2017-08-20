<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce;
if(isset(get_userdata(get_current_user_id( ))->user_login)){
$user_login=(get_userdata(get_current_user_id( ))->user_login);
$_SESSION['user_login']=$user_login;
}

wc_print_notices();

// do_action( 'woocommerce_before_checkout_form', $checkout );
do_action( 'woocommerce_checkout_login_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>
<?php 

$_SESSION['purchase_ID']=$_REQUEST['purchase_agreement_id'];
$_SESSION['process_date']="";
if(array_key_exists( 'change_ship_date', $_REQUEST ) && $_REQUEST['change_ship_date']=="on"){
	if(isset($_REQUEST['custom_order_date'])){
		$_SESSION['process_date']=$_REQUEST['custom_order_date'];
		}

}
//$test=$woocommerce->cart->get_coupon_discount_amount("breezy");
//echo $test;
$test=WC()->cart->get_coupons();
//print_r ($test);

foreach (  $test as $code => $coupon ){
       WC()->cart->remove_coupon( $code );
}
?>
<?php
	$count=0;
	$count=(WC()->cart->cart_contents_count);
	
	WC()->cart->remove_coupon("MORETHAN3ITEMS");
	if ( $count >2 ) {
	echo "<div class='coupon_applied'>20% Discount Applied</div>";
	}
	
	?>

<form data-persist="garlic" name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">

	<div class="user_billing_info">

		<?php
		do_action( 'woocommerce_checkout_before_customer_details' );
		if ( sizeof( $checkout->checkout_fields ) > 0 ) {
			do_action( 'woocommerce_checkout_billing' );
			do_action( 'woocommerce_checkout_shipping' );
		}

		do_action( 'woocommerce_checkout_after_customer_details' );
		?>
	</div>

	

		
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		
	<div class="billing_info"></div>

</form>
<script>

  $( 'form.checkout input.input-text' ).blur(function(e){
 var $parent=$(this).closest( '.form-row' );
 var validated = true;
			if ( $parent.is( '.validate-required' ) ) {
				if ( $(this).val() === '' ) {
					
					validated = false;
				}
			}

			if ( $parent.is( '.validate-email' ) ) {
				if ( $(this).val() ) {

					/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

					if ( ! pattern.test( $(this).val()  ) ) {
						
						validated = false;
					}
				}
			}
			if ( validated ) {
				$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field' ).addClass( 'woocommerce-validated' );
			}
 });
</script>
<?php

do_action( 'woocommerce_after_checkout_form', $checkout );
