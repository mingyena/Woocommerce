<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>
<?php
	$count=0;
	$count=(WC()->cart->cart_contents_count);
	if ( $count >2 ) {
	
	//if(!WC()->cart->has_discount("MORETHAN3ITEMS")){
			WC()->cart->add_discount("MORETHAN3ITEMS");
		//}
	
	}
	else{
	WC()->cart->remove_coupon("MORETHAN3ITEMS");
}

	?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : 
		$orderID=str_replace("#","",$order->get_order_number());
		$firstshipment="";
		$Singleorder = new WC_Order( $orderID );
		//Check if this is a new user
		$user_login=(get_userdata(get_current_user_id( ))->user_login);
		if(array_key_exists('user_login', $_SESSION)) {
			$user_login_previous=$_SESSION['user_login'];
		}
		
		$user_email=($order->billing_email);
		//echo "user login<br/>".$user_login."<br/>user email<br/>".$user_email."<br/>user login previous<br/>".$user_login_previous;
		if(($user_login=$user_email)&&empty($user_login_previous)){
			$_SESSION['user_login']=$user_login;
		}
		
		$productID=array_values($Singleorder->get_items())[0]['item_meta']['_product_id'][0];
		$subscription=new WC_Subscriptions_Manager;
		$sub_key= $subscription->get_subscription_key($orderID,$productID);
		$start_date=($subscription->get_subscription($sub_key)['start_date']);
		$today=time();
		$postdate=date("Y-m-d");
		$nextdate=date('Y-m-d', strtotime(' +1 day'));
		
		if(isset($_SESSION['future_shipment'])&&($_SESSION['future_shipment']!="")){
		 $process_date=$_SESSION['future_shipment'];
		 
		 //print_r (get_post_meta($orderID));
		 //print_r ($Singleorder);
		 $monthdate=date('Y-m-d',strtotime("+$process_date months", $today));
		 //add_post_meta($orderID, 'Process Date', $monthdate);
		//future order fixed
		  wc_delete_order_item_meta($orderID, 'Process Date', '',true);
		  //wc_add_order_item_meta($orderID, 'Process Date', $monthdate);
		 		
		}
		else{
		 wc_delete_order_item_meta($orderID, 'Process Date', '',true);
		//wc_add_order_item_meta($orderID, 'Process Date', $postdate);
		}
		
		/*Add order data to datafile*/
		//Billing First Name
		$billing_first_name=$order->billing_first_name;
		//Biling Last Name
		$billing_last_name=$order->billing_last_name;
		//customer email
		$customer_email=$order->billing_email;
		//Billing Address
		$billing_address=$order->get_formatted_billing_address ();
		$billing_address=str_replace("<br/>",",",$billing_address);
		//Shipping First Name
		$shipping_first_name=$order->shipping_first_name;
		//Shipping Last Name
		$shipping_last_name=$order->shipping_last_name;
		//Shipping Address
		$shipping_address=$order->get_formatted_shipping_address();
		$shipping_address=str_replace("<br/>",",",$shipping_address);
		//1.	Initial order date
		//$postdate
		$postdate=strip_tags($postdate);
		$nextdate=strip_tags($nextdate);
		
		//2.	Subscription interval
		$subscription_item=$Singleorder->get_items();
		$singleschedule=reset($subscription_item);
		$item_meta=($singleschedule['item_meta']);
		$item_interval=$item_meta['_subscription_interval'][0];
		//echo "Subscription interval: ".$item_interval."<br/>";
		$orderDetail=$order->get_order_item_totals();
		//echo "Received  Discount: ";
		$discountflag=0;
		
		foreach($orderDetail as $total){
		//3.	Received  Discount – a Yes or No field
		if($total['label']=='Discount:'){
			$discount=$total['value'];
			$discount_Flag="yes";
			$discountflag=1;
			}
		if($total['label']=='Total:'){
		//4.	Initial Order price
			$initialPrice=$total['value'];
			$initialPrice=str_replace('<span class="amount">',"",$initialPrice);
			$initialPrice=str_replace('</span>',"",$initialPrice);
			$initialPrice=str_replace('&#36;',"$",$initialPrice);
			
		}
		if($total['label']=='Subtotal:'){
		//5.	Subsequent Order price
			$subsequent_price =$total['value'];
			
			$subsequent_price=str_replace('<span class="amount">',"",$subsequent_price);
			$subsequent_price=str_replace('</span>',"",$subsequent_price);
			$subsequent_price=str_replace('&#36;',"$",$subsequent_price);
		}
		}
		if($discountflag==0){
		$discount_Flag="no";
		}
			$itemcounter=0;
			$eachFilter="";
			foreach($subscription_item as $singledetail=>$key){
				//print_r ($key['item_meta']['_qty']);
				$tempFilter="";
				if(isset($key['pa_size'])){
				$product_name=$key['pa_size'];
				$product_name=str_replace("-"," ",$product_name);
				$product_name=str_replace("x","X",$product_name);
				$qty=$key['item_meta']['_qty'][0];
				$tempFilter= $product_name."  QTY: $qty, ";
				$eachFilter.=$tempFilter;
				}
				else{
				//echo $key['name'];
				}
				
				$product_qty=$key['pa_quality'];
				
			}
			//echo $eachFilter;
			$merv=$product_qty;
		
		/*write to the csv file*/
		$arr=array(
		'Initial order date'=>$postdate,
		'Billing First Name'=>$billing_first_name,
		'Billing Last Name'=>$billing_last_name,
		'Email'=>$customer_email,
		'Billing Address'=>$billing_address,
		'Shipping First Name'=>$shipping_first_name,
		'Shipping Last Name'=>$shipping_last_name,
		'Shipping Address'=>$shipping_address,
		
		'Subscription interval'=>($item_interval),
		'Received  Discount '=>$discount_Flag,
		'Initial Order price'=>$initialPrice,
		'Subsequent Order price '=>$subsequent_price,
		'Filter'=>$eachFilter,
		'Merv rating'=>$merv,		
		'Order Id'=>$orderID
		);
		//$FileName = (__FILE__);
		
		$FileName = $_SERVER['DOCUMENT_ROOT'].'/wpcms/wp-content/themes/qaf_theme/functions/form_data/orderInfo.csv';
		$dupFlag=false; 
			if (file_exists($FileName)) {
			$fp = fopen($FileName, 'a');
			$csv = array_map("str_getcsv", file($FileName)); 
				foreach ($csv as $row) { 
					if($row[0]==$postdate||$row[0]==$nextdate){	
										
						if(isset($row[3])&&($row[3]==$customer_email)){	
						 
						  if(isset($row[11])&&($row[11]==$subsequent_price)){
						 
						  $dupFlag=true;
						}
						}
					}
					if($row[14]==$orderID){
						$dupFlag=true;
					}
				}
				
				//print_r($array);
			if(!$dupFlag){
				fputcsv($fp,$arr);
				//GA emcommerce
				}
			}
			else{
			$fp = fopen($FileName, 'a');
				foreach($arr as $name=>$value){
				$HeadingsArray[]=$name;
				}
				fputcsv($fp,$HeadingsArray);
				fputcsv($fp,$arr);
				}

		fclose($fp);
		?>
		<h2>Thank you!</h2>
		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Your order was successful. You can view the status of your subscription in <a href="'. get_permalink( get_option('woocommerce_myaccount_page_id') ) . '">your account</a>.', 'woocommerce' ), $order ); ?></p>
	<div class="checkout_detail">
		<h3>Checkout</h3>
		<div class="checkout_details">
		<table class="checkout_details">
		<tr>
			<td class="order">
				<?php _e( 'ORDER NUMBER', 'woocommerce' ); ?>
			</td>
			<td class="second_td">
				<strong><?php echo $order->get_order_number(); ?></strong>
			</td>
		</tr>
		<tr>
			<td class="date">
				<?php _e( 'DATE', 'woocommerce' ); ?>
			</td>
			<td class="second_td" style="font-size:0.95em;">
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</td>
		</tr>
		<tr>
			<td class="total">
				<?php _e( 'TOTAL', 'woocommerce' ); ?>
			</td>
			<td class="second_td">
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</td>
		</tr>
		<tr>
			<?php if ( $order->payment_method_title ) : ?>
			<td class="method" >
				<?php _e( 'PAYMENT&nbsp;METHOD', 'woocommerce' ); ?>
			</td>
			<td class="second_td">
				<strong><?php echo str_replace('(Stripe)','',$order->payment_method_title); ?></strong>
			</td>
		</tr>
		</table>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	
	<?php endif; ?>
	<?php //print_r($order->id);?>
	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	
	
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>	
	
<img src="https://sp.analytics.yahoo.com/spp.pl?a=1000443753260&.yp=15451&js=no"/>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6028111628490&amp;cd[value]=25.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>	
<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>


