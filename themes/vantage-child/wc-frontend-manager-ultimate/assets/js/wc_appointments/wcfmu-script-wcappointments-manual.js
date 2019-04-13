jQuery(document).ready( function($) {
	$("#appointable_product_id").select2({ });
	
	$("#customer_id").select2({ });
	
	$('input[name=add_appointment_2]').click(function() {
	  $('input[name=add-to-cart]').attr( 'name', 'wcfm-add-to-cart' );
	});
});