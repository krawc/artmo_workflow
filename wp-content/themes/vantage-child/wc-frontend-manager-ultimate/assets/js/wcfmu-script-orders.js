jQuery(document).ready(function($) {
	if( $('#filter-by-date').length > 0 ) {
		$('#filter-by-date').on('change', function() {
			$filter_by_date = $('#filter-by-date').val();
			$wcfm_orders_table.ajax.reload();
		});
	}
	
	if( $('#commission-status').length > 0 ) {
		$('#commission-status').on('change', function() {
			$commission_status = $('#commission-status').val();
			$wcfm_orders_table.ajax.reload();
		});
	}
		
  
	// PDF Invoice
	$( document.body ).on( 'updated_wcfm-orders', function() {
		$('.wcfm_pdf_invoice').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				downloadPDFInvoiceWCFMOrder($(this));
				return false;
			});
		});
	});
	
	function downloadPDFInvoiceWCFMOrder(item) {
		if (wcfm_params.ajax_url.indexOf("?") != -1) {
			url = wcfm_params.ajax_url + '&action=wcfm_order_pdf_invoice&template_type=invoice&order_id='+item.data('orderid');
		} else {
			url = wcfm_params.ajax_url + '?action=wcfm_order_pdf_invoice&template_type=invoice&order_id='+item.data('orderid')
		}
		window.open(url, '_blank');
	}
	
	// PDF Packing Slip
	$( document.body ).on( 'updated_wcfm-orders', function() {
		$('.wcfm_pdf_packing_slip').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				downloadPDFPackingSlipWCFMOrder($(this));
				return false;
			});
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
		
	// Delete Order
	$( document.body ).on( 'updated_wcfm-orders', function() {
		$('.wcfm_order_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm( wcfm_dashboard_messages.order_delete_confirm );
				if(rconfirm) deleteWCFMOrder($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMOrder(item) {
		$('#wcfm-orders_wrapper').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'delete_wcfm_order',
			orderid : item.data('orderid')
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				$wcfm_orders_table.ajax.reload();
				$('#wcfm-orders_wrapper').unblock();
			}
		});
	}
	
	$( document.body ).on( 'updated_wcfm-orders', function() {
		//WC Vendors Mark Order Shipped
		$('.wcfm_wcvendors_order_mark_shipped').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				manageVendorShippingTracking( $(this), 'wcfm_wcvendors_order_mark_shipped' );
				return false;
			});
		});
		
		// WC Product Vendors Mark Order Shipped
		$('.wcfm_wcpvendors_order_mark_fulfilled').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				manageVendorShippingTracking( $(this), 'wcfm_wcpvendors_order_mark_fulfilled' );
				return false;
			});
		});
		
		// WC Marketplace Mark Order Shipped
		$('.wcfm_wcmarketplace_order_mark_shipped').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				manageVendorShippingTracking( $(this), 'wcfm_wcmarketplace_order_mark_shipped' );
				return false;
			});
		});
		
		// Dokan Mark Order Shipped
		$('.wcfm_dokan_order_mark_shipped').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				manageVendorShippingTracking( $(this), 'wcfm_dokan_order_mark_shipped' );
				return false;
			});
		});
	});
	
	function manageVendorShippingTracking( item, mark_shipped_action ) {
		var shippingTrackingHTML = '<div id="wcfm_shipping_tracking_form" class="wcfm-collapse-content">' +
															 '<h2>' + wcfm_shipping_tracking_labels.tracking_heading + '</h2><br />' +
															 '<div style="padding: 10px;"><p class="wcfm_title"><strong>' + wcfm_shipping_tracking_labels.tracking_code_label + ':</strong></p>' +
															 '<input type="text" id="wcfm_tracking_code" name="wcfm_tracking_code" class="wcfm-text" /></div>' +
															 '<div style="padding: 10px;"><p class="wcfm_title"><strong>' + wcfm_shipping_tracking_labels.tracking_url_label + ':</strong></p>' +
															 '<input type="text" id="wcfm_tracking_url" name="wcfm_tracking_url" class="wcfm-text" /></div>' +
															 '<div class="wcfm-message"></div>' +
															 '<input type="submit" id="wcfm_tracking_button" name="wcfm_tracking_button" class="wcfm_submit_button" value="' + wcfm_shipping_tracking_labels.tracking_button_label + '" />' +
															 '</div>';
		jQuery.fancybox.open(shippingTrackingHTML);
		
		$('#wcfm_tracking_button').click(function(e) {
		  e.preventDefault();
		  
		  var tracking_url  = $('#wcfm_tracking_url').val();
		  var tracking_code = $('#wcfm_tracking_code').val();
		  
		  if( tracking_url.length > 0 && tracking_code.length > 0 ) {
		  	$('#wcfm_tracking_button').hide();
		  	$('#wcfm_shipping_tracking_form .wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
				$('#wcfm_shipping_tracking_form').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				var data = {
					action        : mark_shipped_action,
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
						$wcfm_orders_table.ajax.reload();
						$('#wcfm_shipping_tracking_form').unblock();
						$('#wcfm_shipping_tracking_form .wcfm-message').html( '<span class="wcicon-status-completed"></span>' + wcfm_shipping_tracking_labels.tracking_saved ).addClass('wcfm-success').slideDown();
						setTimeout(function() {
							jQuery.fancybox.close();
						}, 2000);
					}
				});
			} else {
				$('#wcfm_shipping_tracking_form .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + wcfm_shipping_tracking_labels.tracking_missing ).addClass('wcfm-error').slideDown();
			}
		});
	}
} );