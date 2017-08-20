<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

wc_print_notices(); ?>
<div style="position:relative">
	<p class="myaccount_user">
		<?php
		

		printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
			wc_customer_edit_account_url()
		);
		?>
	</p>
	<div class="sign-out">
	<?php 	

	printf(
			__( '<a href="%2$s" class="blue-button">Sign out</a>', 'woocommerce' ) . ' ',
			$current_user->display_name,
			wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
		);?>
	</div>
</div>
<div style="clear:both"></div>
<?php do_action( 'woocommerce_before_my_account' ); ?>

<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>
