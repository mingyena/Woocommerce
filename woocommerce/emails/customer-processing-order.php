<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action('woocommerce_email_header', $email_heading); ?>
<p><?php printf( __( 'Hello %s,', 'woocommerce' ), $order->billing_first_name . ' ' . $order->billing_last_name ); ?></p>
<p>Thank you for choosing QualityAirFilters.com! Your air filter order has been received and is now processing. A shipment notice email containing the FedEx tracking number will be emailed to you when your air filters are shipped.  For you reference, please see your order details below.   </p>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php //echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number(); ?></h2>
<p><strong><u>Order Details:</u></strong></p>

<table cellspacing="0" cellpadding="6" style="width: 100%; ;" border="0" bordercolor="#eee">


	<tfoot>
		<?php
		$order_detail= ($order->get_items()); 
		
			$letter="A";
			foreach($order_detail as $singledetail=>$key){
				//print_r ($key);
				if(isset($key['pa_size'])){
				$product_name=$key['pa_size'];
				
				$product_name=str_replace("-"," ",$product_name);
				$product_name=str_replace("x","X",$product_name);
				echo "<tr><td scope='row'colspan='3' style='text-align:left; '>".$letter." Filter-".$product_name."</td></tr>";
				}
				else{
				echo "<tr><td scope='row'colspan='3' style='text-align:left; '>".$letter." Filter-".$key['name']."</td></tr>";
				}
				$letter++;
				$product_qty=$key['pa_quality'];
			}
			
			echo "<tr><td scope='row'colspan='3' style='text-align:left; '>Quality:$product_qty</td></tr>";
			
			if ( $totals = $order->get_order_item_totals() ) {
				
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
										
					if($total['label']=='Shipping:'){
					$shipping=$total['value'];
					}
					
					if($total['label']=='Sales Tax:'){
					$sales_tax= $total['value'];
					}
					if($total['label']=='Total:'){
					$total_price=$total['value'];
					}
					
				}
				$subtotal=$order->get_subtotal();
			echo "
					<tr>
						<td width='200px' scope='row' style='text-align:left; '>&nbsp;</td>
						<td>Item Subtotal: </th>
						<td style='text-align:left; '>$".$subtotal."</td>
					</tr>";
		
			
			$discount= $order->get_discount_to_display();
			echo "
					<tr>
						<td width='200px' scope='row' style='text-align:left; '>&nbsp;</td>
						<td>Cart Discount: </th>
						<td style='text-align:left; '>".$discount."</td>
					</tr>";
			echo "
					<tr>
						<td width='200px' scope='row' style='text-align:left; '>&nbsp;</td>
						<td>Shipping & Handling: </th>
						<td style='text-align:left; '><strong>". $shipping."!!</strong></td>
					</tr>";
			echo "
					<tr>
						<td width='200px' scope='row' style='text-align:left; '>&nbsp;</td>
						<td>Sales Tax: </th>
						<td style='text-align:left; '>".$sales_tax."</td>
					</tr>";
			echo "
					<tr>
						<td width='200px' scope='row' style='text-align:left; '>&nbsp;</td>
						<td><strong>Total:</strong> </td>
						<td style='text-align:left; '><strong>".$total_price."</strong></td>
					</tr>";
					
			}
			
		?>
	</tfoot>
</table>
<p>To manage or update your account please visit <a href="https://www.qualityairfilters.com/my-account">https://www.qualityairfilters.com/my-account</a>. If you need to make any changes to this order, please email us at <a href="mailto:support@QualityAirFilters.com">support@QualityAirFilters.com</a>.</p>
<p>Thank you for ordering from QualityAirFilters.com!</p>

<p>The QualityAirFilters.com Team </p>


<?php do_action( 'woocommerce_email_footer' ); ?>