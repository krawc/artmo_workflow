jQuery(document).ready(function($) {
	if( $('#wcfm-main-contentainer').length > 0 ) { 
		$('#wcfm-main-contentainer').hide()
		function unwrapSelect() {
			$('#wcfm-main-contentainer').find('select').each(function() {
				if ( $(this).parent().is( "span" ) ) {
				 $(this).unwrap( "span" );
				}
			});
			setTimeout( function() {  unwrapSelect(); }, 500 );
		}
		
		setTimeout( function() { 
			$('#wcfm-main-contentainer').find('select').each(function() {
				if ( $(this).parent().is( "span" ) ) {
				 $(this).css( 'padding', '5px' ).css( 'min-width', '15px' ).css( 'min-height', '35px' ); //.change();
				}
				if ($(this).data('select2')) { 
				  $(this).select2('destroy');
				  $(this).select2({dropdownParent: $('.wcfm-tabWrap') });
				}
			});
			unwrapSelect();
		}, 500 );
		
		var container_height = $(window).height();
		var container_width = $(window).width();
		$('#wcfm-main-contentainer').css( 'height', ( container_height - 50 ) + 'px' );
		$('#wcfm-main-contentainer').css( 'width', ( container_width - 50 ) + 'px' );
		
		$('#wcfm-add-product').click(function( event ) {
			event.preventDefault();
			$('#wcfm-main-contentainer').css( 'position', 'relative' );
			$('#wcfm-main-contentainer').css( 'left', 'auto' );
		  jQuery.fancybox.open($('#wcfm-main-contentainer').show());
		  $('#wcfm-main-contentainer').find('#title').focus();
		  return false;
		});
		
		
		function wcfm_products_manage_form_validate() {
			product_form_is_valid = true;
			$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
			var title = $.trim($('#wcfm_products_manage_form').find('#title').val());
			if(title.length == 0) {
				product_form_is_valid = false;
				$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_products_manage_messages.no_title).addClass('wcfm-error').slideDown();
				audio.play();
			}
			
			$( document.body ).trigger( 'wcfm_products_manage_form_validate' );
			
			$wcfm_is_valid_form = product_form_is_valid;
			$( document.body ).trigger( 'wcfm_form_validate' );
			product_form_is_valid = $wcfm_is_valid_form;
			
			return product_form_is_valid;
		}
		
		// Draft Product
		$('#wcfm_products_simple_draft_button').off('click').on('click', function(event) {
			event.preventDefault();
			
			// Validations
			$is_valid = wcfm_products_manage_form_validate();
			
			if($is_valid) {
				$('#wcfm-content').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				
				var excerpt = '';
				if( $('#excerpt').hasClass('rich_editor') ) {
					if( tinymce.get('excerpt') != null ) excerpt = tinymce.get('excerpt').getContent({format: 'raw'});
				} else {
					excerpt = $('#excerpt').val();
				}
				
				var description = '';
				if( $('#description').hasClass('rich_editor') ) {
					if( tinymce.get('description') != null ) description = tinymce.get('description').getContent({format: 'raw'});
				} else {
					description = $('#description').val();
				}
				var data = {
					action : 'wcfm_ajax_controller',
					controller : 'wcfm-products-manage', 
					wcfm_products_manage_form : $('#wcfm_products_manage_form').serialize(),
					excerpt     : excerpt,
					description : description,
					status : 'draft',
					removed_variations : removed_variations,
					removed_person_types : removed_person_types
				}	
				$.post(wcfm_params.ajax_url, data, function(response) {
					if(response) {
						$response_json = $.parseJSON(response);
						$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
						if($response_json.status) {
							$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
								//if( $response_json.redirect ) window.location = $response_json.redirect;	
								$('#wcfm-content').unblock();
							} );
						} else {
							$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
						}
						if($response_json.id) $('#pro_id').val($response_json.id);
						$('#wcfm-content').unblock();
					}
				});	
			}
		});
		
		// Submit Product
		$('#wcfm_products_simple_submit_button').off('click').on('click', function(event) {
			event.preventDefault();
			
			// Validations
			$is_valid = wcfm_products_manage_form_validate();
			
			if($is_valid) {
				$('#wcfm-content').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				
				var excerpt = '';
				if( $('#excerpt').hasClass('rich_editor') ) {
					if( tinymce.get('excerpt') != null ) excerpt = tinymce.get('excerpt').getContent({format: 'raw'});
				} else {
					excerpt = $('#excerpt').val();
				}
				
				var description = '';
				if( $('#description').hasClass('rich_editor') ) {
					if( tinymce.get('description') != null ) description = tinymce.get('description').getContent({format: 'raw'});
				} else {
					description = $('#description').val();
				}
				
				var data = {
					action : 'wcfm_ajax_controller',
					controller : 'wcfm-products-manage',
					wcfm_products_manage_form : $('#wcfm_products_manage_form').serialize(),
					excerpt     : excerpt,
					description : description,
					status : 'submit',
					removed_variations : removed_variations,
					removed_person_types : removed_person_types
				}	
				$.post(wcfm_params.ajax_url, data, function(response) {
					if(response) {
						$response_json = $.parseJSON(response);
						$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
						if($response_json.status) {
							$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
								//if( $response_json.redirect ) window.location = $response_json.redirect;
								$("#products").append('<option value="' + $response_json.id + '">' + $response_json.title  + '</option>');
								$('#products').val($response_json.id);
								$("#products").trigger('chosen:updated'); 
								jQuery.fancybox.close();
								
								// Reset Product Form
								$('#wcfm_products_manage_form').find('#title').val('');
								$('#wcfm_products_manage_form').find('#excerpt').val('');
								$('#wcfm_products_manage_form').find('#description').val('');
								$('#pro_id').val(0);
							} );
						} else {
							$('#wcfm_products_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
						}
						$('#wcfm-content').unblock();
					}
				});
			}
		});
		
	}
});