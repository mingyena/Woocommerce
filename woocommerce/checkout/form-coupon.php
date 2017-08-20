<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
$product_amount=0;
WC()->cart->remove_coupon("MORETHAN3ITEMS");
$product_amount=(WC()->cart->cart_contents_count);

//if(array_key_exists('future_shipment', $_SESSION) && !empty($_SESSION['future_shipment'])) {
if($_SESSION['future_shipment']==""){
	//else{
	if($product_amount>2){
		// [JJ - 03/24/2015] Check if discount already applied before applying again. Otherwise, it triggers errors.
		//if(!WC()->cart->has_discount("MORETHAN3ITEMS")){
			WC()->cart->add_discount("MORETHAN3ITEMS");
		//}

		?>
		<script>
			// [JJ - 03/24/2015] Changed this to always show.
			if (!($('.threeormoredisc-note').length > 0)) {
				$('.woocommerce-error-ajax').append('<ul class="woocommerce-info threeormoredisc-note"><li>You are receiving a 20% discount.</li></ul>');
			}
			// [JJ - 03/24/2015] Check input fields this one time only. If empty, remove false positives for invalid.
			$('.validate-required input, .validate-required select').each(function() {
				var currentVal = $(this).val();
				//console.log(currentVal);
				if(!(currentVal.length > 0) || currentVal == "") {
					$(this).parents('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
				}
			});
		</script>
		<?php
	}
	else{
	WC()->cart->remove_coupon("MORETHAN3ITEMS");
	}
}
else{
	WC()->cart->remove_coupon("MORETHAN3ITEMS");
}
//}
?>
<div id="couponContainer">
	<label><input type="checkbox" id="addCoupon" name="addCoupon"></input> I have a coupon code</label>
	<script>
		//jQuery('#coupon-form').slideDown();
		jQuery('#addCoupon').on('click', function() {
			if(jQuery('#addCoupon').is(':checked')) {
				jQuery('#coupon-form').slideDown();
			}
			else {
				jQuery('#coupon-form').slideUp();
			}
		});
	</script>
	<div id="coupon-form" style="display:none;">
		<!--<form class="checkoutt_coupon" method="post">-->
			<div class="error-message"><font style="color:red">The coupon code doesn't exist.</font></div>
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code"/>
			<input type="button" class="button coupon_code_button" name="apply_coupon" value="<?php _e( 'Apply', 'woocommerce' ); ?>" />
			
			<input type="hidden" name="woo-add-session" class="coupon_code_input" value="<?php echo (WC()->cart->cart_contents_count); ?>">
			<!--<div class="clear"></div>-->
	<script>
		// [RL - 03/25/2015]
		/*tax change*/
		//changeTax(tax_rate);
		var firstlog=$("select#billing_state option.selected").val();
		
		if(typeof(firstlog)  === "undefined"){
			var tax_rate=0.00;
			changeTax(tax_rate);
		}
		$("#billing_state").change(function(){
			if($('#ship-to-different-address-checkbox').is(":checked")){}
			else{
			  var state=$( "#billing_state option:selected" ).text();
			  if(state=="Texas"){
					tax_rate=0.0825;
				}
			  else{
				  tax_rate=0.00;
				  
			  };
			}
			changeTax(tax_rate);
			
		});
		$("#shipping_state").change(function(){
			if($('#ship-to-different-address-checkbox').is(":checked")){
			var state=$( "#shipping_state option:selected" ).text();
			  if(state=="Texas"){
					tax_rate=0.0825;
				}
			  else{
				  tax_rate=0.00;
				  
			  };
				
			}
			else{

			}
			changeTax(tax_rate);
			
		});

		function changeTax(tax_rate){
		var beforetax= $("table.shop_table tr.total_before_tax td span.amount").html();
			beforetax=beforetax.replace("$","");
			beforetax=beforetax.replace('<span class="amount">',"");
			beforetax=beforetax.replace('</span>',"");
			console.log(beforetax);
			var tax=beforetax*tax_rate;
			tax=tax.toFixed(2);
			console.log(tax);
			$("table.shop_table tr.tax-total td span.amount").replaceWith('<span class="amount">$'+tax+'</span>');
			var total= $("table.shop_table tr.total_before_tax td span.amount").html();
			total=total.replace("$","");
			total=total.replace('<span class="amount">',"");
			total=total.replace('</span>',"");
			final_total= (eval(parseFloat(total)+parseFloat(tax))).toFixed(2);
			$('table.shop_table tr.order-total td span.amount').replaceWith("<span class='amount'>$"+final_total+"</span>");

			
		}
		/* AJAX Coupon Form Submission */
		var total="";var total_tax="";
		total_tax=($('table.shop_table tr.tax-rate td span.amount').text().replace(/[^0-9]/gi, ''))/100;
		$('table.shop_table tr.cart_item td.product-total span.amount').each(function(){
			var current= ($(this).text().replace(/[^0-9]/gi, ''))/100;
			total=  eval(current+total);
		});
		
		var previous_value=0;
		var current_coupon=0.00;
		var final_total=0;var test1='';var total_coupon=0;var before_tax=0;
		

		$('.error-message').hide();
		$( '.coupon_code_button' ).click( function() {
			//var $form = $( this );
			total="";total_tax="";
			$('table.shop_table tr.cart_item td.product-total span.amount').each(function(){
				var current= ($(this).text().replace(/[^0-9]/gi, ''))/100;
				total=  eval(current+total);
			});
	
			var coupon=$( 'input[name=coupon_code]' ).val();
			$( '.woocommerce-error, .woocommerce-message' ).remove();
			
			var data = {
				action:			'woocommerce_apply_coupon',
				security:		wc_checkout_params.apply_coupon_nonce,
				coupon_code:	$('input#coupon_code').val()
			};
			
			var data2={
				coupon_code:	$('input#coupon_code').val().toUpperCase(),
				subtotal_amount: $('table.shop_table tr.cart-subtotal td span.amount').text()
			};
		
			$.ajax({
				type:		'POST',
				url:		wc_checkout_params.ajax_url,
				data:		data,
				success:	function( code ) {
					//console.log(data);
					//console.log(wc_checkout_params.ajax_url);
					$( '.woocommerce-error, .woocommerce-message' ).remove();
					/*$form.removeClass( 'processing' ).unblock();
					*/
					if ( code ) {
						//console.log(code);
						if (code.indexOf('successfully') > -1) {
							$('.error-message').hide();
							$.ajax({
								type:		'POST',
								url:		'/wpcms/wp-content/themes/qaf_theme/woocommerce/checkout/get_coupon_value.php',
								data:		data2,				
								success:	function( output) {
									//console.log(output);
									//value="morethan3items"
									/*if(final_total==0){
										
										var test1=data2['subtotal_amount'].replace("$","");
										var test1=test1.replace(",","");
										var test=parseFloat(test1);
										//output=parseFloat(output);
										
										current_coupon=(output*test).toFixed(2);	
										//console.log(current_coupon);	
										final_total++;
									}
									else{*/
										
										var test1=data2['subtotal_amount'].replace("$","");
										var test1=test1.replace(",","");
										var test=parseFloat(test1);
										var previousDiscount=0.00;
										$("tr.active_coupon").each(function(){
										var previouscoupon=$(this).find(".amount_coupon").text().replace("$","");
										previouscoupon=previouscoupon.replace("(","");
										previouscoupon=previouscoupon.replace(")","");
										console.log(previouscoupon);
										//console.log(previousDiscount);
										previousDiscount=(parseFloat(previousDiscount)+parseFloat(previouscoupon));										
										});
										previousDiscount=(parseFloat(previousDiscount/2)).toFixed(2);
										console.log(previousDiscount);
										previousDiscount=parseFloat(previousDiscount).toFixed(2);
										//console.log(previousDiscount);
										//console.log(output);
										
										current_coupon=((test1-previousDiscount)*output).toFixed(2);
									//}
									
									//console.log(current_coupon);
									//previous_value=final_total;
									//console.log(final_total);
									$('tr.cart-discount').remove();
									var twentypercentoff=data2['coupon_code'];
									var tax_rate="";
									var state=$( "#billing_state option:selected" ).text();
									if(state=="Texas"){
										tax_rate=0.0825;
									}
									else{
										tax_rate=0.00;
				  
										}
									
									if(twentypercentoff=="MORETHAN3ITEMS")
									{
										//$("input#coupon_code").val("");
										$('tr.cart-subtotal').after("<tr class='active_coupon'><td>20% DISCOUNT</span></td><td>&nbsp;</td><td><span class='amount_coupon'>($"+current_coupon+")</span></td></tr>");
										//$('input.coupon_code_input').val("");
										//$('input#coupon_code').attr('value') = '';
										//$("input#coupon_code").val("");
									}
									else{
										$('tr.cart-subtotal').after("<tr class='active_coupon'><td>COUPON:<span class='coupon'>"+data2['coupon_code']+"</span></td><td>&nbsp;</td><td><span class='amount_coupon'>($"+current_coupon+")</span></td></tr>");
									}
									test1=(eval(total-current_coupon-total_coupon));
									//console.log(total);
									//console.log(current_coupon);
									total_coupon=parseFloat(current_coupon)+parseFloat(total_coupon);
									//console.log(total_coupon);
									total_tax=(eval(tax_rate*test1)).toFixed(2);
									//console.log(total_tax);
									final_total= eval(parseFloat(test1)+parseFloat(total_tax));
									
									before_tax =test1.toFixed(2);
									//console.log(before_tax);
									$('tr.tax-rate td span.amount').replaceWith("<span class='amount'>"+total_tax+"</span>");
									$('tr.order-total td span.amount').replaceWith("<span class='amount'>$"+final_total.toFixed(2)+"</span>");
									$('tr.total_before_tax td span.amount').replaceWith("<span class='amount'>$"+before_tax+"</span>");

								},
							});
						}
						else {
							$('.error-message').show();
						}

					}
				},
				//dataType: 'html'
			});
		});

	</script>

		<!--</form>-->
	</div>
</div>
