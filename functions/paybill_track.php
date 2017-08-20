<?php
// [RL - 03/24/2015]
ini_set('display_errors',1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'].'\wpcms\wp-config.php' );
$time_entered_page=''; $time_left_page=''; $bill_first_name=''; $billing_last_name=''; $billing_address_1=''; $billing_address_2=''; $billing_city=''; $billing_state=''; $billing_zip=''; $billing_email=''; $billing_confirm_email=''; $billing_phone=''; $ship_different_address=''; $shipping_first_name=''; $shipping_last_name=''; $shipping_address_1=''; $shipping_address_2=''; $shipping_city=''; $shipping_state=''; $shipping_zip=''; $account_username=''; $account_password=''; $add_delivery_note=''; $product_size=''; $product_quality=''; $cart_subtotal=''; $atctived_coupon=''; $sales_tax=''; $order_total=''; $add_coupon_click=''; $coupon_code='';
	if(array_key_exists('time_entered_page', $_POST)){
		$time_entered_page=$_POST['time_entered_page'];}
	if(array_key_exists('time_left_page', $_POST)){
		$time_left_page=$_POST['time_left_page'];}
	if(array_key_exists('bill_first_name', $_POST)){
		$bill_first_name=$_POST['bill_first_name'];}
	if(array_key_exists('billing_last_name', $_POST)){
		$billing_last_name=$_POST['billing_last_name'];}
	if(array_key_exists('billing_address_1', $_POST)){
		$billing_address_1=$_POST['billing_address_1'];}
	if(array_key_exists('billing_address_2', $_POST)){
		$billing_address_2=$_POST['billing_address_2'];}
	if(array_key_exists('billing_city', $_POST)){
		$billing_city=$_POST['billing_city'];}
	if(array_key_exists('billing_state', $_POST)){
		$billing_state=$_POST['billing_state'];}
	if(array_key_exists('billing_zip', $_POST)){
		$billing_zip =$_POST['billing_zip'];}
	if(array_key_exists('billing_email', $_POST)){
		$billing_email=$_POST['billing_email'];}
	if(array_key_exists('billing_confirm_email', $_POST)){
		$billing_confirm_email=$_POST['billing_confirm_email'];}
	if(array_key_exists('billing_phone', $_POST)){
		$billing_phone=$_POST['billing_phone'];}
	
	
	if(array_key_exists('ship_different_address', $_POST)){
		$ship_different_address=$_POST['ship_different_address'];}
	if(array_key_exists('shipping_first_name', $_POST)){
		$shipping_first_name=$_POST['shipping_first_name'];}
	if(array_key_exists('shipping_last_name', $_POST)){
		$shipping_last_name=$_POST['shipping_last_name'];}
	if(array_key_exists('shipping_address_1', $_POST)){
		$shipping_address_1=$_POST['shipping_address_1'];}
	if(array_key_exists('shipping_address_2', $_POST)){
		$shipping_address_2=$_POST['shipping_address_2'];}
	if(array_key_exists('shipping_city', $_POST)){
		$shipping_city=$_POST['shipping_city'];}
	if(array_key_exists('shipping_state', $_POST)){
		$shipping_state=$_POST['shipping_state'];}
	if(array_key_exists('shipping_zip', $_POST)){
		$shipping_zip=$_POST['shipping_zip'];}
	if(array_key_exists('account_username', $_POST)){ 
		$account_username=$_POST['account_username'];}
	if(array_key_exists('account_password', $_POST)){ 
		$account_password=$_POST['account_password'];}
	if(array_key_exists('add_delivery_note', $_POST)){
		$add_delivery_note=$_POST['add_delivery_note'];}
	if(array_key_exists('product_size', $_POST)){
		$product_size=$_POST['product_size'];}
	if(array_key_exists('product_quality', $_POST)){
		$product_quality=$_POST['product_quality'];}
	if(array_key_exists('cart_subtotal', $_POST)){
		$cart_subtotal=$_POST['cart_subtotal'];}
	if(array_key_exists('atctived_coupon', $_POST)){
		$atctived_coupon=$_POST['atctived_coupon'];}
	if(array_key_exists('sales_tax', $_POST)){
		$sales_tax=$_POST['sales_tax'];}
	if(array_key_exists('order_total', $_POST)){
		$order_total=$_POST['order_total'];}
	if(array_key_exists('add_coupon_click', $_POST)){
		$add_coupon_click=$_POST['add_coupon_click'];}
	
	
	if(array_key_exists('coupon_code', $_POST)){
		$coupon_code=$_POST['coupon_code'];}
	
	global $wpdb;
	$table_name = $wpdb->prefix .'paybill_track';
	

$arr=array( 
		
			'Time_Entered_Page' =>$time_entered_page,
			'Time_Left_Page' =>$time_left_page,
			'bill_first_name' => $bill_first_name,
			'billing_last_name' => $billing_last_name, 
			'billing_address_1' => $billing_address_1, 
			'billing_address_2' => $billing_address_2, 
			'billing_city' => $billing_city, 
			'billing_state' => $billing_state, 
			'billing_zip'=>$billing_zip,
			'billing_email' => $billing_email, 
			'billing_confirm_email' =>$billing_confirm_email,
			'billing_phone' => $billing_phone, 
			
			'ship_different_address' => $ship_different_address,
			'shipping_first_name' => $shipping_first_name,
			'shipping_last_name' => $shipping_last_name,
			'shipping_address_1' => $shipping_address_1,
			'shipping_address_2' => $shipping_address_2,
			'shipping_city' => $shipping_city,
			'shipping_state' => $shipping_state,
			'shipping_zip' => $shipping_zip,
			'account_username' => $account_username,
			'account_password' => $account_password,
			'add_delivery_note' => $add_delivery_note,
			'product_size' => $product_size,
			'product_quality' => $product_quality,
			'cart_subtotal' => $cart_subtotal,
			'applied_coupon' => $atctived_coupon,
			'sales_tax' => $sales_tax,
			'order_total' => $order_total,
			'add_coupon_click' => $add_coupon_click,
			'coupon_code' => $coupon_code
		) ;


$FileName = dirname(__FILE__)."/form_data/Billpay_track.csv";

if (file_exists($FileName)) {
$fp = fopen($FileName, 'a');
fputcsv($fp,$arr);
}
else{
$fp = fopen($FileName, 'w');
foreach($arr as $name=>$value){
$HeadingsArray[]=$name;

}
fputcsv($fp,$HeadingsArray);
fputcsv($fp,$arr);
}



fclose($fp);


///////////////////////////////////////////////////////

// Save all records without headings
/*	$valuesArray=array();
		foreach($row as $name => $value){
		$valuesArray[]=$value;
		}
	fputcsv($file,$valuesArray); 
	while($row = mysql_fetch_assoc($sql)){
	$valuesArray=array();
		foreach($row as $name => $value){
		$valuesArray[]=$value;
		}
	fputcsv($file,$valuesArray); 
	}
*/


/*	
	//$con = mysql_connect("vrphp1.vertexremote.net","qafdev","XYLcbVmndeyc4CbY3Zyqd3") or die( "Unable to Connect database");
//mysql_select_db("qafdev",$con) or die( "Unable to select database");
$con= new mysqli('127.0.0.1', 'qafdev', 'XYLcbVmndeyc4CbY3Zyqd3', 'qafdev');
// Table Name that you want
// to export in csv
//$ShowTable = "blogs";
//global $wpdb;
$ShowTable = $wpdb->prefix .'paybill_track';
$FileName = dirname(__FILE__)."/form_data/Billpay_track.csv";
$file = fopen($FileName,"w");

$sql = mysql_query("SELECT * FROM  $ShowTable");
$row = mysql_fetch_assoc($sql);
// Save headings alon
	$HeadingsArray=array();
	foreach($row as $name => $value){
		$HeadingsArray[]=$name;
	}
	fputcsv($file,$HeadingsArray); 
	
// Save all records without headings
	$valuesArray=array();
		foreach($row as $name => $value){
		$valuesArray[]=$value;
		}
	fputcsv($file,$valuesArray); 
	while($row = mysql_fetch_assoc($sql)){
	$valuesArray=array();
		foreach($row as $name => $value){
		$valuesArray[]=$value;
		}
	fputcsv($file,$valuesArray); 
	}
	fclose($file);
 
header("Location: $FileName");
*/	
	
?>