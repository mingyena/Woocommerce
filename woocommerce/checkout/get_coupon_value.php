<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'].'\wpcms\wp-config.php' );
$test=WC()->cart->get_coupons();

if(isset($_REQUEST['coupon_code'])){
$coupon_value=$_REQUEST['coupon_code'];
$amount=str_replace("$","",$_REQUEST['subtotal_amount']);
$dbh = new mysqli('localhost', 'qafprod', '5X5aADt57VXR7DXdFHFzPc', 'qafprod');

global $wpdb;

$table_name = $wpdb->prefix . "posts";
$table_name2 = $wpdb->prefix . "postmeta";
$query = "SELECT ID FROM $table_name WHERE post_title='".$coupon_value."' AND post_type='shop_coupon'";

//if ($result=$conn->query($sql);) {
if($result=$dbh->query($query)){
while($row = $result->fetch_array())
{
$rows[] = $row;
}

foreach($rows as $row)
{
$query2 = "SELECT meta_value FROM $table_name2 WHERE post_id='".$row['ID']."' AND meta_key='coupon_amount'";
//if ($result = mysql_query($query2)) {
if($result=$dbh->query($query2)){
 //$row = mysql_fetch_assoc($result);
 while($row2 = $result->fetch_array())
{
$rows2[] = $row2;
}
foreach($rows2 as $row2)
{
 //$row= $result->fetch_array(MYSQLI_NUM);
 $percent= ($row2['meta_value'])/100;
 echo number_format(($percent), 2, '.', '');
}

}
}
}
else{
	echo "SELECT ID FROM $table_name WHERE post_title='".$coupon_value."'";
}

}
else{
echo "cannot find";
}

?>


