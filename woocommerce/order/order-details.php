<?php
/**
 * Order details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$order = wc_get_order( $order_id );
global $woocommerce;
$customer_country = $woocommerce->customer->get_address();

?>
<h3><?php _e( 'Order Details', 'woocommerce' ); ?></h3>
<div class="shop_table" style="position:static;">
	<h4 id="order_review_heading">Your order summary</h4>

	<table class="shop_table order_details">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-qyt"><?php _e( 'QTY', 'woocommerce' ); ?></th>
				<th class="product-total second_td"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( sizeof( $order->get_items() ) > 0 ) {
				$i = 0;
				$len = count($order->get_items());
				foreach( $order->get_items() as $item ) {
					$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
					$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
					

					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<td class="product-name">
							<?php
								if ( $_product && ! $_product->is_visible() )
									echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
								else
									echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );

								
								//$item_meta->display();

								if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

									$download_files = $order->get_item_downloads( $item );
									$i              = 0;
									$links          = array();

									foreach ( $download_files as $download_id => $file ) {
										$i++;

										$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
									}

									echo '<br/>' . implode( '<br/>', $links );
								}
							?>
						</td>
						<td><?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '%s', $item['qty'] ) . '</strong>', $item );?>
						</td>
						<td class="product-total second_td" >
							<?php echo $order->get_formatted_line_subtotal( $item ); ?>
						</td>
					</tr>
					<?php

					if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
						?>
						<tr class="product-purchase-note">
							<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
						</tr>
						<?php
					}
				}
				
			}
					do_action( 'woocommerce_order_items_table', $order );
			
			?>
		</tbody>
		<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
				//print_r ($total);
				if($total['label']!='Payment Method:'){
				
				?>
				<tr class="<?php echo 'order_details-' . str_replace(':', '', str_replace(' ', '-', $total['label'])); ?>">
					
				<?php
					//echo $total['label'];
					if($total['label']=='Order Total:'){
				?>
					<th scope="row total_detail" colspan="2"><strong><?php echo (str_replace(':','',$total['label'])); ?></strong></th>
					<td class="second_td total_detail"><strong><?php echo $total['value']; ?></strong></td>
				<?php
					}
					elseif($total['label']=='Shipping:'){
				?>
					<th scope="row total_detail" colspan="2"><?php echo (str_replace(':','',$total['label'])); ?></th>
					<td class="second_td total_detail"><strong><?php echo $total['value']; ?></strong></td>
				<?php
					}
					elseif($total['label']=='Order Discount:'){ 
				?>	
					<th scope="row total_detail" colspan="2"><?php echo (str_replace(':','',$total['label'])); ?></th>
					<td class="second_td total_detail"><?php $amount=$total['value'];$end=strrpos($amount,'up'); $tempstring=substr($amount,0,$end); echo (str_replace('-','',$tempstring))."off your initial order"; ?></td>
				<?php
					}
					else{?>
					<th scope="row" colspan="2"><?php echo strtoupper(str_replace(':','',$total['label'])); ?></th>
					<td class="second_td"><?php echo $total['value']; ?></td>
				<?php
					}
				?>
				</tr>
				
				<?php
				}
			endforeach;
		?>
		</tfoot>
	</table>
</div>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); 
$schedule=($order->get_items());
$singleschedule=reset($schedule);
$item_meta=($singleschedule['item_meta']);
$item_interval=$item_meta['_subscription_interval'][0];

//$singleSchedule=$schedule[0];
?>
<h3><?php _e( 'Schedule', 'woocommerce' );?></h3>
<div class="schedule-detail">

<table class="schedule-detail-table">
<tr>
<td>FIRST SHIPMENT</td>
<?php
if (!(isset($_SESSION['process_date'])) || $_SESSION['process_date']==""){
?>
<th class="second_td">within 3 business days</th>
<?php
}
else{
$today=time();
$process_date=$_SESSION['process_date'];
//echo "<th class='second_td'>$process_date</th>";
$monthdate=date('Y-m-d',strtotime("+$process_date months", $today));
echo "<th class='second_td'>$monthdate</th>";
}
?>
</tr>
<tr>
<td>FREQUENCY</td>
<th class="second_td" style="text-align:left"><?php if($item_interval==0){echo "One time order";}else{echo "every ".($item_interval)." months";}?> </th>
</tr>
</table>
</div>
</div><!-- checkout_detail-->
<div class="checkout_detail">
<h3><?php _e( 'Customer details', 'woocommerce' ); ?></h3>
<div class="customer-details">
	<h4>Your details</h4>
	<table class="customer-details-table">
	<?php
		if ( $order->billing_email ) echo '<tr><td class="customer-details-email"><strong>' . __( 'Email', 'woocommerce' ) . '</strong><br />' . $order->billing_email . '</td></tr>';
		if ( $order->billing_phone ) echo '<tr><td class="customer-details-phone"><strong>' . __( 'Phone', 'woocommerce' ) . '</strong><br />' . $order->billing_phone . '</td></tr>';

		// Additional customer details hook
		do_action( 'woocommerce_order_details_after_customer_details', $order );
	?>
	</table>


	<h4><?php _e( 'Billing Address', 'woocommerce' ); ?></h4>
	<div class="customer-details-billing-address">
		<address>
			<?php
				if ( !$order->get_formatted_billing_address () ) _e( 'N/A', 'woocommerce' ); 
				//$billinginfo=$customer_country;
				//$billing_info=explode("<br>",'',($order->get_formatted_billing_address()));
				$billing=preg_split("/<br[^>]*>/i",($order->get_formatted_billing_address()));
				echo ($billing[0])."<br/>".($billing[1])."<br/>".($billing[2])." ".($billing[3])." ".($billing[4]);
				//print_r ($billing);
				//$billing_info=preg_replace('/,/',' ',$billing_info);
				//echo $billing_info;
				//echo ($order->get_formatted_billing_address());
			?>
		</address>
	</div>

	<h4><?php _e( 'Shipping Address', 'woocommerce' ); ?></h4>
	<div class="customer-details-billing-address">
		<address>
			<?php
				if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); 
				else 
				//$test=$order;
				//print_r ($test);
				$shipping=preg_split("/<br[^>]*>/i",($order->get_formatted_shipping_address()));
				echo ($shipping[0])."<br/>".($shipping[1])."<br/>".($shipping[2])." ".($shipping[3])." ".($shipping[4]);
				
				
			?>
		</address>
	</div>

</div>
</div> <!--checkout_detail-->
<!-- /.customer-details -->

<div class="clear"></div>
