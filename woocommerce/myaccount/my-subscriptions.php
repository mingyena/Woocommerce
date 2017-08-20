<?php
/**
 * My Subscriptions
 */
?>

<h2><?php _e( 'My Subscriptions', 'woocommerce-subscriptions' ); ?></h2>

<?php
if ( ! empty( $subscriptions ) ) {
	?>
	<table class="shop_table my_account_subscriptions my_account_orders">
		<thead>
			<tr>
				<th class="subscription-order-number"><span class="nobr"><?php _e( 'Order', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-title"><span class="nobr"><?php _e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="qty-title"><span class="nobr"><?php _e( 'QTY', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-status"><span class="nobr"><?php _e( 'Status', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-next-payment"><span class="nobr"><?php _e( 'Next Shipment / Payment', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-end"><span class="nobr"><?php _e( 'End Date', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-actions"><span class="nobr"><?php _e( 'Actions', 'woocommerce-subscriptions' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			// wp_die( es_preit( array( $subscriptions ), false ) );
			foreach ( array_reverse( $subscriptions ) as $subscription_key => $subscription_details ) {

				$order = new WC_Order( $subscription_details['order_id'] );
				?>

				<tr class="order">

					<td class="order-number" width="1%" data-title="<?php _e( 'Order', 'woocommerce-subscriptions' ); ?>">
						<?php
						if ( method_exists( $order, 'get_view_order_url' ) ) {
							// WC 2.1+
							?>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"><?php echo $order->get_order_number(); ?></a>
							<?php
						} else {
							?>
							<a href="<?php echo esc_url( add_query_arg( 'order', $subscription_details['order_id'], get_permalink( woocommerce_get_page_id( 'view_order' ) ) ) ); ?>"><?php echo $order->get_order_number(); ?></a>
							<?php
						}
						?>
					</td>

					<td class="subscription-title" data-title="<?php _e( 'Subscription', 'woocommerce-subscriptions' ); ?>">
						<p>
							<?php
							if ( sizeof( $order->get_items() ) > 0 ) {
								foreach( $order->get_items() as $item ) {
									echo replace_item_name_on_order( '', $item ) .'<br>';
								}
							}
							?>
						</p>
					</td>
					<td class="subscription-title" data-title="<?php _e( 'QTY', 'woocommerce-subscriptions' ); ?>">
						<p>
							<?php
							if ( sizeof( $order->get_items() ) > 0 ) {
								foreach( $order->get_items() as $item ) {
									echo $item['item_meta']['_qty'][0] . '<br>';
								}
							}
							?>
						</p>
					</td>

					<td class="subscription-status" style="text-align:left; white-space:nowrap;" data-title="<?php _e( 'Status', 'woocommerce-subscriptions' ); ?>">
						<?php echo WC_Subscriptions_Manager::get_status_to_display( $subscription_details['status'], $subscription_key, $user_id ); ?>
					</td>

					<td class="subscription-next-payment" data-title="<?php _e( 'Next Payment', 'woocommerce-subscriptions' ); ?>">
						<?php
						$next_payment_timestamp = WC_Subscriptions_Manager::get_next_payment_date( $subscription_key, $user_id, 'timestamp' );
						if ( $next_payment_timestamp == 0 ) {
							echo '-';
						} else {

							$time_diff = $next_payment_timestamp - gmdate( 'U' );

							if ( $time_diff > 0 && $time_diff < 7 * 24 * 60 * 60 ) {
								$next_payment = sprintf( __( 'In %s', 'woocommerce-subscriptions' ), human_time_diff( $next_payment_timestamp ) );
							} else {
								$next_payment = date_i18n( woocommerce_date_format(), $next_payment_timestamp );
							}
							?>
						<time title="<?php echo esc_attr( $next_payment_timestamp ); ?>">
							<?php echo $next_payment; ?>
						</time><br/>
							<?php
							if ( ! empty ( $order->recurring_payment_method_title ) ) {
								$payment_method_to_display = sprintf( __( 'Via %s', 'woocommerce-subscriptions' ), $order->recurring_payment_method_title );
								?>
								<small>
									<?php echo apply_filters( 'woocommerce_my_subscriptions_recurring_payment_method', $payment_method_to_display, $subscription_details, $order ) ; ?>
								</small>
								<?php
							}
						}
						?>
					</td>

					<td class="subscription-end" data-title="<?php _e( 'End Date', 'woocommerce-subscriptions' ); ?>">
						<?php if ( $subscription_details['expiry_date'] == 0 && ! in_array( $subscription_details['status'], array( 'cancelled', 'switched' ) ) ) : ?>
								<?php _e( 'When Cancelled', 'woocommerce-subscriptions' ); ?>
						<?php else : ?>
							<?php if ( in_array( $subscription_details['status'], array( 'cancelled', 'switched' ) ) ) : ?>
								<?php $end_of_prepaid_term = wc_next_scheduled_action( 'scheduled_subscription_end_of_prepaid_term', array( 'user_id' => (int)$user_id, 'subscription_key' => $subscription_key ) ); ?>
								<?php if ( false === $end_of_prepaid_term ) : ?>
									<?php $end_timestamp = strtotime( $subscription_details['end_date'] ); ?>
								<?php else : ?>
									<?php $end_timestamp = $end_of_prepaid_term; ?>
								<?php endif; ?>
							<?php else : ?>
								<?php $end_timestamp = strtotime( $subscription_details['expiry_date'] ); ?>
							<?php endif; ?>
							<?php $time_diff = $end_timestamp - gmdate( 'U' ); ?>
							<?php if ( absint( $time_diff ) > 0 && absint( $time_diff ) < 7 * 24 * 60 * 60 ) : ?>
								<?php if ( $time_diff > 0 ) : // In the future ?>
									<?php $expiry = sprintf( __( 'In %s', 'woocommerce-subscriptions' ), human_time_diff( $end_timestamp ) ); ?>
								<?php else : // In the past ?>
									<?php $expiry = sprintf( __( '%s ago', 'woocommerce-subscriptions' ), human_time_diff( $end_timestamp ) ); ?>
								<?php endif; ?>
							<?php else : ?>
								<?php $expiry = date_i18n( woocommerce_date_format(), $end_timestamp ); ?>
							<?php endif; ?>
							<time title="<?php echo esc_attr( $end_timestamp ); ?>">
								<?php echo $expiry; ?>
							</time>
						<?php endif; ?>
					</td>

					<td class="subscription-actions order-actions" data-title="<?php _e( 'Actions', 'woocommerce-subscriptions' ); ?>">
						<?php
						foreach( $actions[ $subscription_key ] as $key => $action ) {

							if($key === 'cancel') {
								continue;
							}
							?>
							<a href="<?php echo esc_url( $action['url'] ); ?>" class="button <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name'] ); ?></a>
							<?php
						}
						?>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>

	</table>

	<?php
} else {
	?>
	<p><?php printf( __( 'You have no active subscriptions. ') ); ?></p>
	<?php
}
