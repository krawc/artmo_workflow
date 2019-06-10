<?php
global $WCFM, $WCFMu, $wp_query;

if( !current_user_can( 'edit_shop_coupons' ) || !apply_filters( 'wcfm_is_allow_manage_coupons', true ) ) {
	return;
}

// Coupon Type Filter
?>
<div class="wcfm_coupons_filter_wrap wcfm_filters_wrap">
	<select name="coupon_type" id="dropdown_shop_coupon_type">
		<option value=""><?php _e( 'Show all types', 'wc-frontend-manager-ultimate' ); ?></option>
		<?php
			$types = apply_filters( 'wcfm_coupon_types', wc_get_coupon_types() );
	
			foreach ( $types as $name => $type ) {
				echo '<option value="' . esc_attr( $name ) . '"';
				echo '>' . esc_html__( $type, 'wc-frontend-manager-ultimate' ) . '</option>';
			}
		?>
	</select>
</div>