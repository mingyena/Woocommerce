var $ = jQuery.noConflict();

// Fire on initial page load only.
$(document).ready(function() {

	alwaysScripts();
	filterqty();
	filterquality();
	clearsizepage();
	autoregister();
	$('.create-account').hide();
	$('#create-account-container').hide();
	
	if (window.location.href.indexOf("checkout/?woo-airfilter-frequency") > -1) {
		billpaytrack();
		couponcode();
	}
	//checkoutformValidation();
});
// Fire on page load and postback.
$( document ).ajaxComplete(function(){
	paymentHack();
	alwaysScripts();
	// Because IE. is. awesome.
	if ($.browser.msie) {
		$.unblockUI();
		$('.blockUI').remove();
	}
	
	
});

// Fire on window load. Mobile nav functionality only works if DOM is fully loaded first.
$(window).load(function() {
	SidebarMenuEffects();
	$('input.insert_custom').val('add another filter size');	
});

function alwaysScripts() {
	
	jvFloatInit();
	confirmEmail();
	modalPopup();
	
}


function jvFloatInit() {

	if ($('.user_billing_info').length > 0) {
		$('.user_billing_info input[type="text"], .user_billing_info input[type="email"], .user_billing_info input[type="password"], .user_billing_info select, .user_billing_info textarea').each(function() {
			if(!($(this).parent().hasClass('jvFloat'))) {
				$(this).jvFloat();
			}
			// if ($.browser.msie) {
			// 	$('.placeholder').css({
			// 		'display': 'block',
			// 		'opacity': '1',
			// 		'-ms-transform': 'translate(0, -1.2em)',
			// 		'visibility': 'visible'
			// 	})
			// }
		});
	}

}

function confirmEmail() {

	$('.validate-email .input-text').on( 'blur input change', function() {
		var $email1 = $('.validate-email:eq(0)'),
			$email2 = $('.validate-email:eq(1)'),
			confirmedEmail = true;

		// console.log($this2.val());
		if ( !($email1.find('.input-text').val() === $email2.find('.input-text').val()) ) {
			$email2.closest('.form-row').removeClass( 'confirmed-email' ).addClass( 'confirm-email' );
			confirmedEmail = false;
		}

		if ( confirmedEmail ) {
			$email2.closest('.form-row').removeClass( 'confirm-email' ).addClass( 'confirmed-email' );
		}
	} )

}

/*Auto registration* RL 3/2/2015*/


//	[JJ - 07/22/14] Very versatile function that allows you to
//	target a specific (use #id) or generic (use .class) element
//	and toggle the class you specify.
function classToggle(elClass,target) {
	$(target).toggleClass(elClass);
}

function modalPopup () {
	$('.modal-popup').off('click mouseenter mouseleave');
	if($('html').hasClass('touch')) {
		$('.modal-popup').on('click', function(e) {
			e.preventDefault();
			// alert('click');

			var popup = $(this).attr('data-popup-target'),
				anchor = $(this);

			popup = '#' + popup;

			if(!($(popup).find('.popup-content').length)) {
				$(popup).wrapInner("<div class='popup-content'></div>");
				// alert('wrapped');
			}

			if(!($(popup).is(':visible'))) {
				$(anchor).append($(popup));
				// alert('appended');
				$(popup).show();
				// alert('shown');
			}
			else {
				$(popup).hide();
				// alert('hidden');
			}

		});
	}
	if($('html').hasClass('no-touch')) {
		$('.modal-popup').on('mouseenter', function() {
			var popup = $(this).attr('data-popup-target'),
				anchor = $(this);

			popup = '#' + popup;

			if(!($(popup).find('.popup-content').length)) {
				$(popup).wrapInner("<div class='popup-content'></div>");
			}

			$(anchor).append($(popup));
			$(popup).show();
		});
		$('.modal-popup').on('mouseleave', function() {
			var popup = $(this).attr('data-popup-target');
			popup = '#' + popup;
			// $(popup).hide();
		    $(popup).fadeOut(400);
		    $(popup).mouseover(function() {
		        $(this).stop(true).fadeTo(400, 1);
		    });

		});
	}
}

