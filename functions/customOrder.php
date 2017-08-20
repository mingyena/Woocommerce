<?php/**
 * Add the field to order emails
 **/
add_filter('woocommerce_order_items_table','customOrderDetail');
function customOrderDetail(){
require_once '\wpcms\wp-content\plugins\woocommerce\includes\admin\meta-boxes\class-wc-meta-box-order-data.php';
$test_order=new WC_Meta_Box_Order_Data();
echo ($test_order['output']);
}
?>