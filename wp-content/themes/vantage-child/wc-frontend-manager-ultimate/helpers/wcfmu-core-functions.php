<?php
if(!function_exists('wcfmu_woocommerce_inactive_notice')) {
	function wcfmu_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Ultimate is inactive.%s The %sWooCommerce plugin%s must be active for the WCFM - Ultimate to work. Please %sinstall & activate WooCommerce%s', WCFMu_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmu_wcfm_inactive_notice')) {
	function wcfmu_wcfm_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Ultimate is inactive.%s The %sWooCommerce Frontend Manager%s must be active for the WCFM - Ultimate to work. Please %sinstall & activate WooCommerce Frontend Manager%s', WCFMu_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-frontend-manager/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+frontend+manager' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if( !function_exists( 'wcfm_is_wcvpro' ) ) {
	function wcfm_is_wcvpro() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		// WC Booking Check
		$is_wcvpro = ( in_array( 'wc-vendors-pro/wcvendors-pro.php', $active_plugins ) || array_key_exists( 'wc-vendors-pro/wcvendors-pro.php', $active_plugins ) ) ? 'wcvpro' : false;
		
		return $is_wcvpro;
	}
}

if(!function_exists('get_wcfm_create_bookings_url')) {
	function get_wcfm_create_bookings_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_create_bookings_url = wcfm_get_endpoint_url( 'wcfm-bookings-manual', '', $wcfm_page );
		return $wcfm_create_bookings_url;
	}
}

if(!function_exists('get_wcfm_bookings_resources_url')) {
	function get_wcfm_bookings_resources_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_bookings_resources_url = wcfm_get_endpoint_url( 'wcfm-bookings-resources', '', $wcfm_page );
		return $wcfm_bookings_resources_url;
	}
}

if(!function_exists('get_wcfm_bookings_resources_manage_url')) {
	function get_wcfm_bookings_resources_manage_url( $resource_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_bookings_manage_resources_url = wcfm_get_endpoint_url( 'wcfm-bookings-resources-manage', $resource_id, $wcfm_page );
		return $wcfm_bookings_manage_resources_url;
	}
}

if(!function_exists('get_wcfm_bookings_calendar_url')) {
	function get_wcfm_bookings_calendar_url( $view = 'month', $calendar_day = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_bookings_calendar_url = wcfm_get_endpoint_url( 'wcfm-bookings-calendar', '', $wcfm_page );
		if( $view ) $wcfm_bookings_calendar_url = add_query_arg( 'view', $view, $wcfm_bookings_calendar_url );
		if( $calendar_day ) $wcfm_bookings_calendar_url = add_query_arg( 'calendar_day', $calendar_day, $wcfm_bookings_calendar_url );
		return $wcfm_bookings_calendar_url;
	}
}

if(!function_exists('get_wcfm_bookings_settings_url')) {
	function get_wcfm_bookings_settings_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_view_bookings_settings_url = wcfm_get_endpoint_url( 'wcfm-bookings-settings', '', $wcfm_page );
		return $wcfm_view_bookings_settings_url;
	}
}

if(!function_exists('get_wcfm_rental_url')) {
	function get_wcfm_rental_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_rental_url = wcfm_get_endpoint_url( 'wcfm-rental-calendar', '', $wcfm_page );
		return $get_wcfm_rental_url;
	}
}

if(!function_exists('get_wcfm_rental_quote_url')) {
	function get_wcfm_rental_quote_url( $quote_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_rental_quote_url = wcfm_get_endpoint_url( 'wcfm-rental-quote', '', $wcfm_page );
		if( $quote_status ) $get_wcfm_rental_quote_url = add_query_arg( 'quote_status', $quote_status, $get_wcfm_rental_quote_url );
		return $get_wcfm_rental_quote_url;
	}
}

if(!function_exists('get_wcfm_rental_quote_details_url')) {
	function get_wcfm_rental_quote_details_url( $quote_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_rental_quote_details_url = wcfm_get_endpoint_url( 'wcfm-rental-quote-details', $quote_id, $wcfm_page );
		return $wcfm_rental_quote_details_url;
	}
}

if(!function_exists('get_wcfm_appointments_dashboard_url')) {
	function get_wcfm_appointments_dashboard_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_appointments_dashboard_url = wcfm_get_endpoint_url( 'wcfm-appointments-dashboard', '', $wcfm_page );
		return $wcfm_appointments_dashboard_url;
	}
}

if(!function_exists('get_wcfm_create_appointments_url')) {
	function get_wcfm_create_appointments_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_create_appointments_url = wcfm_get_endpoint_url( 'wcfm-appointments-manual', '', $wcfm_page );
		return $wcfm_create_appointments_url;
	}
}