//RL 121214 - script for select size page
//add, remove, submit funciton
function filterqty(){
	
	//var test=$(".new_filter_section>select:eq(0)>option").val();
	//var standard_dimension = $('.new_filter_section').find('single_qyt:eq(0)')
	var options = $('.new_filter_section').find('.single_qyt:eq(0) select');
	var standard_size = $.map(options ,function(option) {
	//console.log (standard_size);
    return standard_size;
	});

	//add button is in the php
	var wrapper=$('.new_filter_section');
			 $(wrapper).on('click','.remove_qty', function(e){ //user click on remove text,check the qty-standard
				//e.preventDefault(); 
				//var test=$(this).parents('div.single_qyt').html();
				
				var current_dimension=$(this).parents('div.single_qyt').find('select.qty_options option:selected').text();
				
				if(current_dimension!="none"){
				
					 $("ul.item_summary .standard_section li.item_row").each(function()
						 {
							
								var existed_dimension=$(this).find('li.dimension').text();
								//console.log(existed_dimension);
								if(existed_dimension==current_dimension){
							    
								var current_qty = parseInt($(this).find('li.select_qty').text(),10)-1;
								
								/*if(current_qty!=0){
								$(this).find('li.select_qty').replaceWith("<li class='select_qty'>"+current_qty+"</li>");
								}
								else{*/
								
								$(this).remove();
								//}
								return false;
								}
								
								
							
						});
				}
				$(this).parents('div.single_qyt').remove();
				var size_total=$('select.qty_options').size();
				//console.log(size_total);
				$('input.standard_fiflter_qyt').val(size_total);	
				var size=$('select.qty_options').size();
				var size_standard=$('.new_filter_section .single_qyt').size();
								//console.log(size_standard);			
								if (size_standard<10){
									$('.toggle_standard_list .add-qty-container').show();
								}
    })
	
			// var default_qty=1;
	var standard='standard';
			 
	$(wrapper).on('change','.qty_options,.single_filter_qty', function(e)
	{ //user fill the options-standard
				e.preventDefault(); 
				dimension=$(this).find('option:selected').text();
				//console.log(dimension);
			if(dimension!='none')
			{
				var size_total=$('select.qty_options').size();
				var size_empty=$('select.qty_options option:selected[value=\"none\"]').size();
				var size=size_total-size_empty;
				$('input.standard_fiflter_qyt').val(size);
				if(size>0)
				{
					$('ul.item_summary .standard_section li.item_row').remove();
					var default_qty=0;
					var type="standard";
					
					for ( var i = 0; i < size_total; i++ ) 
					{
						dimension=$('.new_filter_section .single_qyt:eq('+i+')').find('select.qty_options option:selected').text();
						default_qty=$('.new_filter_section .single_qyt:eq('+i+')').find('select.single_filter_qty option:selected').val();
						$('ul.item_summary .standard_section').append("<li class='item_row'><ul class='clearfix'><li class='dimension'>"+dimension+"</li><li class='select_qty'>"+default_qty+"</li><li class='type'>"+type+"</li></ul></li>");
						 $("ul.item_summary .standard_section li.item_row:not(:last)").each(function()
						 {
							
								var existed_dimension=$(this).find('li.dimension').html();
								//console.log(existed_dimension);
								/*if(dimension==existed_dimension){
							
								var current_qty = parseInt($(this).find('li.select_qty').text(),10)+1;
							
								$(this).find('li.select_qty').replaceWith("<li class='select_qty'>"+current_qty+"</li>");
								$("ul.item_summary li.item_row").last().remove();
								return false;
								}*/
							
						});
						
						
					}
					
				}
			}
	})
			
	var wrapper=$('.new_filter_section_custom');
			
			 $(wrapper).on('click','.remove_qty_custom', function(e){ //user click on remove text - custom size
				//console.log('click');
				//e.preventDefault(); 
				
				//var current_dimension=$(this).parents('div.single_qyt').find('select.qty_options option:selected').text();
				var countertest=0;
				var counter=0
				$(this).parents('.single_qyt').each(function(e){
					var depth=$(this).find('select.depth option:selected').text();
					var depth_frac=$(this).find('select.depth_fraction option:selected').text();
					
					if($(this).find('input.height').val().length===0||$(this).find('input.width').val().length===0){
						counter++;
					}
					
				
					});
					
					
				var number=$(this).parents('.single_qyt').prevAll().length-2;	
				//console.log(number);
				$(this).parents('.single_qyt').remove(); 
				
				$("ul.item_summary .custom_section li.item_row"+number+"").remove();
				
				var size=$('.new_filter_section_custom').find('.single_qyt').size();
				$('input.custom_fiflter_qyt').val(size);
				//console.log(size);
				
				var size_custom=$('.new_filter_section_custom .single_qyt').size();
							   //console.log(size_custom);			
								if (size_custom<10){
									$('.toggle_custom_list .add-qty-container').show();
								}
				
				
    })
	/*var custom_demension_before="";;
	$(wrapper).on('keypress','.single_qyt input', function(custom_demension_before){
	
	//$('.new_filter_section_custom').find('.single_qyt').each(function(){	
	
		var height=$(this).find('input.height').val();
						var width=$(this).find('input.width').val();
						var depth=$(this).find('input.depth').val();
						
						var height_frac=$(this).find('select.height_fraction option:selected').text();
						var width_frac=$(this).find('select.width_fraction option:selected').text();
						var depth_frac=$(this).find('select.depth_fraction option:selected').text();
						
						var custom_demension_before=ignore(height)+ignore(height_frac)+"\" x "+ignore(width)+ignore(width_frac)+"\" x "+ignore(depth)+ignore(depth_frac)+"\"";
						console.log(custom_demension_before);
						
	
	//});
	});*/
	$(wrapper).on('change','.single_qyt input', function(){ //user fill the input - custom size
				//console.log(custom_demension_before);
				//e.preventDefault(); 
				var size_total=$('.new_filter_section_custom').find('.single_qyt').size();
				
				var counter=0;
				var countertest=0;
				var size_empty=0;
				$('.new_filter_section_custom').find('.single_qyt').each(function(e){
					if($(this).find('input.height').val().length===0||$(this).find('input.width').val().length===0||$(this).find('select.height option:selected').text()===0){
						
						counter++;
					}
				
				//validator - compare with standard size
					else{
						
						var height=$(this).find('input.height').val();
						var width=$(this).find('input.width').val();
						var depth=$(this).find('select.depth option:selected').text();
						//console.log(depth);
						var height_frac=$(this).find('select.height_fraction option:selected').text();
						var width_frac=$(this).find('select.width_fraction option:selected').text();
						var depth_frac=$(this).find('select.depth_fraction option:selected').text();
						
						var custom_demension=ignore(height)+ignore(height_frac)+" x "+ignore(width)+ignore(width_frac)+" x "+ignore(depth)+ignore(depth_frac);
						//console.log(size_total);
						//console.log(custom_demension);
						if(custom_demension==""){
						alert("Cannot all fields are blank");
						}
						var default_qty=6;
						var type="custom";
						var number=$(this).prevAll().length-2;	
						//console.log(number);
						$("ul.item_summary .custom_section li.item_row"+number+"").remove();
						$('ul.item_summary .custom_section').append("<li class='item_row"+number+"'><ul class='clearfix'><li class='dimension'>"+custom_demension+"</li><li class='select_qty'>"+default_qty+"</li><li class='type'>"+type+"</li></ul></li>");
						//countertest++;
						//$(".custom_fiflter_qyt").appenafter
						}
						var element=$(this);
						$('.new_filter_section').find('.single_qyt:eq(0) select option').each(function(e){
						var standardD= $(this).text();
						
						if(custom_demension==standardD){
						var default_qty=1;
						var type="custom";
						var number=$(element).prevAll().length-2;	
						//console.log(number);
						$("ul.item_summary .custom_section li.item_row"+number+"").remove();
						$test=$(element).find('input.height').val('');
						$(element).find('input.width').val('');
						//$(element).find('input.depth').val('');
						alert ("This size is already existed in the Standard Size, Please go to the Standard filter size.");
						
						return false;
						//e.defaultprevented();
						}
						
						});
					});
					
				
				
				var size_empty=counter;
				var size=size_total-size_empty;
				$('input.custom_fiflter_qyt').val(size);
				
				
    })
	
	///////////////////////////////////////////////////////////////////////////////
		$(wrapper).on('change','.single_qyt select,.single_filter_qty select', function(e){ //user fill the input - custom size
				
				$('.new_filter_section_custom').find('.single_qyt').each(function(){
					
				
				//validator - compare with standard size
						
						var height=$(this).find('input.height').val();
						var width=$(this).find('input.width').val();
						//var depth=$(this).find('input.depth').val();
						var depth=$(this).find('select.depth option:selected').text();
						console.log(depth);
						var height_frac=$(this).find('select.height_fraction option:selected').text();
						var width_frac=$(this).find('select.width_fraction option:selected').text();
						var depth_frac=$(this).find('select.depth_fraction option:selected').text();
						
						var custom_demension=ignore(height)+ignore(height_frac)+" x "+ignore(width)+ignore(width_frac)+" x "+ignore(depth)+ignore(depth_frac);
						if(custom_demension==""){
						alert("Cannot all fields are blank");
						}
						

						if((ignore(height)+ignore(height_frac))===""||(ignore(width)+ignore(width_frac))===""||(ignore(depth)+ignore(depth_frac))===""){
						var default_qty=0;
						var type="custom";
						var number=$(this).prevAll().length-2;	
						//console.log(number);
						$("ul.item_summary .custom_section li.item_row"+number+"").remove();
						}
						else{
						var default_qty=0;
						default_qty=$(this).find('select.single_filter_qty option:selected').val();

						var type="custom";
						var number=$(this).prevAll().length-2;	
						//console.log(number);
						$("ul.item_summary .custom_section li.item_row"+number+"").remove();
						$('ul.item_summary .custom_section').append("<li class='item_row"+number+"'><ul class='clearfix'><li class='dimension'>"+custom_demension+"</li><li class='select_qty'>"+default_qty+"</li><li class='type'>"+type+"</li></ul></li>");
						var element=$(this);
						$('.new_filter_section').find('.single_qyt:eq(0) select option').each(function(){
						var standardD= $(this).text();
						
						if(custom_demension==standardD){
						var default_qty=0;
						var type="custom";
						var number=$(element).prevAll().length-2;
						$("ul.item_summary .custom_section li.item_row"+number+"").remove();
						$(element).find('input.height').val('');
						$(element).find('input.width').val('');
						//$(element).find('input.depth').val('');
						//var test=$(this).find('input.depth').val();
						alert ("This size is already existed in the Standard Size, Please go to the Standard filter size.");
						return false;
						
						
						}
						
						});
						}
						
						
					});			
				
			
    })
	
	/////////////////////////////////////////////////////////////////////////////////////
	//submit button validator
$('#step2').on("submit",function(e){
		
		if((($(".new_filter_section").find(".single_qyt:eq(0) select option:selected").val()=="none")||$(".new_filter_section").find("select.single_filter_qty option:selected").val()==0)&&($(".new_filter_section_custom").find(".single_qyt:eq(0) input").val().length===0||$(".new_filter_section_custom").find("select.single_filter_qty select option:selected").val()==0)){
			
			$('.new_filter_section ').find('.single_qyt:eq(0)').addClass('error');
			$('.new_filter_section_custom').find('.single_qyt:eq(0)').addClass('error');
		
		e.preventDefault();
		return false;
		}
		
		//$('.new_filter_section_custom input.dimention_custom').remove();
				//add custmize size to hiddenn input
				$("ul.item_summary .custom_section li.dimension").each(function(){
				var dimention=$(this).text();
				//dimention.replace(/\","-");
				$('.new_filter_section_custom').after("<input type='hidden' name='woo-airfilter-what-custom[]' class='dimention_custom' value='"+dimention+"'>");
				//e.preventDefault();
				});
				
	
	});
$('#select-quality').on("submit",function(e){
	if(($( ".step_3_frequency option:selected" ).val()=="blank")){
		
	$(".step_3_frequency").addClass('error');
	$('html, body').animate({ scrollTop: 0 }, 'slow');
	e.preventDefault();
		return false;
	}
	
});
	
	$('.step_3_frequency').on('change',function(){var frequency=$('.step_3_frequency option:selected').text(); $('span.selected_frequency').text(frequency);});


	}
