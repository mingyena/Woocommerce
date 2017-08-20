<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">
	<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

		<?php
			if ( empty( $_POST ) ) {

				$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
				$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

			} else {

				$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

			}
		?>

		<div id="ship-to-different-address">
			<label for="ship-to-different-address-checkbox" class="checkbox">
				<input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( $ship_to_different_address, 0 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
				<?php _e( 'ship to a different address', 'woocommerce' ); ?>
			</label>
			<script>
				jQuery('#ship-to-different-address-checkbox').on('click', function() {
					if(jQuery('#ship-to-different-address-checkbox').is(':checked')) {
						jQuery('.shipping_address').slideDown();
					}
					else {
						jQuery('.shipping_address').slideUp();
					}
				});
			</script>
		</div>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>
				<?php // Filter out Shipping Company field. ?>
				<?php if ($key == "shipping_company") : ?>

				<?php else : ?>

				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

				<?php endif; ?>

			<?php endforeach; ?>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>

	<label><input type="checkbox" id="addOrderNotes" name="addOrderNotes"></input> add delivery notes</label>
	<script>
		jQuery('#addOrderNotes').on('click', function() {
			if(jQuery('#addOrderNotes').is(':checked')) {
				jQuery('#order-notes-container').slideDown();
			}
			else {
				jQuery('#order-notes-container').slideUp();
			}
		});
	</script>

	<div id="order-notes-container" style="display:none;">
		<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : ?>

			<?php if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

				<h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

			<?php endif; ?>

			<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

			<?php endforeach; ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
	</div>
</div>
