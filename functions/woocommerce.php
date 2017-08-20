<?php
/**
 * Filters here to be able to carpet-bomb them
 */
add_action( 'woocommerce_order_items_table', 'add_quality_to_order', 10, 1);
add_action( 'wc_stripe_icon', 'change_stripe_icon' );
add_filter( 'woocommerce_credit_card_form_fields', 'change_payment_fields' );
add_filter( 'woocommerce_terms_is_checked_default', 'check_terms' );
add_filter( 'woocommerce_billing_fields', 'change_billing_fields' );
add_filter( 'woocommerce_shipping_fields', 'change_shipping_fields' );
add_filter( 'woocommerce_checkout_fields', 'change_comment_fields' );
add_filter( 'woocommerce_cart_item_name', 'replace_item_name_in_cart', 10, 3 );
add_filter( 'woocommerce_order_item_name', 'replace_item_name_on_order', 10, 2 );
add_filter( 'woocommerce_subscriptions_thank_you_message', 'remove_thank_you_message' );
add_action( 'woocommerce_review_order_after_cart_contents', 'add_quality' );
// add_action( 'woocommerce_review_order_before_payment', 'add_notice_to_below_your_order_box' );
//add_action( 'woocommerce_checkout_order_review_custom', 'woocommerce_payment', 10 );


/**
 * Removing texts from here and there
 */
# Cart
remove_filter( 'woocommerce_cart_totals_fee_html', 'WC_Subscriptions_Cart::cart_totals_fee_html', 10 );
remove_filter( 'woocommerce_cart_product_subtotal', 'WC_Subscriptions_Cart::get_formatted_product_subtotal', 11 );
remove_filter( 'woocommerce_cart_subtotal', 'WC_Subscriptions_Cart::get_formatted_cart_subtotal', 11 );
remove_filter( 'woocommerce_cart_tax_totals', 'WC_Subscriptions_Cart::get_recurring_tax_totals', 11 );
remove_filter( 'woocommerce_cart_totals_taxes_total_html', 'WC_Subscriptions_Cart::get_taxes_total_html', 11 );
remove_filter( 'woocommerce_coupon_discount_amount_html', 'WC_Subscriptions_Cart::cart_coupon_discount_amount_html', 10 );
remove_filter( 'woocommerce_cart_total', 'WC_Subscriptions_Cart::get_formatted_total', 11 );


# Order
remove_filter( 'woocommerce_order_formatted_line_subtotal', 'WC_Subscriptions_Order::get_formatted_line_total', 10 );
remove_filter( 'woocommerce_order_subtotal_to_display', 'WC_Subscriptions_Order::get_subtotal_to_display', 10 );
remove_filter( 'woocommerce_get_order_item_totals', 'WC_Subscriptions_Order::get_order_item_totals', 10 );
remove_filter( 'woocommerce_order_cart_discount_to_display', 'WC_Subscriptions_Order::get_cart_discount_to_display', 10 );
remove_filter( 'woocommerce_get_formatted_order_total', 'WC_Subscriptions_Order::get_formatted_order_total', 10 );


/**
 * When the_post is called, put product data into a global.
 *
 * @param mixed $post
 * @return WC_Product
 */




//if ( ! function_exists( 'woocommerce_checkout_order_review_custom' ) ) {
/*function woocommerce_payment( $is_ajax = false ) {
	$current_path=getcwd();
	
	//wc_get_template( 'payment.php', array( 'checkout' => WC()->checkout(), 'is_ajax' => $is_ajax ),dirname($current_path) );
	$test=wc_get_template( 'checkout/review-order.php', array( 'checkout' => WC()->checkout(), 'is_ajax' => $is_ajax ) );
	//print_r (WC()->checkout());
	echo $test;
	}*/
//}


/**
 * Removing the country from both the billing and the shipping fieldsets on checkout
 *
 * hooked into
 * - woocommerce_billing_fields
 * - woocommerce_shipping_fields
 * @param  array 			$fields 		the original list as passed by woo
 * @return array         					the fields without the country
 */
