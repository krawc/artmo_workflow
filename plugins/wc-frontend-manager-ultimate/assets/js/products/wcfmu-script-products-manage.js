jQuery(document).ready(function($) {
	if( wcfm_is_allow_downlodable_file_field.is_allow ) {
		$('.downlodable_file').addClass('downlodable_file_visible');
		$('.downlodable_file').attr( 'readonly', false );
	}
		
	// Category checklist view cat limit control
	if( $('#product_cats_checklist').length > 0 ) {
		var catlimit = $('#product_cats_checklist').data('catlimit');
		if( catlimit != -1 ) {
			$('#product_cats_checklist').find('.wcfm-checkbox').change(function() {
			  var checkedCount = $('#product_cats_checklist').find('.wcfm-checkbox:checked').length;
			  if( checkedCount > catlimit ) {
			  	$(this).attr( 'checked', false );
			  }
			});
		}
	}
		
	$('.wcfm_add_attributes_new_term').each(function() {
		$(this).on('click', function() {
			var term = prompt( wcfm_dashboard_messages.add_attribute_term );
			if (term != null) {
				$wrapper = $(this).parent();
				var taxonomy = $wrapper.find('[data-name="term_name"]').val();
				var data         = {
					action:   'wcfmu_add_attribute_term',
					taxonomy: taxonomy,
					term:     term
				};
		
				$('#attributes').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				
				$.ajax({
					type:		'POST',
					url: wcfm_params.ajax_url,
					data: data,
					success:	function(response) {
						if(response) {
							if ( response.error ) {
								// Error.
								window.alert( response.error );
							} else if ( response.slug ) {
								// Success.
								$wrapper.find( 'select.wc_attribute_values' ).append( '<option value="' + response.term_id + '" selected="selected">' + response.name + '</option>' );
								$wrapper.find( 'select.wc_attribute_values' ).change();
							}
			
							$( '#attributes' ).unblock();
						}
					}
				});
			}
		});
	});
	
	// Associate Listing - WP Job Manager Support
	if( $('#wpjm_listings').length > 0 ) {
		$('#wpjm_listings').select2({
			placeholder: wcfm_dashboard_messages.choose_listings_select2
		});
	}
	
	if( $('.add_product_tab').length > 0 ) {
		$('.add_product_tab').on('click', function() {
			setTimeout(function() {
				$('.remove_row').addClass('wcfm_submit_button');
				resetCollapsHeight($('#woocommerce_product_tabs'));
			}, 100);
		});
	}
} );