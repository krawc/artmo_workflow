jQuery(document).ready(function($) {
  $('#_woo_vou_enable_recipient_name').change(function() {
    if( $(this).is(':checked') ) {
    	$('._woo_vou_enable_recipient_name_ele').removeClass('wcfm_ele_hide');
    } else {
    	$('._woo_vou_enable_recipient_name_ele').addClass('wcfm_ele_hide');
    }
    resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
   $('#_woo_vou_enable_recipient_email').change(function() {
    if( $(this).is(':checked') ) {
    	$('._woo_vou_enable_recipient_email_ele').removeClass('wcfm_ele_hide');
    } else {
    	$('._woo_vou_enable_recipient_email_ele').addClass('wcfm_ele_hide');
    }
    resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
   $('#_woo_vou_enable_recipient_message').change(function() {
    if( $(this).is(':checked') ) {
    	$('._woo_vou_enable_recipient_message_ele').removeClass('wcfm_ele_hide');
    } else {
    	$('._woo_vou_enable_recipient_message_ele').addClass('wcfm_ele_hide');
    }
    resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
   $('#_woo_vou_enable_recipient_giftdate').change(function() {
    if( $(this).is(':checked') ) {
    	$('._woo_vou_enable_recipient_giftdate_ele').removeClass('wcfm_ele_hide');
    } else {
    	$('._woo_vou_enable_recipient_giftdate_ele').addClass('wcfm_ele_hide');
    }
    resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
  var startDateTextBox = $('#_woo_vou_product_start_date');
	var endDateTextBox = $('#_woo_vou_product_exp_date');

	$.timepicker.datetimeRange(
			startDateTextBox,
			endDateTextBox,
			{
					minInterval: (1000*60), // 1min
					dateFormat: 'dd-mm-yy',
					timeFormat: 'HH:mm',
					start: {}, // start picker options
					end: {} // end picker options
			}
	);
	
	$('#_woo_vou_pdf_template_selection').select2({ containerCssClass : "_woo_vou_enable_pdf_template_selection_ele" });
	
	$('#_woo_vou_enable_pdf_template_selection').change(function() {
    if( $(this).is(':checked') ) {
    	$('._woo_vou_enable_pdf_template_selection_non_ele').addClass('wcfm_ele_hide');
    	$('._woo_vou_enable_pdf_template_selection_ele').removeClass('wcfm_ele_hide');
    } else {
    	$('._woo_vou_enable_pdf_template_selection_non_ele').removeClass('wcfm_ele_hide');
    	$('._woo_vou_enable_pdf_template_selection_ele').addClass('wcfm_ele_hide');
    }
    resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
  if( !$('#_woo_vou_sec_vendor_users').hasClass('wcfm_ele_hide') ) { 
  	$('#_woo_vou_sec_vendor_users').select2();
  }
  
  $('#_woo_vou_days_diff').change(function() {
  	$_woo_vou_days_diff = $(this).val();
  	$('.woo_vou_days_diff_custom_ele').addClass('wcfm_ele_hide');
  	$('.woo_vou_days_diff_custom_non_ele').addClass('wcfm_ele_hide');
  	if( $_woo_vou_days_diff == 'cust' ) {
  		$('.woo_vou_days_diff_custom_ele').removeClass('wcfm_ele_hide');
  	} else {
  		$('.woo_vou_days_diff_custom_non_ele').removeClass('wcfm_ele_hide');
  	}
  	resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
  $('#_woo_vou_exp_type').change(function() {
  	$_woo_vou_exp_type = $(this).val();
  	$('.specific_date_ele').addClass('wcfm_ele_hide');
  	$('.based_on_purchase_ele').addClass('wcfm_ele_hide');
  	$('.woo_vou_days_diff_custom_non_ele').addClass('wcfm_ele_hide');
  	if( $_woo_vou_exp_type == 'specific_date' ) {
  		$('.specific_date_ele').removeClass('wcfm_ele_hide');
  	} else if( $_woo_vou_exp_type == 'based_on_purchase' ) {
  		$('.based_on_purchase_ele').removeClass('wcfm_ele_hide');
  		$('.woo_vou_days_diff_custom_non_ele').removeClass('wcfm_ele_hide');
  	} else {
  		
  	}
  	resetCollapsHeight($('#_woo_vou_enable_recipient_name'));
  }).change();
  
  var woo_vou_start_date = $('#_woo_vou_start_date');
	var woo_vou_exp_date = $('#_woo_vou_exp_date');

	$.timepicker.datetimeRange(
			woo_vou_start_date,
			woo_vou_exp_date,
			{
					minInterval: (1000*60), // 1min
					dateFormat: 'dd-mm-yy',
					timeFormat: 'HH:mm',
					start: {}, // start picker options
					end: {} // end picker options
			}
	);
	
	$('#_woo_vou_disable_redeem_day').select2();
	
	// Generate Voucher Codes
	$('.wcfm_voucher_code_popup').click(function(event) {
	  event.preventDefault();
	  
	  var data = {
			action  : 'wcfm_generate_voucher_code_html',
		}	
		
		jQuery.ajax({
			type    :		'POST',
			url     : wcfm_params.ajax_url,
			data    : data,
			success :	function(response) {
				// Intialize colorbox
				jQuery.colorbox( { html: response, width: $popup_width,
					onComplete:function() {
				
						// Intialize Quick Update Action
						jQuery('#wcfm_generate_voucher_code_button').click(function() {
							jQuery('#wcfm_generate_voucher_code_form').block({
								message: null,
								overlayCSS: {
									background: '#fff',
									opacity: 0.6
								}
							});
							jQuery('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
							
							var existing_code = $('#_woo_vou_codes').val();
							var delete_code = '';
							var no_of_voucher = $( 'input[name=woo-vou-no-of-voucher]' ).val();
							var code_prefix = $( 'input[name=woo-vou-code-prefix]' ).val();
							var code_seperator = $( 'input[name=woo-vou-code-seperator]' ).val();
							var code_pattern = $( 'input[name=woo-vou-code-pattern]' ).val();
							
							if( no_of_voucher == '' ) {
								jQuery('#wcfm_generate_voucher_code_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + WooVouMeta.noofvouchererror).addClass('wcfm-error').slideDown();
								jQuery('#wcfm_generate_voucher_code_form').unblock();
							} else if( code_pattern == '' ) {
								jQuery('#wcfm_generate_voucher_code_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + WooVouMeta.patternemptyerror).addClass('wcfm-error').slideDown();
								jQuery('#wcfm_generate_voucher_code_form').unblock();
							} else if( code_pattern.indexOf('l') == '-1' && code_pattern.indexOf('d') == '-1' && code_pattern.indexOf('L') == '-1' && code_pattern.indexOf('D') == '-1' ) {
								jQuery('#wcfm_generate_voucher_code_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + WooVouMeta.generateerror).addClass('wcfm-error').slideDown();
								jQuery('#wcfm_generate_voucher_code_form').unblock();
							} else {
								var data = {
									action			  : 'woo_vou_import_code',
									noofvoucher		: no_of_voucher,
									codeprefix		: code_prefix,
									codeseperator	: code_seperator,
									codepattern		: code_pattern,
									existingcode	: existing_code,
									deletecode		: delete_code
								};
								jQuery.post(wcfm_params.ajax_url, data, function(response) {
									if(response) {
										var import_code = response;
										$( '#_woo_vou_codes' ).val(import_code);
										jQuery('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
										jQuery('#wcfm_generate_voucher_code_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + WooVouMeta.vouchercodegenerated).addClass('wcfm-success').slideDown();
										jQuery('#wcfm_generate_voucher_code_form').unblock();
										setTimeout(function() {
											jQuery.colorbox.remove();
										}, 2000);
									}
								} );
							}
						});
					}
				});
				jQuery('.products').unblock();
			}
		});
	});
});