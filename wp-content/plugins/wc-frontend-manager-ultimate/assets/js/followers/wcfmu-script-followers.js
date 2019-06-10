$wcfm_followers_table = '';

jQuery(document).ready(function($) {
	
	$wcfm_followers_table = $('#wcfm-followers').DataTable( {
		"processing": true,
		"serverSide": true,
		"pageLength": dataTables_config.pageLength,
		"bFilter"   : false,
		"responsive": true,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 2 },
										{ responsivePriority: 1 },
								],
		"columnDefs": [ { "targets": 0, "orderable" : false }, 
										{ "targets": 1, "orderable" : false },
										{ "targets": 2, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action          = 'wcfm_ajax_controller',
				d.controller      = 'wcfm-followers'
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-groups table refresh complete
				$( document.body ).trigger( 'updated_wcfm-followers' );
			}
		}
	} );
	
	// Delete followers
	$( document.body ).on( 'updated_wcfm-followers', function() {
		$('.wcfm_followers_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm(wcfm_dashboard_messages.follower_delete_confirm);
				if(rconfirm) deleteWCFMFollowers($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMFollowers(item) {
		jQuery('#wcfm_followers_listing_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action      : 'delete_wcfm_followers',
			lineid      : item.data('lineid'),
			userid 			: item.data('userid'),
			followersid : item.data('followersid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_followers_table) $wcfm_followers_table.ajax.reload();
				jQuery('#wcfm_followers_listing_expander').unblock();
			}
		});
	}
	
} );