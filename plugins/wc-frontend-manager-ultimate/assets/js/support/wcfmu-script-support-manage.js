jQuery(document).ready( function($) {
	$wcfm_messages_table = '';
	
	// Save Settings
	$('#wcfm_reply_send_button').click(function(event) {
	  event.preventDefault();
	  
	  var support_ticket_reply = getWCFMEditorContent( 'support_ticket_reply' );
  
	  // Validations
	  $is_valid = true;
	  
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		if(wcfmstripHtml(support_ticket_reply).length <= 1) {
			$is_valid = false;
			$('#wcfm_support_ticket_reply_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_support_manage_messages.no_reply).addClass('wcfm-error').slideDown();
			audio.play();
		}
	  
	  if($is_valid) {
			$('#wcfm_support_ticket_reply_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                         : 'wcfm_ajax_controller',
				controller                     : 'wcfm-support-manage',
				support_ticket_reply           : support_ticket_reply,
				wcfm_support_ticket_reply_form : jQuery('#wcfm_support_ticket_reply_form').serialize()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						tinymce.get('support_ticket_reply').setContent('');
						audio.play();
						$('#wcfm_support_ticket_reply_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow" , function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('#wcfm_support_ticket_reply_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_support_ticket_reply_form').unblock();
				}
			});	
		}
	});
});