function change_billing_fields( $fields ) {
	
	if(array_key_exists('first_name', $fields)) {
		$fields['first_name']['placeholder'] = "First Name";
	}

	if(array_key_exists('billing_address_1', $fields)) {
		$fields['billing_address_1']['label'] = "Address line 1";
		$fields['billing_address_1']['placeholder'] = "Address line 1";
	}

	if(array_key_exists('billing_address_2', $fields)) {
		$fields['billing_address_2']['label'] = "Address line 2 (optional)";
		$fields['billing_address_2']['placeholder'] = "Address line 2 (optional)";
		$fields['billing_address_2']['clear'] = true;
	}

	if(array_key_exists('billing_city', $fields)) {
		$fields['billing_city']['label'] = "City";
		$fields['billing_city']['placeholder'] = "City";
		$fields['billing_city']['class'] = array('form-row-first');
	}

	if(array_key_exists('billing_state', $fields)) {
		$fields['billing_state']['class'] = array('form-row-last');
		$fields['billing_state']['clear'] = true;
	}

	if(array_key_exists('billing_postcode', $fields)) {
		$fields['billing_postcode']['class'] = array('form-row-first');
		$fields['billing_postcode']['clear'] = true;
	}

	if(array_key_exists('billing_email', $fields)) {
		$fields['billing_email']['class'] = array('form-row-first');
		$fields['billing_email']['clear'] = false;
	}

	if( ! array_key_exists('billing_email_confirmation', $fields) ) {
		$fields['billing_email_confirmation'] = array(
			'label'     	=> __('Confirm Email Address', 'woocommerce'),
			'placeholder'   => _x('Confirm Email Address', 'placeholder', 'woocommerce'),
			'required'  	=> true,
			'class'     	=> array('form-row-last', 'validate-email', 'confirm-email'),
			'clear'     	=> true
		);
	}

	if(array_key_exists('billing_phone', $fields)) {
		$fields['billing_phone']['class'] = array('form-row-first');
		$fields['billing_phone']['clear'] = true;
	}

	$order = array(
		'billing_first_name',
		'billing_last_name',
		'billing_address_1',
		'billing_address_2',
		'billing_city',
		'billing_state',
		'billing_postcode',
		'billing_email',
		'billing_email_confirmation',
		'billing_phone'
	);
	foreach ($order as $field) {
		$ordered_fields[$field] = $fields[$field];
	}

	$fields = $ordered_fields;

	return $fields;
}

function change_shipping_fields( $fields ) {
	
	if(array_key_exists('shipping_address_1', $fields)) {
		$fields['shipping_address_1']['label'] = "Address line 1";
		$fields['shipping_address_1']['placeholder'] = "Address line 1";
	}

	if(array_key_exists('shipping_address_2', $fields)) {
		$fields['shipping_address_2']['label'] = "Address line 2 (optional)";
		$fields['shipping_address_2']['placeholder'] = "Address line 2 (optional)";
		$fields['shipping_address_2']['clear'] = true;
	}

	if(array_key_exists('shipping_city', $fields)) {
		$fields['shipping_city']['label'] = "City";
		$fields['shipping_city']['placeholder'] = "City";
		$fields['shipping_city']['class'] = array('form-row-first');
		$fields['shipping_city']['clear'] = false;
	}

	if(array_key_exists('shipping_state', $fields)) {
		$fields['shipping_state']['class'] = array('form-row-last');
		$fields['shipping_state']['clear'] = true;
	}

	if(array_key_exists('shipping_postcode', $fields)) {
		$fields['shipping_postcode']['label'] = "Postcode / Zip";
		$fields['shipping_postcode']['placeholder'] = "Postcode / Zip";
		$fields['shipping_postcode']['class'] = array('form-row-first');
		$fields['shipping_postcode']['clear'] = true;
	}

	$order = array(
		'shipping_first_name',
		'shipping_last_name',
		'shipping_address_1',
		'shipping_address_2',
		'shipping_city',
		'shipping_state',
		'shipping_postcode'
	);
	foreach ($order as $field) {
		$ordered_fields[$field] = $fields[$field];
	}

	$fields = $ordered_fields;

	return $fields;
}

function change_comment_fields( $fields ) {

	if(array_key_exists('order_comments', $fields['order'])) {
		$fields['order']['order_comments']['placeholder'] = "Order Notes";
	}

	return $fields;

}

