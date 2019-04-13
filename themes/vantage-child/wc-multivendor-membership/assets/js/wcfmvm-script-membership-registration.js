jQuery(document).ready(function($) {
	if( $(".country_select").length > 0 ) {
		$(".country_select").select2();
	}	
	
	// Email Verification
	if( $('.wcfm_email_verified_input').length > 0 ) {
		$('#user_email').on( 'blur', function() {
			sendEmailVerificationCode();
		});
		$('.wcfm_email_verified_button').on( 'click', function(e) {
			e.preventDefault();
			sendEmailVerificationCode();
			return false;
		});
	}
	
	function sendEmailVerificationCode() {
		
		$('#user_email').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		
	  $is_valid = true;
	  if( $is_valid ) {
	  	$user_email = $('#user_email').val();
	  	if( !$user_email ) {
	  		$('#user_email').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
	  		$('#wcfm_membership_container .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + $('#user_email').data('required_message') ).addClass('wcfm-error').slideDown();
	  		$is_valid = false;
	  	} else {
	  		$('#user_email').addClass('wcfm_validation_success').removeClass('wcfm_validation_failed');
	  	}
	  }
	  
		if( $is_valid ) {
			var data = {
				action                             : 'wcfmvm_email_verification_code',
				user_email                         : $('#user_email').val()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.status) {
						$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
						$('#wcfm_membership_registration_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_membership_registration_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#user_email').unblock();
				}
			});
		} else {
			$('#user_email').unblock();
		}
	}
	
	function setStateBoxforCountry( countryBox ) {
		var states_json = wc_country_select_params.countries.replace( /&quot;/g, '"' ),
				states = $.parseJSON( states_json ),
				country = countryBox.val();

		if ( states[ country ] ) {
			if ( $.isEmptyObject( states[ country ] ) ) {
				countryBox.parent().find('.wcfmvm_state_to_select').each(function() {
					$statebox = $(this);
					$statebox_id = $statebox.attr('id');
					$statebox_name = $statebox.attr('name');
					$statebox_val = $statebox.val();
					$statebox_dataname = $statebox.data('name');
					
					if ( $statebox.is( 'select' ) ) {
						$statebox.replaceWith( '<input type="text" name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" value="'+$statebox_val+'" class="wcfm-text wcfmvm_state_to_select" />' );
					}
				});
			} else {
				input_selected_state = '';
				var options = '',
						state = states[ country ];

				countryBox.parent().find('.wcfmvm_state_to_select').each(function() {
					$statebox = $(this);
					$statebox_id = $statebox.attr('id');
					$statebox_name = $statebox.attr('name');
					$statebox_val = $statebox.val();
					$statebox_dataname = $statebox.data('name');
					
					for ( var index in state ) {
						if ( state.hasOwnProperty( index ) ) {
							if ( $statebox_val ) {
								if ( $statebox_val == index ) {
									var selected_value = 'selected="selected"';
								} else {
									var selected_value = '';
								}
							}
							options = options + '<option value="' + index + '"' + selected_value + '>' + state[ index ] + '</option>';
						}
					}
					
					if ( $statebox.is( 'select' ) ) {
						$statebox.html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option>' + options );
					}
					if ( $statebox.is( 'input' ) ) {
						$statebox.replaceWith( '<select name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" class="wcfm-select wcfmvm_state_to_select"></select>' );
						$statebox = $('#'+$statebox_id);
						$statebox.html( '<option value="">' + wc_country_select_params.i18n_select_state_text + '</option>' + options );
					}
					$statebox.val( $statebox_val );
				});
			}
		} else {
			countryBox.parent().find('.wcfmvm_state_to_select').each(function() {
				$statebox = $(this);
				$statebox_id = $statebox.attr('id');
				$statebox_name = $statebox.attr('name');
				$statebox_val = $statebox.val();
				$statebox_dataname = $statebox.data('name');
				
				if ( $statebox.is( 'select' ) ) {
					$statebox.replaceWith( '<input type="text" name="'+$statebox_name+'" id="'+$statebox_id+'" data-name="'+$statebox_dataname+'" value="'+$statebox_val+'" class="wcfm-text wcfmvm_state_to_select multi_input_block_element" />' );
				}
			});
		}
	}
	
	$('.wcfmvm_country_to_select').each(function() {
	  $(this).change(function() {
	    setStateBoxforCountry( $(this) );
	  }).change();
	});
		
	// Membership Registration
	$('#wcfm_membership_register_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
	  $wcfm_is_valid_form = true;
	  $( document.body ).trigger( 'wcfm_form_validate', $('#wcfm_membership_registration_form') );
	  $is_valid = $wcfm_is_valid_form;
	  
	  if( $is_valid ) {
	  	$password = $('#passoword').val();
	  	$confirm_pwd = $('#confirm_pwd').val();
	  	if( $password != $confirm_pwd ) {
	  		$('#passoword').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
	  		$('#confirm_pwd').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
	  		$('#wcfm_membership_container .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + $('#passoword').data('mismatch_message') ).addClass('wcfm-error').slideDown();
	  		$is_valid = false;
	  	}
	  }
	  
		$('#wcfm_membership_container').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		if( $is_valid ) {
			var data = {
				action                             : 'wcfm_ajax_controller',
				controller                         : 'wcfm-memberships-registration',
				wcfm_membership_registration_form  : $('#wcfm_membership_registration_form').serialize(),
				status                             : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.status) {
						$('#wcfm_membership_registration_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );	
					} else {
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_membership_registration_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_membership_container').unblock();
				}
			});
		} else {
			$('#wcfm_membership_container').unblock();
		}
	});
} );