if(!function_exists('get_wcfm_appointments_staffs_url')) {
	function get_wcfm_appointments_staffs_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_appointments_staffs_url = wcfm_get_endpoint_url( 'wcfm-appointments-staffs', '', $wcfm_page );
		return $wcfm_appointments_staffs_url;
	}
}

if(!function_exists('get_wcfm_appointments_staffs_manage_url')) {
	function get_wcfm_appointments_staffs_manage_url( $staff_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_appointments_manage_staffs_url = wcfm_get_endpoint_url( 'wcfm-appointments-staffs-manage', $staff_id, $wcfm_page );
		return $wcfm_appointments_manage_staffs_url;
	}
}

if(!function_exists('get_wcfm_appointments_url')) {
	function get_wcfm_appointments_url( $appointment_ststus = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_appointments_url = wcfm_get_endpoint_url( 'wcfm-appointments', '', $wcfm_page );
		if( $appointment_ststus ) $wcfm_appointments_url = add_query_arg( 'appointment_status', $appointment_ststus, $wcfm_appointments_url );
		return $wcfm_appointments_url;
	}
}

if(!function_exists('get_wcfm_appointments_calendar_url')) {
	function get_wcfm_appointments_calendar_url( $view = 'month', $calendar_day = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_appointments_calendar_url = wcfm_get_endpoint_url( 'wcfm-appointments-calendar', '', $wcfm_page );
		if( $view ) $wcfm_appointments_calendar_url = add_query_arg( 'view', $view, $wcfm_appointments_calendar_url );
		if( $calendar_day ) $wcfm_appointments_calendar_url = add_query_arg( 'calendar_day', $calendar_day, $wcfm_appointments_calendar_url );
		return $wcfm_appointments_calendar_url;
	}
}

if(!function_exists('get_wcfm_view_appointment_url')) {
	function get_wcfm_view_appointment_url($appointment_id = '') {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_view_appointment_url = wcfm_get_endpoint_url( 'wcfm-appointments-details', $appointment_id, $wcfm_page );
		return $wcfm_view_appointment_url;
	}
}

if(!function_exists('get_wcfm_appointment_settings_url')) {
	function get_wcfm_appointment_settings_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_view_appointment_settings_url = wcfm_get_endpoint_url( 'wcfm-appointments-settings', '', $wcfm_page );
		return $wcfm_view_appointment_settings_url;
	}
}

if(!function_exists('get_wcfm_auction_url')) {
	function get_wcfm_auction_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_auctions_url = wcfm_get_endpoint_url( 'wcfm-auctions', '', $wcfm_page );
		return $get_wcfm_auctions_url;
	}
}

if(!function_exists('get_wcfm_resources_manage_messages')) {
	function get_wcfm_resources_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_title' => __( 'Please insert atleast Resource Title before submit.', 'wc-frontend-manager-ultimate' ),
											'resource_failed' => __( 'Resource Saving Failed.', 'wc-frontend-manager-ultimate' ),
											'resource_published' => __( 'Resource Successfully Published.', 'wc-frontend-manager-ultimate' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_staffs_manage_messages')) {
	function get_wcfm_staffs_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_username' => __( 'Please insert Staff Username before submit.', 'wc-frontend-manager-ultimate' ),
											'no_email' => __( 'Please insert Staff Email before submit.', 'wc-frontend-manager-ultimate' ),
											'username_exists' => __( 'This Username already exists.', 'wc-frontend-manager-ultimate' ),
											'email_exists' => __( 'This Email already exists.', 'wc-frontend-manager-ultimate' ),
											'staff_failed' => __( 'Staff Saving Failed.', 'wc-frontend-manager-ultimate' ),
											'staff_saved' => __( 'Staff Successfully Saved.', 'wc-frontend-manager-ultimate' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_wcappointments_settings_messages')) {
	function get_wcfm_wcappointments_settings_messages() {
		global $WCFMu;
		
		$messages = array(
											'settings_failed' => __( 'Global Settings Saving Failed.', 'wc-frontend-manager-ultimate' ),
											'settings_saved' => __( 'Global Settings Successfully Saved.', 'wc-frontend-manager-ultimate' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfm_shipping_tracking_labels')) {
	function get_wcfm_shipping_tracking_labels() {
		global $WCFMu;
		
		$messages = array(
											'tracking_heading'       => __( 'Shipment Tracking Info', 'wc-frontend-manager-ultimate' ),
											'tracking_code_label'    => __( 'Tracking Code', 'wc-frontend-manager-ultimate' ),
											'tracking_url_label'     => __( 'Tracking URL', 'wc-frontend-manager-ultimate' ),
											'tracking_button_label'  => __( 'Submit', 'wc-frontend-manager' ),
											'tracking_missing'       => __( 'Fill up the details.', 'wc-frontend-manager-ultimate' ),
											'tracking_saved'         => __( 'Details successfully updated.', 'wc-frontend-manager-ultimate' ),
											);
		
		return $messages;
	}
}
?>