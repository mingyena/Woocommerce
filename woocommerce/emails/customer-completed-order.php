<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>
<p><?php printf( __( 'Hello %s,', 'woocommerce' ), $order->billing_first_name . ' ' . $order->billing_last_name ); ?></p>
<p>Good news, your air filters are on their way! Your order has been shipped via FedEx SmartPost; you will receive an email containing the FedEx tracking number shortly. Below is the order detail for your reference: </p>
<?php 

$shipping=preg_split("/<br[^>]*>/i",($order->get_formatted_shipping_address()));
$shipping_address=("<br/>".($shipping[1])."<br/>".($shipping[2]).", ".($shipping[3])." ".($shipping[4]));
?>

<p>Your order was sent to:<?php  echo $shipping_address;?></p>
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
<p> If you have any questions or concerns with your air filter order, please email us at <a href="mailto:support@QualityAirFilters.com">support@QualityAirFilters.com</a>. </p>
<p>Thank you for ordering from <a href="http://www.QualityAirFilters.com">QualityAirFilters.com!</a> </p>
<p>The QualityAirFilters.com<br/>
Team Support@QualityAirFilters.com</p>



<?php do_action( 'woocommerce_email_footer' ); ?>