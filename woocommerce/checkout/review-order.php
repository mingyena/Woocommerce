<?php
/**
 * Review order table
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
	
	<table class="shop_table_1" style="display:none;">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tfoot>

			<tr class="cart-subtotal">
				<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

			<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
				<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php _e( 'Order Total', 'woocommerce' ); ?></th>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
		<tbody>
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td class="product-name">
								<?php // echo apply_filters( 'woocommerce_cart_item_name', str_replace($_product->get_title()), $cart_item, $cart_item_key ); ?>
								<?php // [JJ - 03/25/2015] Why was str_replace used above? ?>
								<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
							</td>
							<td class="product-quantity">
								<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong>' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
								<?php //echo WC()->cart->get_item_data( $cart_item ); ?>
							</td>
							<td class="product-total">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
							</td>
						</tr>
						<?php
					}
				}

				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

	<div id="payment">
		<?php if ( WC()->cart->needs_payment() ) : ?>
		<div class="secure_payment"><p class="with-lock">Payment secure by 256-bit SSL encryption</p></li></div>
		<ul class="payment_methods methods">
				
			<?php
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}

					foreach ( $available_gateways as $gateway ) {
						?>
						<li class="payment_method_<?php echo $gateway->id; ?>">
						<p>Secure card payment</p>
							<input id="payment_method_<?php echo $gateway->id; ?>" checked="checked" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
							<label for="payment_method_<?php echo $gateway->id; ?>"> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									$gateway->payment_fields();
									//print_r ($gateway);
									echo '</div>';
								endif;
							?>
						</li>
						<?php
					}
				} else {

					if ( ! WC()->customer->get_country() )
						$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
					else
						$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );

					echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

				}
			?>
		</ul>
		<div id="cvcCode" class="reveal-modal reveal-small-window">
		
		<p><img src="/wpcms/wp-content/themes/qaf_theme/images/cvc_card.gif"/ style="float:left; padding-right:1em"></p>
		<a class="close-reveal-modal" href="javascript:" onclick="classToggle('open', '#cvcCode')" data-reveal-id="cvcCode">×</a>
		</div>
		<?php endif; ?>

		<div class="form-row place-order">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

			<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php
			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );

			echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>

			<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) {
				$terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) );
				$purchase_ID= $_SESSION['purchase_ID'];
				$content_post = get_post($purchase_ID);	
				?>
				<p class="form-row terms">
			<div id="purchase-agreement" class="reveal-modal">
				<h2><?php echo get_the_title( $purchase_ID ); ?></h2>
				<?php echo apply_filters('the_content', $content_post->post_content); ?>
				<a class="close-reveal-modal" href="javascript:" onclick="classToggle('open', '#purchase-agreement')" data-reveal-id="purchase-agreement">&#215;</a>
			</div>		
					<label for="terms" class="checkbox purchase_agreement">By clicking the Place Order Button you consent to our <a href="javascript:" class="modal-popup agreementLink" data-popup-target="purchase-agreement" data-reveal-id="purchase-agreement" >Purchase Agreement</a>	</label>
					<input type="checkbox" class="input-checkbox" name="terms" id="terms" checked/>
				</p>
			<?php } ?>

			
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
			
						   
		</div>
		</div>
		<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
		
		<!--<div class="clear"></div>-->
		<div class="shop_table_outter">
			<div class="shop_table open">
				<a id="shopTableToggle" href="javascript:" onclick="classToggle('open', 'div.shop_table')">Show / Hide order summary</a>
				<h3 id="order_review_heading">Your order summary</h3>
				<table class="shop_table">
				<thead>
					
					<tr>
						<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>				
						<th class="quantity-shipping-info"><?php _e( 'QTY', 'woocommerce' ); ?></th>
						<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
					</tr>
				</thead>
				<tfoot>

					<tr class="cart-subtotal">
						<th colspan="2"><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
						<td><?php wc_cart_totals_subtotal_html(); ?></td>
					</tr>

					<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
						<tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?>">
							<th colspan="2"><?php if(esc_attr( $code )=="morethan3items"){echo "20% Discount";} else{wc_cart_totals_coupon_label( $coupon );} ?></th>
							<td><?php if(esc_attr( $code )=="morethan3items"){$discAmount = WC()->cart->get_coupon_discount_amount( $code, WC()->cart->display_cart_ex_tax ); echo "- " . wc_price($discAmount);} else{wc_cart_totals_coupon_html( $coupon );} ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

						<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
						
						<?php wc_cart_totals_shipping_html(); ?>
						
						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

					<?php endif; ?>
						
					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
						<tr class="fee">
							<th colspan="2"><?php echo esc_html( $fee->name ); ?></th>
							<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr class="total_before_tax"><th colspan="2">TOTAL BEFORE TAX</th><td><span class="amount"><?php wc_cart_totals_subtotal_html(); ?></span></td>
					<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
						<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
									<th colspan="2"><?php echo esc_html( $tax->label ); ?></th>
									<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="tax-total">
								<th colspan="2"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
								<td><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></td>
							</tr>
						<?php endif; ?>
					<?php endif; ?>

					<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
						<tr class="order-discount coupon-<?php echo esc_attr( $code ); ?>">
							<th colspan="2"><?php if(esc_attr( $code )=="morethan3items"){echo "20% Discount";} else{wc_cart_totals_coupon_label( $coupon );} ?></th>
							<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

					<tr class="order-total">
						<th colspan="2"><?php _e( 'Order Total', 'woocommerce' ); ?></th>
							<?php
							$count=0;
							$count=(WC()->cart->cart_contents_count);
							WC()->cart->remove_coupon("MORETHAN3ITEMS");
							if ( $count >2 ) {
							
							//if(!WC()->cart->has_discount("MORETHAN3ITEMS")){
									WC()->cart->add_discount("MORETHAN3ITEMS");
								//}
							
							}
							else{
							WC()->cart->remove_coupon("MORETHAN3ITEMS");
						}

							?>
						
						<td><?php WC()->cart->calculate_totals(); echo WC()->cart->get_total();?></td>
					</tr>

					<?php if(array_key_exists('future_shipment', $_SESSION) && !empty($_SESSION['future_shipment'])) {
						
						if($_SESSION['future_shipment']!=""){
						   $process_date=$_SESSION['future_shipment'];
						   $today=time();
						   $monthdate=date('m/d/Y',strtotime("+$process_date months", $today));
						   
						   if (($pos = strpos($_SESSION['total_price_product'], "then")) !== FALSE) { $product_price = substr($_SESSION['total_price_product'], $pos+32,5);}
						   $product_price=number_format(($product_price*1.0825), 2, '.', '');
						echo '<tr class="future-shipment-note"><td colspan="3"><strong>Note:</strong>You have chosen to have your filter(s) shipped on '.$monthdate.'. Your credit card will be charged $'.$product_price.' which includes tax on '.$monthdate.'.</td></tr>';
						echo '<style>tr.cart-subtotal,tr.shipping,tr.tax-rate,#couponContainer{display:none;}</style>';
						echo "<script>$( 'table.shop_table tr.order-total th' ).replaceWith( '<th colspan=\'2\'>TODAY\'S PAYMENT</th>' );
							$('h3.checkout_header').replaceWith('<h3>Create an account</h3>');</script>";
						}
					} ?>

					<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

				</tfoot>
				<tbody>
					<?php
						do_action( 'woocommerce_review_order_before_cart_contents' );

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							//print_r ($cart_item);
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
									<?php echo "<td class='product-name'>";
										 
										$product_name= apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
										$product_name = preg_replace('/\s+/', '', $product_name);
										echo $product_name."<br>";
										
										
									echo "</td>";?>
									
									<td class="product-quantity">
										<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong>' . sprintf( '%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
										<?php //echo WC()->cart->get_item_data( $cart_item ); ?>
									</td>

									<td class="product-total">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
										
									</td>
								</tr>
								<?php
							}
						}

						do_action( 'woocommerce_review_order_after_cart_contents' );
					?>
				</tbody>
			</table>

		<?php woocommerce_checkout_coupon_form(); ?>
		<table class="delivery_freq">
		<tr class="delivery_freq_tr">
		<th colspan="2">DELIVERY FREQUENCY</th>
		<?php if( array_key_exists( 'woo-airfilter-frequency', $_REQUEST ) ) {
		?>
		<td class="second_td">Every <?php $freqMonth=$_REQUEST['woo-airfilter-frequency']; echo "$freqMonth months";?></td>
		<?php
		}
		?>
		</tr>
		</table>
		</div>
	
	</div><!--table-->
	

	


<?php if ( ! is_ajax() ) : ?></div><?php endif;?>
