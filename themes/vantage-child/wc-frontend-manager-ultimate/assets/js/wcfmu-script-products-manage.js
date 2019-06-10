jQuery(document).ready(function($) {
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
	
	$('.wcfm_select_all_attributes').each(function() {
		$(this).on('click', function() {
		  $( this ).parent().find( 'select option' ).attr( 'selected', 'selected' );
		  $( this ).parent().find( 'select' ).change();
		});
	});
	
	$('.wcfm_select_no_attributes').each(function() {
		$(this).on('click', function() {
		  $( this ).parent().find( 'select option' ).removeAttr( 'selected' );
		  $( this ).parent().find( 'select' ).change();
		});
	});
			
	
	// Associate Listing - WP Job Manager Support
	if( $('#wpjm_listings').length > 0 ) {
		$('#wpjm_listings').select2({
			placeholder: wcfm_dashboard_messages.choose_listings_select2
		});
	}
} );