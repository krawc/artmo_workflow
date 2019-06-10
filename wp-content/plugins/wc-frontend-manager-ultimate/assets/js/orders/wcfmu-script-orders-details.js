jQuery(document).ready(function($) {
	// PDF Invoice
	$('.wcfm_pdf_invoice').each(function() {
		$(this).click(function(event) {
			event.preventDefault();
			downloadPDFInvoiceWCFMOrder($(this));
			return false;
		});
	});
	
	function downloadPDFInvoiceWCFMOrder(item) {
		if (wcfm_params.ajax_url.indexOf("?") != -1) {
			url = wcfm_params.ajax_url + '&action=wcfm_order_pdf_invoice&template_type=invoice&order_id='+item.data('orderid');
		} else {
			url = wcfm_params.ajax_url + '?action=wcfm_order_pdf_invoice&template_type=invoice&order_id='+item.data('orderid')
		}
		window.open(url,'_blank');
	}
	
	// PDF Packing Slip
	$('.wcfm_pdf_packing_slip').each(function() {
		$(this).click(function(event) {
			event.preventDefault();
			downloadPDFPackingSlipWCFMOrder($(this));
			return false;
		});
	});
	
	function downloadPDFPackingSlipWCFMOrder(item) {
		if (wcfm_params.ajax_url.indexOf("?") != -1) {
			url = wcfm_params.ajax_url + '&action=wcfm_order_pdf_packing_slip&template_type=packing_slip&order_id='+item.data('orderid');
		} else {
			url = wcfm_params.ajax_url + '?action=wcfm_order_pdf_packing_slip&template_type=packing-slip&order_id='+item.data('orderid')
		}
		window.open(url, '_blank');
	}
	
	// Order Add Note
	$('#wcfm_add_order_note').click(function(event) {
		event.preventDefault();
		addWCFMOrderNote();
		return false;
	});
		
	function addWCFMOrderNote() {
		$('#wcfm_order_notes_options').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action    : 'wcfm_add_order_note',
			note      : $('#add_order_note').val(),
			note_type : $('#order_note_type').val(),
			order_id  : $('#wcfm_add_order_note').data('orderid')
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				$('#notes_holder').append(response);
				$('#add_order_note').val('');
				$('#wcfm_order_notes_options').unblock();
			}
		});
	}
	
	$('.wcfm_order_mark_shipped').each(function() {
		$(this).click(function(event) {
			event.preventDefault();
			manageVendorShippingTracking( $(this) );
			return false;
		});
	});
	
	function manageVendorShippingTracking( item ) {
		var data = {
							  action  : 'wcfmu_shipment_tracking_html',
							}
		jQuery.ajax({
			type    :		'POST',
			url     : wcfm_params.ajax_url,
			data    : data,
			success :	function(response) {
														 
				// Intialize colorbox
				$.colorbox( { html: response, height: 300, width: $popup_width,
					onComplete:function() {
						$('#wcfm_tracking_button').click(function(e) {
							e.preventDefault();
							
							$('#wcfm_shipping_tracking_form').block({
									message: null,
									overlayCSS: {
										background: '#fff',
										opacity: 0.6
									}
								});
							
							jQuery( document.body ).trigger( 'wcfm_form_validate', jQuery('#wcfm_shipping_tracking_form') );
							if( !$wcfm_is_valid_form ) {
								wcfm_notification_sound.play();
								jQuery('#wcfm_shipping_tracking_form').unblock();
							} else {
								var tracking_url  = $('#wcfm_tracking_url').val();
								var tracking_code = $('#wcfm_tracking_code').val();
								
								$('#wcfm_tracking_button').hide();
								$('#wcfm_shipping_tracking_form .wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
								
								var data = {
									action        : item.data('shipped_action'),
									orderid       : item.data('orderid'),
									productid     : item.data('productid'),
									orderitemid   : item.data('orderitemid'),
									tracking_url  : tracking_url,
									tracking_code : tracking_code
								}	
								$.ajax({
									type:		'POST',
									url: wcfm_params.ajax_url,
									data: data,
									success:	function(response) {
										wcfm_notification_sound.play();
										$('#wcfm_shipping_tracking_form').unblock();
										$('#wcfm_shipping_tracking_form .wcfm-message').html( '<span class="wcicon-status-completed"></span>' + wcfm_shipping_tracking_labels.tracking_saved ).addClass('wcfm-success').slideDown();
										setTimeout(function() {
											$.colorbox.remove();
											window.location = window.location.href;
										}, 2000);
									}
								});
							}
						});
					}
				});
			}
		});
	}
});