function change_stripe_icon( $url ) {
	$url = get_stylesheet_directory_uri() . '/images/card-images.png';
	return $url;
}

function check_terms() {
	return true;
}

function remove_thank_you_message() {
	return '';
}

function change_payment_fields( $default_fields ) {
	
	$default_fields['card-number-field'] = str_replace('placeholder="•••• •••• •••• ••••"', 'placeholder="Card Number"', $default_fields['card-number-field']);
	$default_fields['card-expiry-field'] = str_replace('placeholder="' . __( 'MM / YY', 'woocommerce' ) . '"', 'placeholder="Expiration (' . __( 'MM/YY', 'woocommerce' ) . ')"', $default_fields['card-expiry-field']);
	$default_fields['card-expiry-field'] = str_replace('form-row-first', 'form-row-wide', $default_fields['card-expiry-field']);
	$default_fields['card-expiry-field'] = str_replace('Expiry (MM/YY)', 'Expiration (MM/YY)', $default_fields['card-expiry-field']);
	$default_fields['card-cvc-field'] = str_replace('placeholder="' . __( 'CVC', 'woocommerce' ) . '"', 'placeholder="Card Code (' . __( 'CVC', 'woocommerce' ) . ')"', $default_fields['card-cvc-field']);
	$default_fields['card-cvc-field'] = str_replace('form-row-last', 'form-row-wide', $default_fields['card-cvc-field']);
	$default_fields['card-cvc-field'] = str_replace('</p>', '<a href="javascript:" data-popup-target="cvcCode" id="cvcInfo" class="modal-popup circle-icon">?</a></p>', $default_fields['card-cvc-field']);

	 
	return $default_fields;

}

// REPLACES ALL PLACEHOLDER VALUES WITH LABEL VALUES AND THEN REMOVES LABELS.
// THIS ALLOWS FOR JVFLOAT.JS TO DO ITS THANNNNNG.
add_filter('woocommerce_checkout_fields','custom_wc_checkout_fields_no_label');

// Action: remove label from $fields
function custom_wc_checkout_fields_no_label($fields) {
	// loop by category
	foreach ($fields as $category => $value) {
		// loop by fields
		foreach ($fields[$category] as $field => $property) {
			// remove label property
			$fields[$category][$field]['placeholder'] = $fields[$category][$field]['label'];
			unset($fields[$category][$field]['label']);
		}
	}
	return $fields;
}

/**
 * Adds a smalll snippet of info under the review order box
 *
 * Hooked into
 * - woocommerce_review_order_before_payment
 */
/*
function add_notice_to_below_your_order_box() {
	?>
	<div class="shipping-notice">
		<p>Your order will be shipped on the next business day.</p>
	</div>
	<?php
	// do_action( '_es_add_startover' );
}
*/

/**
 * Replaces the name of the item with the size attribute assigned to it. It should work with different sizes across
 * the car
 * @param  string 			$title         	the original product name
 * @param  array 			$cart_item     	the data for the current item in the cart
 * @param  string 			$cart_item_key 	a hash assigned by woocommerce
 * @return string                			the new name for the product
 */
function replace_item_name_in_cart( $title, $cart_item, $cart_item_key ) {
	$name = 'attribute_pa_size';
	$value = $cart_item['variation']['attribute_pa_size'];

	if(array_key_exists($name, $cart_item['variation'])) {
		$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );
		if ( taxonomy_exists( $taxonomy ) ) {
			$term = get_term_by( 'slug', $value, $taxonomy );
			if ( ! is_wp_error( $term ) && $term && $term->name ) {
				$value = $term->name;
			}

			$label = wc_attribute_label( $taxonomy );

			// If this is a custom option slug, get the options name
		} else {

			$value              = apply_filters( 'woocommerce_variation_option_name', $value );
			$product_attributes = $cart_item['data']->get_attributes();
			if ( isset( $product_attributes[ str_replace( 'attribute_', '', $name ) ] ) ) {
				$label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $name ) ]['name'] );
			} else {
				$label = $name;
			}
		}
	}
	$value=str_replace("\"","",$value);
	return $value;
}


/**
 * Added via woocommerce_order_item_name
 *
 * Replaces the name
 * @param  string $name the original name of the item
 * @param  object $item WC_Product object
 * @return string       the nex name of the item
 */
