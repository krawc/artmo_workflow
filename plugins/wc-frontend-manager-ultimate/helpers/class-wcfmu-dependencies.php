<?php
/**
 * WC Dependency Checker
 *
 */
class WCFMu_Dependencies {
	
	private static $active_plugins;
	
	static function init() {
		self::$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}
	
	// WooCommerce
	static function woocommerce_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		return false;
	}
	
	// WC Frontend Manager
	static function wcfm_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'wc-frontend-manager/wc_frontend_manager.php', self::$active_plugins ) || array_key_exists( 'wc-frontend-manager/wc_frontend_manager.php', self::$active_plugins );
		return false;
	}
	
	// WP Resume Manager Support
	static function wcfm_resume_manager_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'wp-job-manager-resumes/wp-job-manager-resumes.php', self::$active_plugins ) || array_key_exists( 'wp-job-manager-resumes/wp-job-manager-resumes.php', self::$active_plugins );
		return false;
	}
	
	// YITH Auction Premium Support
	static function wcfm_yith_auction_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'yith-woocommerce-auctions-premium/init.php', self::$active_plugins ) || array_key_exists( 'yith-woocommerce-auctions-premium/init.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Simple Auction Support
	static function wcfm_wcs_auction_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-simple-auctions/woocommerce-simple-auctions.php', self::$active_plugins ) || array_key_exists( 'woocommerce-simple-auctions/woocommerce-simple-auctions.php', self::$active_plugins );
		return false;
	}
	
	// WC Rental & Booking Pro Support
	static function wcfm_wc_rental_pro_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-rental-and-booking/redq-rental-and-bookings.php', self::$active_plugins ) || array_key_exists( 'woocommerce-rental-and-booking/redq-rental-and-bookings.php', self::$active_plugins );
		return false;
	}
	
	// WC Appointments Support
	static function wcfm_wc_appointments_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-appointments/woocommerce-appointments.php', self::$active_plugins ) || array_key_exists( 'woocommerce-appointments/woocommerce-appointments.php', self::$active_plugins );
		return false;
	}
	
	// WC Product Addons Support
	static function wcfm_wc_addons_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-product-addons/woocommerce-product-addons.php', self::$active_plugins ) || array_key_exists( 'woocommerce-product-addons/woocommerce-product-addons.php', self::$active_plugins );
		return false;
	}
	
	// WC Bookings Accommodation Support
	static function wcfm_wc_accommodation_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-accommodation-bookings/woocommerce-accommodation-bookings.php', self::$active_plugins ) || array_key_exists( 'woocommerce-accommodation-bookings/woocommerce-accommodation-bookings.php', self::$active_plugins );
		return false;
	}
	
	// WC Per Product Shipping Support - 2.5.0
	static function wcfm_wc_per_peroduct_shipping_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-shipping-per-product/woocommerce-shipping-per-product.php', self::$active_plugins ) || array_key_exists( 'woocommerce-shipping-per-product/woocommerce-shipping-per-product.php', self::$active_plugins );
		return false;
	}
	
	// Toolset Types Support - 2.5.0
	static function wcfm_toolset_types_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'types/wpcf.php', self::$active_plugins ) || array_key_exists( 'types/wpcf.php', self::$active_plugins );
		return false;
	}
	
	// MapPress - 2.6.2
	static function wcfm_mappress_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'mappress-google-maps-for-wordpress/mappress.php', self::$active_plugins ) || array_key_exists( 'mappress-google-maps-for-wordpress/mappress.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Additional Variation Images - 3.0.2
	static function wcfm_wc_variation_gallery_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-additional-variation-images/woocommerce-additional-variation-images.php', self::$active_plugins ) || array_key_exists( 'woocommerce-additional-variation-images/woocommerce-additional-variation-images.php', self::$active_plugins );
		return false;
	}
	
	// Advanced Custom Fields(ACF) - 3.0.4
	static function wcfm_acf_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'advanced-custom-fields/acf.php', self::$active_plugins ) || array_key_exists( 'advanced-custom-fields/acf.php', self::$active_plugins );
		return false;
	}
	
	// Address Geocoder - 3.1.1
	static function wcfm_address_geocoder_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'address-geocoder/address-geocoder.php', self::$active_plugins ) || array_key_exists( 'address-geocoder/address-geocoder.php', self::$active_plugins );
		return false;
	}
	
	// Sitepress WPML - 3.2.0
	static function wcfm_sitepress_wpml_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'sitepress-multilingual-cms/sitepress.php', self::$active_plugins ) || array_key_exists( 'sitepress-multilingual-cms/sitepress.php', self::$active_plugins );
		return false;
	}
	
	// Toolset Maps Support - 3.2.4
	static function wcfm_toolset_address_map_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'toolset-maps/toolset-maps-loader.php', self::$active_plugins ) || array_key_exists( 'toolset-maps/toolset-maps-loader.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Box Office Support - 3.3.3
	static function wcfm_wc_box_office_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-box-office/woocommerce-box-office.php', self::$active_plugins ) || array_key_exists( 'woocommerce-box-office/woocommerce-box-office.php', self::$active_plugins );
		return false;
	}
	
	// Advanced Custom Fields(ACF) Pro - 3.3.7
	static function wcfm_acf_pro_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'advanced-custom-fields-pro/acf.php', self::$active_plugins ) || array_key_exists( 'advanced-custom-fields-pro/acf.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Lottery - 3.5.0
	static function wcfm_wc_lottery_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-lottery/wc-lottery.php', self::$active_plugins ) || array_key_exists( 'woocommerce-lottery/wc-lottery.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Deposit - 3.5.9
	static function wcfm_wc_deposits_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-deposits/woocommmerce-deposits.php', self::$active_plugins ) || array_key_exists( 'woocommerce-deposits/woocommmerce-deposits.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Deposit - 4.0.0
	static function wcfm_wc_pdf_voucher_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-pdf-vouchers/woocommerce-pdf-vouchers.php', self::$active_plugins ) || array_key_exists( 'woocommerce-pdf-vouchers/woocommerce-pdf-vouchers.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Custom Product Tabs Manager Support - 4.1.0
	static function wcfm_wc_tabs_manager_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-tab-manager/woocommerce-tab-manager.php', self::$active_plugins ) || array_key_exists( 'woocommerce-tab-manager/woocommerce-tab-manager.php', self::$active_plugins ) || class_exists( 'WC_Tab_Manager' );
		return false;
	}
	
	// WooCommerce Warranty & Request Support - 4.1.5
	static function wcfm_wc_warranty_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-warranty/woocommerce-warranty.php', self::$active_plugins ) || array_key_exists( 'woocommerce-warranty/woocommerce-warranty.php', self::$active_plugins ) || class_exists( 'WooCommerce_Warranty' );
		return false;
	}
	
	// WooCommerce Waitlist Support - 4.1.5
	static function wcfm_wc_waitlist_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-waitlist/woocommerce-waitlist.php', self::$active_plugins ) || array_key_exists( 'woocommerce-waitlist/woocommerce-waitlist.php', self::$active_plugins ) || class_exists( 'WooCommerce_Waitlist_Plugin' );
		return false;
	}
}