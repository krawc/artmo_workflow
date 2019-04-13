jQuery(document).ready(function($) {
	// Quote Status Update
	$('#wcfm_modify_quote_status').click(function(event) {
		event.preventDefault();
		modifyWCFMRentalQuoteStatus();
		return false;
	});
		
	function modifyWCFMRentalQuoteStatus() {
		$('#quotes_details_general_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action       : 'wcfm_modify_rental_quote_status',
			quote_status : $('#wcfm_quote_status').val(),
			quote_price  : $('#wcfm_quote_price').val(),
			quote_id     : $('#wcfm_modify_quote_status').data('quoteid')
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				$('#quotes_details_general_expander').unblock();
			}
		});
	}
});