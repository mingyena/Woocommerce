<?php
/**
 * Checkout Payment Section
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! is_ajax() ) : ?><div id="order_review"><?php endif; ?>



	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

	


	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>

<?php if ( ! is_ajax() ) : ?></div><?php endif;
