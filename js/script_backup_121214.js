var $ = jQuery.noConflict();

$(document).ready(function() {

	alwaysScripts();
	

});

$( document ).ajaxComplete(function() { alwaysScripts() });

function alwaysScripts() {
	
	$('.woocommerce input[type="text"], .woocommerce input[type="email"], .woocommerce input[type="password"], .woocommerce select, .woocommerce textarea').each(function() {
		if(!($(this).parent().hasClass('jvFloat'))) {
			$(this).jvFloat();
		}
	});

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

//	[JJ - 07/22/14] Very versatile function that allows you to
//	target a specific (use #id) or generic (use .class) element
//	and toggle the class you specify.
function classToggle(elClass,target) {
	$(target).toggleClass(elClass);
}
