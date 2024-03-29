jQuery(document).ready(function($) {
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
	
	if( $('#dropdown_shop_coupon_type').length > 0 ) {
		$('#dropdown_shop_coupon_type').on('change', function() {
		  $coupon_type = $('#dropdown_shop_coupon_type').val();
		  $wcfm_coupons_table.ajax.reload();
		});
	}
	
	
	// Delete Coupon
	$( document.body ).on( 'updated_wcfm-coupons', function() {
		$('.wcfm_coupon_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Coupon'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMCoupon($(this));
				return false;
			});
		});
	});
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm-coupons', function() {
		$.each(wcfm_coupons_screen_manage, function( column, column_val ) {
		  $wcfm_coupons_table.column(column).visible( false );
		} );
	});
	
	function deleteWCFMCoupon(item) {
		$('#wcfm-coupons_wrapper').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'delete_wcfm_coupon',
			couponid : item.data('couponid')
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				$wcfm_coupons_table.ajax.reload();
				$('#wcfm-coupons_wrapper').unblock();
			}
		});
	}
	
} );