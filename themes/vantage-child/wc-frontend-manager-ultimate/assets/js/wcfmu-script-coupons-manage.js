jQuery(document).ready( function($) {
	if( ! $("#product_ids").hasClass('wcfm_ele_for_vendor') ) {
		$("#product_ids").select2({
			placeholder: wcfm_dashboard_messages.search_product_select2
		});
	}
	
	// Select2 Intialize
	$("#exclude_product_ids").select2({
		placeholder: wcfm_dashboard_messages.search_product_select2
	});
  
  $("#product_categories").select2({
		placeholder: wcfm_dashboard_messages.choose_category_select2
	});
	
	$("#exclude_product_categories").select2({
		placeholder: wcfm_dashboard_messages.no_category_select2
	});
});