function filterquality(){
	$('div.change_shipdate').hide();
	$('input.change_ship_date').on("click",function(e){
		if($(this).is(':checked')){
			$('div.change_shipdate').show();
			$('input.ship_today').prop('checked', false);
		}
		else{
			$('div.change_shipdate').hide();
			$('input.ship_today').prop('checked', true);
		}
	});
	$('input.ship_today').on("click",function(e){
		if($(this).is(':checked')){
			$('div.change_shipdate').hide();
			$('input.change_ship_date').prop('checked', false);
		}
		else{
			$('div.change_shipdate').show();
			$('input.change_ship_date').prop('checked', true);
		}
	});
	
}
function clearsizepage(){
	$('.new_filter_section .qty_options').val('none');
	$('.new_filter_section .single_filter_qty').val('1');
	$('.new_filter_section_custom input').val('');
	$('input.custom_fiflter_qyt').val('0');
	$('input.standard_fiflter_qyt').val('0');
}
function ignore(x){
	if(x==0){
	return "";
	}
	else{
	return x;
	}
}

function paymentHack(){
	//var payment=$('.user_billing_info');
	//$('.user_billing_info').remove();
	//$('div.billing_info').after(payment);

	// var coupon=$('#couponContainer');
	// $('#couponContainer').remove();
	// $('table.shop_table').after(coupon);

	// var paymentby=$('.payment-by');
	// $('.payment-by').remove();
	// $('div.shop_table_outter').append(paymentby);
	
	// $('input#ship-to-different-address-checkbox').change(function(){
		// $('.shipping_address').show();
	// });
	//$('.shipping_addres').hide();

	if ($('#order_review .shop_table_outter').length > 0) {
		var orderSummary = $('#order_review .shop_table_outter');
		$('#order_review .shop_table_outter').remove();
		$('#order-summary-container .shop_table_outter').remove();
		$('#order-summary-container').prepend(orderSummary);
		$('#order-summary-container').show();
	}

	$('a.agreementLink').click(function(e){
		var agreementHeight=$('#payment').position();
		//console.log(agreementHeight);
		$('#purchase-agreement').css("top",agreementHeight.top);
	
	});
	$("input#place_order").prop('value', 'PLACE ORDER');
	var replace_string;var replace_content;
	$('div#order_review .payment_box p').first().each(function(){replace_string=$(this).html();

	replace_content=replace_string.replace(/ via Stripe/g, "");
	});
	//console.log(replace_content);
	$('div#order_review .payment_box p').first().each(function(){$(this).replaceWith("<p>"+replace_content+"</p>");});
	
}
function autoregister(){
$('input#billing_email').on('change',function(e){
var email=$(this).val();
$('input#account_username').val(email);
$('input#account_password').val(email);
//console.log(email);
});
// var test=$('ul.woocommerce-error li').html();
// console.log(test);
}
/*RL - 4/9/2015*/
function billpaytrack(){
		var d = new Date();
		var startTime = d.toString('MM/dd/yy hh:mm');
		var product_size="";
		var couponcode_clicked="";
		$('table.shop_table tr.cart_item').each(function(){
			
			var product_qty=parseInt(($(this).find("td.product-quantity").text()));
			 for (var i = 0; i < product_qty; i++) {
			 product_size=product_size+($(this).find("td.product-name").text())+', ';
			 }
			//product_size=product_size+($(this).text())+', ';
		});
		//console.log(product_size);
		var hasBeenClicked = false;
	
		$('input#place_order').click(function () {
			hasBeenClicked = true;
			// console.log(hasBeenClicked);
		});
		$('input#addCoupon').click(function(){
			if ($('input#addCoupon').attr('checked')) {
				couponcode_clicked="yes";
			}
		});

	
		// else{
		//console.log(hasBeenClicked);
		$(window).on('beforeunload', function() {
			if(hasBeenClicked){
				//return NULL;
			}
			//var firstname= $('input #billing_first_name').val();
			//setInterval(function(){
			// method to be executed;
			else{
				var data = {
						time_entered_page:startTime,
						time_left_page:(new Date()).toString('MM/dd/yy hh:mm'),
						bill_first_name:$('input#billing_first_name').val(),
						billing_last_name:$('input#billing_last_name').val(),
						billing_address_1:$('input#billing_address_1').val(),
						billing_address_2:$('input#billing_address_2').val(),
						billing_city:$('input#billing_city').val(),
						billing_state:$('select#billing_state :selected').val(),
						billing_zip:$('input#billing_postcode').val(),
						billing_email:$('input#billing_email').val(),
						billing_confirm_email:$('input#billing_email_confirmation').val(),
						billing_phone:$('input#billing_phone').val(),			
						ship_different_address:$('input#ship-to-different-address-checkbox').val(),
						shipping_first_name:$('input#shipping_first_name').val(),
						shipping_last_name:$('input#shipping_last_name').val(),
						shipping_address_1:$('input#shipping_address_1').val(),
						shipping_address_2:$('input#shipping_address_2').val(),
						shipping_city:$('input#shipping_city').val(),
						shipping_state:$('select#shipping_state :selected').val(),
						shipping_zip:$('input#shipping_postcode').val(),
						account_username:$('input#account_username').val(),
						account_password:$('input#account_password').val(),
						add_delivery_note:$('textarea#order_comments').val(),
						product_size:product_size,
						product_quality:$('table.shop_table tr.cart_item .product-quality > strong').text(),
						cart_subtotal:$('table.shop_table tr.cart-subtotal .amount').text(),
						atctived_coupon:$('table.shop_table tr td .coupon').text(),
						sales_tax:$('table.shop_table tr.tax-rate .amount').text(),
						order_total:$('table.shop_table tr.order-total .amount').text(),
						add_coupon_click:couponcode_clicked,
						coupon_code:$('input#coupon_code').val()			
				};
					//console.log(firstname);
				if(product_size!=""){
				$.ajax({
					type:		'POST',
					url:		'/wpcms/wp-content/themes/qaf_theme/functions/paybill_track.php',
					data:		data,
					success:	function( code ) {
						console.log(data);
					}
				
				});
				}
				return NULL;
				//var message="";
				//return message;
				//return 1;
			}
		});
		
}
//For some resson, the bill form call 3 times all the time.
function couponcode(){
var count=$('table.shop_table tr.cart_item').length;

var count2=0;
$('table.shop_table tr.cart_item td.product-quantity strong').each(function(){
  count2=parseInt($(this).html())+count2;
});
if (count>3||count2>2){
 $('#coupon-form #coupon_code').val('MORETHAN3ITEMS');
 $('.coupon_code_button').trigger('click');
}

}

function checkoutformValidation(){
 $( 'form.checkout input.input-text' ).blur(function(){
 var $parent=$(this).closest( '.form-row' );
 var validated = true;
 			if ( $parent.is( '.validate-required' ) ) {
				if ( $(this).val() === '' ) {
					$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
					validated = false;
				}
			}

			if ( $parent.is( '.validate-email' ) ) {
				if ( $(this).val() ) {

					/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

					if ( ! pattern.test( $(this).val()  ) ) {
						$parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email' );
						validated = false;
					}
				}
			}

			if ( validated ) {
				$parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field' ).addClass( 'woocommerce-validated' );
			}
 });
}