function replace_item_name_on_order( $name, $item ) {
	if(!array_key_exists('pa_size', $item)) {
		return $name;
	}

	$value = $item['pa_size'];
	$taxonomy = wc_attribute_taxonomy_name( 'size' );

	if ( taxonomy_exists( $taxonomy ) ) {
		$term = get_term_by( 'slug', $value, $taxonomy );
		if ( ! is_wp_error( $term ) && $term && $term->name ) {
			$value = $term->name;
		}
	} else {
		return $name;
	}
	return $value;
}


/**
 * This adds a new line to the review order section about the quality
 * It needs to be wrapped in a <tr><td>thing</td></tr> at least
 *
 * Added to woocommerce_review_order_after_cart_contents
 */
function add_quality() {
	$quality = get_quality_from_cart( WC()->cart->get_cart() );
	echo '<tr class="cart_item"><td class="product-quality" colspan="2"><em>Quality:</em> <strong>' . $quality . '</strong></td><td>&nbsp;</td></tr>';
}


/**
 * Utility function to get the quality from the cart
 * @param  object $cart instance of WC Cart
 * @return string       Good, Better or Best
 */
function get_quality_from_cart( $cart ) {
	foreach ( $cart as $cart_item_key => $cart_item ) {
		$name = 'attribute_pa_quality';
		$value = $cart_item['variation']['attribute_pa_quality'];

		if(array_key_exists($name, $cart_item['variation'])) {
			$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );
			if ( taxonomy_exists( $taxonomy ) ) {
				$term = get_term_by( 'slug', $value, $taxonomy );
				if ( ! is_wp_error( $term ) && $term && $term->name ) {
					$value = $term->name;
				}

				$label = wc_attribute_label( $taxonomy );

				// If this is a custom option slug, get the options name
			} else {

				$value              = apply_filters( 'woocommerce_variation_option_name', $value );
				$product_attributes = $cart_item['data']->get_attributes();
				if ( isset( $product_attributes[ str_replace( 'attribute_', '', $name ) ] ) ) {
					$label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $name ) ]['name'] );
				} else {
					$label = $name;
				}
			}
		}

		break;
	}
	return $value;
}


/**
 * Added via a filter - woocommerce_order_items_table
 * @param object $order a WC_Order object
 */
function add_quality_to_order( $order ) {
	foreach ($order->get_items() as $key => $item) {

		if(!array_key_exists('pa_quality', $item)) {
			return;
		}

		$value = $item['pa_quality'];
		$taxonomy = wc_attribute_taxonomy_name( 'quality' );

		if ( taxonomy_exists( $taxonomy ) ) {
			$term = get_term_by( 'slug', $value, $taxonomy );
			if ( ! is_wp_error( $term ) && $term && $term->name ) {
				$value = $term->name;
			}
		} else {
			return;
		}
		break;
	}
	?>
	<tr class="quality_detail_page"><td colspan="2"><em>Quality:</em> <strong><?php echo strtoupper($value); ?></strong></td><td>&nbsp;</td></tr>
	<?php
}


/**
 * Adds the start over button to the bottom of step 2, 3 and below the review order
 */
function add_startover() {
	?>
	<div class="startover">
		<a href="<?php echo home_url(); ?>">Click here to start over</a>
	</div>
	<?php
}
add_action( '_es_add_startover', 'add_startover' );


/**
 * Changes the error message from terms and conditions to subscription agreement
 * @param  string 			$error 			the original error that is supposed to be displayed
 * @return string        					the changed one
 */
function change_error_message( $error ) {
	if( 'You must accept our Terms &amp; Conditions.' === $error ) {
		$error = 'You must accept our Subscription Agreement.';
		
	}
	
	if(('An account is already registered with that username. Please choose another.'===$error)||('An account is already registered with your email address. Please login.'===$error)){
		$error='You are our existed client. Please <a href="/my-account/">login.</a>';
		die('<script type="text/javascript">
		alert("Welcome back! Please log in to make your purchase.")
		window.location.href="/my-account";</script>');
	}
	return $error;
}
add_filter( 'woocommerce_add_error', 'change_error_message' );
