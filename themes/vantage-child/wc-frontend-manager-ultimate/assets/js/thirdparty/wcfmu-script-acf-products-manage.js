jQuery(document).ready(function($) {
	$('.wcfm-acf-multi-select').each(function() { $(this).select2(); } );
	
	function processAcfBasedFieldGroupShow() {
		$('.wcfm_cat_based_acf_product_manager_fields').addClass('wcfm_acf_hide wcfm_block_hide wcfm_custom_hide');
		$('#product_cats_checklist').find('input[type="checkbox"]').each(function() {
			if( $(this).is(':checked') ) {
				$cat_val = $(this).val();
				$.each( wcfm_cat_based_acf_fields, function( cat_id, allowed_groups ) {
				  if( $cat_val == cat_id ) {
				  	$.each( allowed_groups, function( i, allowed_group ) {
				  	  $('.wcfm_acf_products_manage_'+allowed_group+'_collapsible').removeClass('wcfm_acf_hide wcfm_block_hide wcfm_custom_hide');
				  	  $('.wcfm_acf_products_manage_'+allowed_group+'_container').removeClass('wcfm_acf_hide wcfm_block_hide wcfm_custom_hide');
				  	});
				  }
				});
			}
		});
		resetCollapsHeight($('#sku'));
	}
	
	$('#product_cats_checklist').find('input[type="checkbox"]').each(function() {
	  $(this).click(function() {
	    processAcfBasedFieldGroupShow();
	  });
	});
	processAcfBasedFieldGroupShow();
});