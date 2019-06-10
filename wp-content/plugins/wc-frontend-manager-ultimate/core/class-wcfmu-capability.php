<?php
/**
 * WCFM plugin core
 *
 * Plugin Capability Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   2.3.1
 */
 
class WCFMu_Capability {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM;
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', get_option( 'wcfm_capability_options', array() ) );
		
		// Products Filter
		add_filter( 'wcfm_non_allowd_product_type_options', array( &$this, 'wcfmcap_non_allowd_product_type_options' ), 500 );
		
		// Manage Vendor Product Permissions
		add_filter( 'wcfm_is_allow_resume_manager', array( &$this, 'wcfmcap_is_allow_resume_manager'), 500 );
		add_filter( 'wcfm_is_allow_auction', array( &$this, 'wcfmcap_is_allow_auction'), 500 );
		add_filter( 'wcfm_is_allow_rental', array( &$this, 'wcfmcap_is_allow_rental'), 500 );
		add_filter( 'wcfm_is_allow_appointments', array( &$this, 'wcfmcap_is_allow_appointments'), 500 );
		add_filter( 'wcfm_is_allow_accommodation', array( &$this, 'wcfmcap_is_allow_accommodation'), 500 );
		add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcfmcap_is_allow_fields_general' ), 500 );
		add_filter( 'wcfm_product_manage_fields_pricing', array( &$this, 'wcfmcap_is_allow_fields_pricing' ), 500 );
		add_filter( 'wcfm_product_manage_fields_content', array( &$this, 'wcfmcap_is_allow_fields_content' ), 500 );
		add_filter( 'wcfm_product_fields_stock', array( &$this, 'wcfmcap_is_allow_fields_stock' ), 500 );
		add_filter( 'wcfm_is_allow_sku', array( &$this, 'wcfmcap_is_allow_sku' ), 500 );
		add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfmcap_is_allow_fields_variation' ), 500 );
		add_filter( 'wcfmu_is_allow_downloadable', array( &$this, 'wcfmcap_is_allow_downloadable' ), 500 );
		add_filter( 'wcfm_is_allow_advanced', array( &$this, 'wcfmcap_is_allow_advanced' ), 500 );
		add_filter( 'wcfm_is_allow_wc_box_office', array( &$this, 'wcfmcap_is_allow_wc_box_office' ), 500 );
		
		// Sections
		add_filter( 'wcfm_is_allow_featured', array( &$this, 'wcfmcap_is_allow_featured' ), 500 );
		add_filter( 'wcfm_is_allow_gallery', array( &$this, 'wcfmcap_is_allow_gallery' ), 500 );
		add_filter( 'wcfm_is_allow_category', array( &$this, 'wcfmcap_is_allow_category' ), 500 );
		add_filter( 'wcfm_is_allow_add_category', array( &$this, 'wcfmcap_is_allow_add_category' ), 500 );
		add_filter( 'wcfm_is_allow_tags', array( &$this, 'wcfmcap_is_allow_tags' ), 500 );
		add_filter( 'wcfm_is_allow_products_addons', array( &$this, 'wcfmcap_is_allow_products_addons' ), 500 );
		add_filter( 'wcfm_is_allow_toolset_types', array( &$this, 'wcfmcap_is_allow_toolset_types' ), 500 );
		add_filter( 'wcfm_is_allow_acf_fields', array( &$this, 'wcfmcap_is_allow_acf_fields' ), 500 );
		add_filter( 'wcfm_is_allow_mappress', array( &$this, 'wcfmcap_is_allow_mappress' ), 500 );
		add_filter( 'wcfm_is_allow_geo_my_wp', array( &$this, 'wcfmcap_is_allow_mappress' ), 500 );
		
		// Limit
		add_filter( 'wcfm_gallerylimit', array( &$this, 'wcfmcap_gallerylimit' ), 500 );
		add_filter( 'wcfm_catlimit', array( &$this, 'wcfmcap_catlimit' ), 500 );
		add_filter( 'wcfm_allowed_taxonomies', array( &$this, 'wcfmcap_allowed_taxonomies' ), 500, 3 );
		add_filter( 'wcfm_is_allowed_attributes', array( &$this, 'wcfmcap_allowed_attributes' ), 500, 2 );
		add_filter( 'wcfm_allowed_custom_fields', array( &$this, 'wcfmcap_allowed_custom_fields' ), 500, 2 );
		
		// Insights
		add_filter( 'wcfm_is_allow_add_attribute', array( &$this, 'wcfmcap_is_allow_add_attribute' ), 500 );
		add_filter( 'wcfm_is_allow_add_attribute_term', array( &$this, 'wcfmcap_is_allow_add_attribute_term' ), 500 );
		add_filter( 'wcfm_is_allow_rich_editor', array( &$this, 'wcfmcap_is_allow_rich_editor' ), 500 );
		add_filter( 'wcfm_is_allow_duplicate_product', array( &$this, 'wcfmcap_is_allow_duplicate_product' ), 500 );
		add_filter( 'wcfm_is_allow_featured_product', array( &$this, 'wcfmcap_is_allow_featured_product' ), 500 );
		add_filter( 'wcfm_has_featured_product_limit', array( &$this, 'wcfmcap_has_featured_product_limit' ), 500 );
		add_filter( 'wcfm_is_allow_quick_edit_product', array( &$this, 'wcfmcap_is_allow_quick_edit_product' ), 500 );
		add_filter( 'wcfm_is_allow_bulk_edit', array( &$this, 'wcfmcap_is_allow_bulk_edit_product' ), 500 );
		add_filter( 'wcfm_is_allow_stock_manager', array( &$this, 'wcfmcap_is_allow_stock_manager' ), 500 );
		
		// Product Import / Exoprt - 2.4.2
		add_filter( 'wcfm_is_allow_products_export', array( &$this, 'wcfmcap_is_allow_products_export' ), 500 );
		add_filter( 'wcfm_is_allow_products_import', array( &$this, 'wcfmcap_is_allow_products_import' ), 500 );
		
		// Bookings Filter
		add_filter( 'wcfm_is_allow_manual_booking', array( &$this, 'wcfmcap_is_allow_manual_booking' ), 500 );
		add_filter( 'wcfm_is_allow_manage_resource', array( &$this, 'wcfmcap_is_allow_manage_resource' ), 500 );
		add_filter( 'wcfm_is_allow_booking_list', array( &$this, 'wcfmcap_is_allow_booking_list' ), 500 );
		add_filter( 'wcfm_is_allow_booking_calendar', array( &$this, 'wcfmcap_is_allow_booking_calendar' ), 500 );
		
		// Appointments Filter
		add_filter( 'wcfm_is_allow_manual_appointment', array( &$this, 'wcfmcap_is_allow_manual_appointment' ), 500 );
		add_filter( 'wcfm_is_allow_manage_appointment_staff', array( &$this, 'wcfmcap_is_allow_manage_appointment_staff' ), 500 );
		add_filter( 'wcfm_is_allow_appointment_list', array( &$this, 'wcfmcap_is_allow_appointment_list' ), 500 );
		add_filter( 'wcfm_is_allow_appointment_calendar', array( &$this, 'wcfmcap_is_allow_appointment_calendar' ), 500 );
		add_filter( 'wcfm_is_allow_shop_staff_availability', array( &$this, 'wcfmcap_is_allow_shop_staff_availability' ), 500 );
		
		// Subscriptons Filter
		add_filter( 'wcfm_is_allow_subscription_list', array( &$this, 'wcfmcap_is_allow_subscription_list' ), 500 );
		add_filter( 'wcfm_is_allow_subscription_details', array( &$this, 'wcfmcap_is_allow_subscription_details' ), 500 );
		add_filter( 'wcfm_is_allow_subscription_status_update', array( &$this, 'wcfmcap_is_allow_subscription_status_update' ), 500 );
		add_filter( 'wcfm_is_allow_subscription_schedule_update', array( &$this, 'wcfmcap_is_allow_subscription_schedule_update' ), 500 );
		
		
		// Order Notes
		add_filter( 'wcfm_allow_order_notes', array( &$this, 'wcfmcap_is_allow_order_notes' ), 500 );
		add_filter( 'wcfm_view_order_notes', array( &$this, 'wcfmcap_is_allow_view_order_notes' ), 500 );
		add_filter( 'wcfm_add_order_notes', array( &$this, 'wcfmcap_is_allow_add_order_notes' ), 500 );
		
		// Shipping Tracking - 3.1.1
		add_filter( 'wcfm_is_allow_shipping_tracking', array( &$this, 'wcfmcap_is_allow_shipping_tracking' ), 500 );
		
		// Support Ticket
		add_filter( 'wcfm_is_allow_support', array( &$this, 'wcfmcap_is_allow_support' ), 500 );
		add_filter( 'wcfm_is_allow_manage_support', array( &$this, 'wcfmcap_is_allow_manage_support' ), 500 );
		
		// Profile
		add_filter( 'wcfm_is_allow_address_profile', array( &$this, 'wcfmcap_is_allow_address_profile' ), 500 );
		add_filter( 'wcfm_is_allow_social_profile', array( &$this, 'wcfmcap_is_allow_social_profile' ), 500 );
		
		// Settings
		add_filter( 'wcfm_is_allow_brand_settings', array( &$this, 'wcfmcap_is_allow_brand_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vshipping_settings', array( &$this, 'wcfmcap_is_allow_vshipping_settings' ), 500 );
		add_filter( 'wcfm_is_allow_billing_settings', array( &$this, 'wcfmcap_is_allow_billing_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vseo_settings', array( &$this, 'wcfmcap_is_allow_vseo_settings' ), 500 );
		add_filter( 'wcfm_is_allow_policy_settings', array( &$this, 'wcfmcap_is_allow_policy_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vacation_settings', array( &$this, 'wcfmcap_is_allow_vacation_settings' ), 500 );
		
		// Header Panels
		add_filter( 'wcfm_is_allow_notice', array( &$this, 'wcfmcap_is_allow_notice' ), 500 );
		add_filter( 'wcfm_is_allow_notice_reply', array( &$this, 'wcfmcap_is_allow_notice_reply' ), 500 );
		add_filter( 'wcfm_is_allow_notifications', array( &$this, 'wcfmcap_is_allow_notifications' ), 500 );
		add_filter( 'wcfm_is_allow_direct_message', array( &$this, 'wcfmcap_is_allow_direct_message' ), 500 );
		add_filter( 'wcfm_is_allow_enquiry', array( &$this, 'wcfmcap_is_allow_enquiry' ), 500 );
		add_filter( 'wcfm_is_allow_knowledgebase', array( &$this, 'wcfmcap_is_allow_knowledgebase' ), 500 );
		add_filter( 'wcfm_is_allow_profile', array( &$this, 'wcfmcap_is_allow_profile' ), 500 );
		
		// Chat Module
		add_filter( 'wcfm_is_allow_chatbox', array( &$this, 'wcfmcap_is_allow_chatbox' ), 500 );
		
		// Custom Caps
		add_filter( 'wcfm_is_allow_commission_manage', array( &$this, 'wcfmcap_is_allow_commission_manage' ), 500 );
		add_filter( 'wcfm_allow_wp_admin_view', array( &$this, 'wcfmcap_is_allow_wp_admin_view' ), 500 );
	}
	
	// Non allowed prodyct type options
  function wcfmcap_non_allowd_product_type_options( $product_type_options ) {
  	$virtual = ( isset( $this->wcfm_capability_options['virtual'] ) ) ? $this->wcfm_capability_options['virtual'] : 'no';
  	$downloadable = ( isset( $this->wcfm_capability_options['downloadable'] ) ) ? $this->wcfm_capability_options['downloadable'] : 'no';
  	if( $virtual == 'yes' ) unset( $product_type_options['virtual'] );
  	if( $downloadable == 'yes' ) unset( $product_type_options['downloadable'] );
  	return $product_type_options;
  }
  
  // Resume Manager
  function wcfmcap_is_allow_resume_manager( $allow ) {
  	$resume_package = ( isset( $this->wcfm_capability_options['resume_package'] ) ) ? $this->wcfm_capability_options['resume_package'] : 'no';
  	if( $resume_package == 'yes' ) return false;
  	return $allow;
  }
  
  // Auction
  function wcfmcap_is_allow_auction( $allow ) {
  	$auction = ( isset( $this->wcfm_capability_options['auction'] ) ) ? $this->wcfm_capability_options['auction'] : 'no';
  	if( $auction == 'yes' ) return false;
  	return $allow;
  }
  
  // Rental
  function wcfmcap_is_allow_rental( $allow ) {
  	$rental = ( isset( $this->wcfm_capability_options['rental'] ) ) ? $this->wcfm_capability_options['rental'] : 'no';
  	if( $rental == 'yes' ) return false;
  	return $allow;
  }
  
  // Appointment
  function wcfmcap_is_allow_appointments( $allow ) {
  	$appointment = ( isset( $this->wcfm_capability_options['appointment'] ) ) ? $this->wcfm_capability_options['appointment'] : 'no';
  	$manual_appointment = ( isset( $this->wcfm_capability_options['manual_appointment'] ) ) ? $this->wcfm_capability_options['manual_appointment'] : 'no';
  	$manage_appointment_staff = ( isset( $this->wcfm_capability_options['manage_appointment_staff'] ) ) ? $this->wcfm_capability_options['manage_appointment_staff'] : 'no';
  	$appointment_list = ( isset( $this->wcfm_capability_options['appointment_list'] ) ) ? $this->wcfm_capability_options['appointment_list'] : 'no';
  	$appointment_calendar = ( isset( $this->wcfm_capability_options['appointment_calendar'] ) ) ? $this->wcfm_capability_options['appointment_calendar'] : 'no';
  	
  	if( ( $appointment == 'yes' ) && ( $manual_appointment == 'yes' ) && ( $manage_appointment_staff == 'yes' ) && ( $appointment_list == 'yes' ) && ( $appointment_calendar == 'yes' ) ) return false;
  	return $allow;
  }
  
  // Accommodation
  function wcfmcap_is_allow_accommodation( $allow ) {
  	$accommodation = ( isset( $this->wcfm_capability_options['accommodation'] ) ) ? $this->wcfm_capability_options['accommodation'] : 'no';
  	if( $accommodation == 'yes' ) return false;
  	return $allow;
  }
  
  // Downloadable & Virtual
  function wcfmcap_is_allow_fields_general( $general_fields ) {
  	$virtual = ( isset( $this->wcfm_capability_options['virtual'] ) ) ? $this->wcfm_capability_options['virtual'] : 'no';
  	$downloadable = ( isset( $this->wcfm_capability_options['downloadable'] ) ) ? $this->wcfm_capability_options['downloadable'] : 'no';
  	if( $virtual == 'yes' ) unset( $general_fields['is_virtual'] );
  	if( $downloadable == 'yes' ) unset( $general_fields['is_downloadable'] );
  		
  	return $general_fields;
  }
  
  // Pricing
  function wcfmcap_is_allow_fields_pricing( $pricing_fields ) {
  	$manage_price = ( isset( $this->wcfm_capability_options['manage_price'] ) ) ? $this->wcfm_capability_options['manage_price'] : 'no';
  	$manage_sales_price = ( isset( $this->wcfm_capability_options['manage_sales_price'] ) ) ? $this->wcfm_capability_options['manage_sales_price'] : 'no';
  	$manage_sales_scheduling = ( isset( $this->wcfm_capability_options['manage_sales_scheduling'] ) ) ? $this->wcfm_capability_options['manage_sales_scheduling'] : 'no';
  	
  	if( ( $manage_price == 'yes' ) && isset( $pricing_fields['regular_price'] ) ) {
  		$pricing_fields['regular_price']['class'] = 'wcfm_custom_hide';
  		$pricing_fields['regular_price']['label_class'] = 'wcfm_custom_hide';
  	}
  	if( ( $manage_sales_price == 'yes' ) && isset( $pricing_fields['sale_price'] ) ) {
  		$pricing_fields['sale_price']['class'] = 'wcfm_custom_hide';
  		$pricing_fields['sale_price']['label_class'] = 'wcfm_custom_hide';
  	}
  	if( ( $manage_sales_scheduling == 'yes' ) && isset( $pricing_fields['sale_price'] ) ) {
  		unset( $pricing_fields['sale_price']['desc'] );
  	}
  		
  	return $pricing_fields;
  }
  
  // Content
  function wcfmcap_is_allow_fields_content( $content_fields ) {
  	$manage_excerpt = ( isset( $this->wcfm_capability_options['manage_excerpt'] ) ) ? $this->wcfm_capability_options['manage_excerpt'] : 'no';
  	$manage_description = ( isset( $this->wcfm_capability_options['manage_description'] ) ) ? $this->wcfm_capability_options['manage_description'] : 'no';
  	
  	if( ( $manage_excerpt == 'yes' ) && isset( $content_fields['excerpt'] ) ) {
  		$content_fields['excerpt']['type'] = 'textarea'; 
  		$content_fields['excerpt']['class'] = 'wcfm_custom_hide';
  		$content_fields['excerpt']['label_class'] = 'wcfm_custom_hide';
  	}
  	if( ( $manage_description == 'yes' ) && isset( $content_fields['description'] ) ) {
  		$content_fields['description']['type'] = 'textarea';
  		$content_fields['description']['class'] = 'wcfm_custom_hide';
  		$content_fields['description']['label_class'] = 'wcfm_custom_hide';
  	}
  		
  	return $content_fields;
  }
  
  // Stock / SKU
  function wcfmcap_is_allow_fields_stock( $stock_fields ) {
  	$manage_sku = ( isset( $this->wcfm_capability_options['manage_sku'] ) ) ? $this->wcfm_capability_options['manage_sku'] : 'no';
  	
  	if( ( $manage_sku == 'yes' ) && isset( $stock_fields['sku'] ) ) {
  		$stock_fields['sku']['class'] = 'wcfm_custom_hide';
  		$stock_fields['sku']['label_class'] = 'wcfm_custom_hide';
  	}
  		
  	return $stock_fields;
  }
  
  // SKU
  function wcfmcap_is_allow_sku( $allow ) {
  	$manage_sku = ( isset( $this->wcfm_capability_options['manage_sku'] ) ) ? $this->wcfm_capability_options['manage_sku'] : 'no';
  	if( $manage_sku == 'yes' ) return false;
  	return $allow;
  }
  
  // Variation Fields
  function wcfmcap_is_allow_fields_variation( $variation_fields ) {
  	$virtual = ( isset( $this->wcfm_capability_options['virtual'] ) ) ? $this->wcfm_capability_options['virtual'] : 'no';
  	$downloadable = ( isset( $this->wcfm_capability_options['downloadable'] ) ) ? $this->wcfm_capability_options['downloadable'] : 'no';
  	
  	// Downloadable
  	if( $downloadable == 'yes' ) {
  		unset( $variation_fields['is_downloadable'] );
  		unset( $variation_fields['downloadable_file'] );
  		unset( $variation_fields['downloadable_file_name'] );
  		unset( $variation_fields['download_limit'] );
  		unset( $variation_fields['download_expiry'] );
  	}
  	
  	// Virtual
  	if( $virtual == 'yes' ) {
  		unset( $variation_fields['is_virtual'] );
  	}
  	
  	// Shipping
  	$shipping = ( isset( $this->wcfm_capability_options['shipping'] ) ) ? $this->wcfm_capability_options['shipping'] : 'no';
  	if( $shipping == 'yes' ) {
  		unset( $variation_fields['weight'] );
  		unset( $variation_fields['length'] );
  		unset( $variation_fields['width'] );
  		unset( $variation_fields['height'] );
  		unset( $variation_fields['shipping_class'] );
  	}
  	
  	// Inventory
  	$inventory = ( isset( $this->wcfm_capability_options['inventory'] ) ) ? $this->wcfm_capability_options['inventory'] : 'no';
  	if( $inventory == 'yes' ) {
  		unset( $variation_fields['manage_stock'] );
  		unset( $variation_fields['stock_qty'] );
  		unset( $variation_fields['backorders'] );
  		unset( $variation_fields['stock_status'] );
  	}
  	
  	// Tax
  	$taxes = ( isset( $this->wcfm_capability_options['taxes'] ) ) ? $this->wcfm_capability_options['taxes'] : 'no';
  	if( $taxes == 'yes' ) unset( $variation_fields['tax_class'] );
  	
  	// SKU
  	$manage_sku = ( isset( $this->wcfm_capability_options['manage_sku'] ) ) ? $this->wcfm_capability_options['manage_sku'] : 'no';
  	if( $manage_sku == 'yes' ) unset( $variation_fields['sku'] );
  	
  	// Price
  	$manage_price = ( isset( $this->wcfm_capability_options['manage_price'] ) ) ? $this->wcfm_capability_options['manage_price'] : 'no';
  	if( $manage_price == 'yes' ) unset( $variation_fields['regular_price'] );
  	
  	// Sales Price
  	$manage_sales_price = ( isset( $this->wcfm_capability_options['manage_sales_price'] ) ) ? $this->wcfm_capability_options['manage_sales_price'] : 'no';
  	if( $manage_sales_price == 'yes' ) unset( $variation_fields['sale_price'] );
  	
  	// Sales Price Schedule
  	$manage_sales_scheduling = ( isset( $this->wcfm_capability_options['manage_sales_scheduling'] ) ) ? $this->wcfm_capability_options['manage_sales_scheduling'] : 'no';
  	if( ( $manage_sales_scheduling == 'yes' ) && isset( $variation_fileds['sale_price'] ) ) unset( $variation_fields['sale_price']['desc'] );
  	
  	// Description
  	$manage_description = ( isset( $this->wcfm_capability_options['manage_description'] ) ) ? $this->wcfm_capability_options['manage_description'] : 'no';
  	if( $manage_description == 'yes' ) unset( $variation_fields['description'] );
  	
  	return $variation_fields;
  }
  
  // Downloadable
  function wcfmcap_is_allow_downloadable( $allow ) {
  	$downloadable = ( isset( $this->wcfm_capability_options['downloadable'] ) ) ? $this->wcfm_capability_options['downloadable'] : 'no';
  	if( $downloadable == 'yes' ) return false;
  	return $allow;
  }
  
  // Advanced
  function wcfmcap_is_allow_advanced( $allow ) {
  	$advanced = ( isset( $this->wcfm_capability_options['advanced'] ) ) ? $this->wcfm_capability_options['advanced'] : 'no';
  	if( $advanced == 'yes' ) return false;
  	return $allow;
  }
  
  // WC Box Office
  function wcfmcap_is_allow_wc_box_office( $allow ) {
  	$wc_box_office_ticket = ( isset( $this->wcfm_capability_options['wc_box_office_ticket'] ) ) ? $this->wcfm_capability_options['wc_box_office_ticket'] : 'no';
  	if( $wc_box_office_ticket == 'yes' ) return false;
  	return $allow;
  }
  
  // Add Attribute - 3.1.5
  function wcfmcap_is_allow_add_attribute( $add_attribute ) {
  	$add_attribute = ( isset( $this->wcfm_capability_options['add_attribute'] ) ) ? $this->wcfm_capability_options['add_attribute'] : 'no';
  	if( $add_attribute == 'yes' ) return '';
  	return $add_attribute;
  }
  
  // Add Attribute Term - 3.3.0
  function wcfmcap_is_allow_add_attribute_term( $add_attribute_term ) {
  	$add_attribute_term = ( isset( $this->wcfm_capability_options['add_attribute_term'] ) ) ? $this->wcfm_capability_options['add_attribute_term'] : 'no';
  	if( $add_attribute_term == 'yes' ) return '';
  	return $add_attribute_term;
  }
  
  // Rich Editor - 2.5.1
  function wcfmcap_is_allow_rich_editor( $rich_editor ) {
  	$rich_editor = ( isset( $this->wcfm_capability_options['rich_editor'] ) ) ? $this->wcfm_capability_options['rich_editor'] : 'rich_editor';
  	if( $rich_editor == 'yes' ) return '';
  	return $rich_editor;
  }
  
  // Duplicate Product - 2.5.2
  function wcfmcap_is_allow_duplicate_product( $allow ) {
  	$duplicate_product = ( isset( $this->wcfm_capability_options['duplicate_product'] ) ) ? $this->wcfm_capability_options['duplicate_product'] : 'no';
  	if( $duplicate_product == 'yes' ) return false;
  	return $allow;
  }
  
  // Featured Product - 3.0.1
  function wcfmcap_is_allow_featured_product( $allow ) {
  	$featured_product = ( isset( $this->wcfm_capability_options['featured_product'] ) ) ? $this->wcfm_capability_options['featured_product'] : 'no';
  	if( $featured_product == 'yes' ) return false;
  	return $allow;
  }
  
  // Featured Product Limit - 4.0.8
  function wcfmcap_has_featured_product_limit( $allow ) {
  	$featured_product_limit = ( isset( $this->wcfm_capability_options['featured_product_limit'] ) ) ? $this->wcfm_capability_options['featured_product_limit'] : '';
  	if( $featured_product_limit ) $featured_product_limit = absint($featured_product_limit);
  	if( $featured_product_limit && ( $featured_product_limit >= 0 ) ) {
			$featured_product_count = wcfm_get_user_posts_count( 0, 'product', 'publish', array( 'tax_query' => array(
																																																								array(
																																																									'taxonomy' => 'product_visibility',
																																																									'field' => 'slug',
																																																									'terms' => 'featured'
																																																								)
																																																							) ) );
			if( $featured_product_limit <= $featured_product_count ) return false;
		}
  	return $allow;
  }
  
  // Quick Edit - 3.2.2
  function wcfmcap_is_allow_quick_edit_product( $allow ) {
  	$product_quick_edit = ( isset( $this->wcfm_capability_options['product_quick_edit'] ) ) ? $this->wcfm_capability_options['product_quick_edit'] : 'no';
  	if( $product_quick_edit == 'yes' ) return false;
  	return $allow;
  }
  
  // Bulk Edit - 3.2.4
  function wcfmcap_is_allow_bulk_edit_product( $allow ) {
  	$product_bulk_edit = ( isset( $this->wcfm_capability_options['product_bulk_edit'] ) ) ? $this->wcfm_capability_options['product_bulk_edit'] : 'no';
  	if( $product_bulk_edit == 'yes' ) return false;
  	return $allow;
  }
  
  // Stock manager
  function wcfmcap_is_allow_stock_manager( $allow ) {
  	$stock_manager = ( isset( $this->wcfm_capability_options['stock_manager'] ) ) ? $this->wcfm_capability_options['stock_manager'] : 'no';
  	if( $stock_manager == 'yes' ) return false;
  	return $allow;
  }
  
  // Featured Image
  function wcfmcap_is_allow_featured( $allow ) {
  	$featured_img = ( isset( $this->wcfm_capability_options['featured_img'] ) ) ? $this->wcfm_capability_options['featured_img'] : 'no';
  	if( $featured_img == 'yes' ) return false;
  	return $allow;
  }
  
  // Gallery Image
  function wcfmcap_is_allow_gallery( $allow ) {
  	$gallery_img = ( isset( $this->wcfm_capability_options['gallery_img'] ) ) ? $this->wcfm_capability_options['gallery_img'] : 'no';
  	if( $gallery_img == 'yes' ) return false;
  	return $allow;
  }
  
  // Categories
  function wcfmcap_is_allow_category( $allow ) {
  	$category = ( isset( $this->wcfm_capability_options['category'] ) ) ? $this->wcfm_capability_options['category'] : 'no';
  	if( $category == 'yes' ) return false;
  	return $allow;
  }
  
  // Add Category
  function wcfmcap_is_allow_add_category( $allow ) {
  	$add_category = ( isset( $this->wcfm_capability_options['add_category'] ) ) ? $this->wcfm_capability_options['add_category'] : 'no';
  	if( $add_category == 'yes' ) return false;
  	return $allow;
  }
  
  // Tags
  function wcfmcap_is_allow_tags( $allow ) {
  	$tags = ( isset( $this->wcfm_capability_options['tags'] ) ) ? $this->wcfm_capability_options['tags'] : 'no';
  	if( $tags == 'yes' ) return false;
  	return $allow;
  }
  
  // Gallery limit
  function wcfmcap_gallerylimit( $gallerylimit ) {
  	$gallerylimit = ( !empty( $this->wcfm_capability_options['gallerylimit'] ) ) ? $this->wcfm_capability_options['gallerylimit'] : '-1';
  	return $gallerylimit;
  }
  
  // Category Limits
  function wcfmcap_catlimit( $catlimit ) {
  	$catlimit = ( !empty( $this->wcfm_capability_options['catlimit'] ) ) ? $this->wcfm_capability_options['catlimit'] : '-1';
  	return $catlimit;
  }
  
  // Allowed Taxonomies
  function wcfmcap_allowed_taxonomies( $allow, $taxonomy, $term_id ) {
		global $WCFM, $WCFMu;
		
		if( $taxonomy == 'product_cat' ) $taxonomy = 'categories';
		elseif( $taxonomy == 'category' ) $taxonomy = 'article_category';
		
		$allowed_taxonomies    = ( !empty( $this->wcfm_capability_options['allowed_' . $taxonomy] ) ) ? $this->wcfm_capability_options['allowed_' . $taxonomy] : array();
		
		if( is_array( $allowed_taxonomies ) && !empty( $allowed_taxonomies ) ) {
			if( !in_array( $term_id, $allowed_taxonomies ) ) {
				$allow = false;
			}
		}
		
		return $allow;
	}
	
	// Allowed Attributes
	function wcfmcap_allowed_attributes( $allow, $attribute ) {
		$allowed_attributes    = ( !empty( $this->wcfm_capability_options['allowed_attributes'] ) ) ? $this->wcfm_capability_options['allowed_attributes'] : array();
		if( is_array( $allowed_attributes ) && !empty( $allowed_attributes ) ) {
			if( !in_array( $attribute, $allowed_attributes ) ) $allow = false;
		}
		return $allow;
	}
	
	// Allowed Custom Fields
	function wcfmcap_allowed_custom_fields( $allow, $custom_block ) {
		$allowed_custom_fields    = ( !empty( $this->wcfm_capability_options['allowed_custom_fields'] ) ) ? $this->wcfm_capability_options['allowed_custom_fields'] : array();
		if( is_array( $allowed_custom_fields ) && !empty( $allowed_custom_fields ) ) {
			if( !in_array( $custom_block, $allowed_custom_fields ) ) $allow = false;
		}
		return $allow;
	}
  
  // Product Add-ons
  function wcfmcap_is_allow_products_addons( $allow ) {
  	$addons = ( isset( $this->wcfm_capability_options['addons'] ) ) ? $this->wcfm_capability_options['addons'] : 'no';
  	if( $addons == 'yes' ) return false;
  	return $allow;
  }
  
  // Toolset Types
  function wcfmcap_is_allow_toolset_types( $allow ) {
  	$toolset_types = ( isset( $this->wcfm_capability_options['toolset_types'] ) ) ? $this->wcfm_capability_options['toolset_types'] : 'no';
  	if( $toolset_types == 'yes' ) return false;
  	return $allow;
  }
  
  // ACF Fields
  function wcfmcap_is_allow_acf_fields( $allow ) {
  	$acf_fields = ( isset( $this->wcfm_capability_options['acf_fields'] ) ) ? $this->wcfm_capability_options['acf_fields'] : 'no';
  	if( $acf_fields == 'yes' ) return false;
  	return $allow;
  }
  
  // MapPress
  function wcfmcap_is_allow_mappress( $allow ) {
  	$mappress = ( isset( $this->wcfm_capability_options['mappress'] ) ) ? $this->wcfm_capability_options['mappress'] : 'no';
  	if( $mappress == 'yes' ) return false;
  	return $allow;
  }
  
  // Product Export
  function wcfmcap_is_allow_products_export( $allow ) {
  	$product_export = ( isset( $this->wcfm_capability_options['product_export'] ) ) ? $this->wcfm_capability_options['product_export'] : 'no';
  	if( $product_export == 'yes' ) return false;
  	return $allow;
  }
  
  // Product Import
  function wcfmcap_is_allow_products_import( $allow ) {
  	$product_import = ( isset( $this->wcfm_capability_options['product_import'] ) ) ? $this->wcfm_capability_options['product_import'] : 'no';
  	if( $product_import == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Manage Booking
  function wcfmcap_is_allow_manual_booking( $allow ) {
  	$manual_booking = ( isset( $this->wcfm_capability_options['manual_booking'] ) ) ? $this->wcfm_capability_options['manual_booking'] : 'no';
  	if( $manual_booking == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Booking Resources
  function wcfmcap_is_allow_manage_resource( $allow ) {
  	$manage_resource = ( isset( $this->wcfm_capability_options['manage_resource'] ) ) ? $this->wcfm_capability_options['manage_resource'] : 'no';
  	if( $manage_resource == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Booking List
  function wcfmcap_is_allow_booking_list( $allow ) {
  	$booking_list = ( isset( $this->wcfm_capability_options['booking_list'] ) ) ? $this->wcfm_capability_options['booking_list'] : 'no';
  	if( $booking_list == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Booking Calendar
  function wcfmcap_is_allow_booking_calendar( $allow ) {
  	$booking_calendar = ( isset( $this->wcfm_capability_options['booking_calendar'] ) ) ? $this->wcfm_capability_options['booking_calendar'] : 'no';
  	if( $booking_calendar == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Manual Appointment
  function wcfmcap_is_allow_manual_appointment( $allow ) {
  	$manual_appointment = ( isset( $this->wcfm_capability_options['manual_appointment'] ) ) ? $this->wcfm_capability_options['manual_appointment'] : 'no';
  	if( $manual_appointment == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Appointment Staff
  function wcfmcap_is_allow_manage_appointment_staff( $allow ) {
  	$manage_appointment_staff = ( isset( $this->wcfm_capability_options['manage_appointment_staff'] ) ) ? $this->wcfm_capability_options['manage_appointment_staff'] : 'no';
  	if( $manage_appointment_staff == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Appointment List
  function wcfmcap_is_allow_appointment_list( $allow ) {
  	$appointment_list = ( isset( $this->wcfm_capability_options['appointment_list'] ) ) ? $this->wcfm_capability_options['appointment_list'] : 'no';
  	if( $appointment_list == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Appointment Calendar
  function wcfmcap_is_allow_appointment_calendar( $allow ) {
  	$appointment_calendar = ( isset( $this->wcfm_capability_options['appointment_calendar'] ) ) ? $this->wcfm_capability_options['appointment_calendar'] : 'no';
  	if( $appointment_calendar == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Staff Manage Own Availability
  function wcfmcap_is_allow_shop_staff_availability( $allow ) {
  	$manage_appointment_availability = ( isset( $this->wcfm_capability_options['manage_appointment_availability'] ) ) ? $this->wcfm_capability_options['manage_appointment_availability'] : 'no';
  	if( $manage_appointment_availability == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap View Subscriptions List
  function wcfmcap_is_allow_subscription_list( $allow ) {
  	$subscription_list = ( isset( $this->wcfm_capability_options['subscription_list'] ) ) ? $this->wcfm_capability_options['subscription_list'] : 'no';
  	if( $subscription_list == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap View Subscription Details
  function wcfmcap_is_allow_subscription_details( $allow ) {
  	$subscription_details = ( isset( $this->wcfm_capability_options['subscription_details'] ) ) ? $this->wcfm_capability_options['subscription_details'] : 'no';
  	if( $subscription_details == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Update Subscription Sttaus
  function wcfmcap_is_allow_subscription_status_update( $allow ) {
  	$subscription_status_update = ( isset( $this->wcfm_capability_options['subscription_status_update'] ) ) ? $this->wcfm_capability_options['subscription_status_update'] : 'no';
  	if( $subscription_status_update == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Update Subscription Schedule
  function wcfmcap_is_allow_subscription_schedule_update( $allow ) {
  	$subscription_schedule_update = ( isset( $this->wcfm_capability_options['subscription_schedule_update'] ) ) ? $this->wcfm_capability_options['subscription_schedule_update'] : 'no';
  	if( $subscription_schedule_update == 'yes' ) return false;
  	return $allow;
  }
  
  // Allow Order Notes
  function wcfmcap_is_allow_order_notes( $allow ) {
  	$view_comments = ( isset( $this->wcfm_capability_options['view_comments'] ) ) ? $this->wcfm_capability_options['view_comments'] : 'no';
		$submit_comments = ( isset( $this->wcfm_capability_options['submit_comments'] ) ) ? $this->wcfm_capability_options['submit_comments'] : 'no';
		if ( ( $view_comments == 'yes' ) && ( $submit_comments == 'yes' ) ) return false;
		return $allow;
  }
  
  // View Order Notes
  function wcfmcap_is_allow_view_order_notes( $allow ) {
  	$view_comments = ( isset( $this->wcfm_capability_options['view_comments'] ) ) ? $this->wcfm_capability_options['view_comments'] : 'no';
		if ( $view_comments == 'yes' ) return false;
		return $allow;
  }
  
  // Add Order Notes
  function wcfmcap_is_allow_add_order_notes( $allow ) {
		$submit_comments = ( isset( $this->wcfm_capability_options['submit_comments'] ) ) ? $this->wcfm_capability_options['submit_comments'] : 'no';
		if ( $submit_comments == 'yes' ) return false;
		return $allow;
  }
  
  // Shipping Tracking
  function wcfmcap_is_allow_shipping_tracking( $allow ) {
		$shipping_tracking = ( isset( $this->wcfm_capability_options['shipping_tracking'] ) ) ? $this->wcfm_capability_options['shipping_tracking'] : 'no';
		if ( $shipping_tracking == 'yes' ) return false;
		return $allow;
  }
  
  // Allow View / Manage Support Tickets
  function wcfmcap_is_allow_support( $allow ) {
  	$support_ticket = ( isset( $this->wcfm_capability_options['support_ticket'] ) ) ? $this->wcfm_capability_options['support_ticket'] : 'no';
  	if( $support_ticket == 'yes' ) return false;
  	return $allow;
  }
  
  // Allow Support Ticket Reply
  function wcfmcap_is_allow_manage_support( $allow ) {
  	$support_ticket_manage = ( isset( $this->wcfm_capability_options['support_ticket_manage'] ) ) ? $this->wcfm_capability_options['support_ticket_manage'] : 'no';
  	if( $support_ticket_manage == 'yes' ) return false;
  	return $allow;
  }
  
  // Profile Address
  function wcfmcap_is_allow_address_profile( $allow ) {
		$address = ( isset( $this->wcfm_capability_options['address'] ) ) ? $this->wcfm_capability_options['address'] : 'no';
		if ( $address == 'yes' ) return false;
		return $allow;
  }
  
  // Profile Social
  function wcfmcap_is_allow_social_profile( $allow ) {
		$social = ( isset( $this->wcfm_capability_options['social'] ) ) ? $this->wcfm_capability_options['social'] : 'no';
		if ( $social == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Brand
  function wcfmcap_is_allow_brand_settings( $allow ) {
		$brand = ( isset( $this->wcfm_capability_options['brand'] ) ) ? $this->wcfm_capability_options['brand'] : 'no';
		if ( $brand == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Shipping
  function wcfmcap_is_allow_vshipping_settings( $allow ) {
		$vshipping = ( isset( $this->wcfm_capability_options['vshipping'] ) ) ? $this->wcfm_capability_options['vshipping'] : 'no';
		if ( $vshipping == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Billing
  function wcfmcap_is_allow_billing_settings( $allow ) {
		$billing = ( isset( $this->wcfm_capability_options['billing'] ) ) ? $this->wcfm_capability_options['billing'] : 'no';
		if ( $billing == 'yes' ) return false;
		return $allow;
  }
  
  // Setting SEO
  function wcfmcap_is_allow_vseo_settings( $allow ) {
		$store_seo = ( isset( $this->wcfm_capability_options['store_seo'] ) ) ? $this->wcfm_capability_options['store_seo'] : 'no';
		if ( $store_seo == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Policy
  function wcfmcap_is_allow_policy_settings( $allow ) {
		$policy = ( isset( $this->wcfm_capability_options['policy'] ) ) ? $this->wcfm_capability_options['policy'] : 'no';
		if ( $policy == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Vacation
  function wcfmcap_is_allow_vacation_settings( $allow ) {
		$vacation = ( isset( $this->wcfm_capability_options['vacation'] ) ) ? $this->wcfm_capability_options['vacation'] : 'no';
		if ( $vacation == 'yes' ) return false;
		return $allow;
  }
  
  // Notice
  function wcfmcap_is_allow_notice( $allow ) {
		$notice = ( isset( $this->wcfm_capability_options['notice'] ) ) ? $this->wcfm_capability_options['notice'] : 'no';
		if ( $notice == 'yes' ) return false;
		return $allow;
  }
  
  // Notice Reply
  function wcfmcap_is_allow_notice_reply( $allow ) {
		$notice_reply = ( isset( $this->wcfm_capability_options['notice_reply'] ) ) ? $this->wcfm_capability_options['notice_reply'] : 'no';
		if ( $notice_reply == 'yes' ) return false;
		return $allow;
  }
  
  // Notification
  function wcfmcap_is_allow_notifications( $allow ) {
		$notification = ( isset( $this->wcfm_capability_options['notification'] ) ) ? $this->wcfm_capability_options['notification'] : 'no';
		if ( $notification == 'yes' ) return false;
		return $allow;
  }
  
  // Direct Message
  function wcfmcap_is_allow_direct_message( $allow ) {
		$direct_message = ( isset( $this->wcfm_capability_options['direct_message'] ) ) ? $this->wcfm_capability_options['direct_message'] : 'no';
		if ( $direct_message == 'yes' ) return false;
		return $allow;
  }
  
  // Enquiry
  function wcfmcap_is_allow_enquiry( $allow ) {
		$enquiry = ( isset( $this->wcfm_capability_options['enquiry'] ) ) ? $this->wcfm_capability_options['enquiry'] : 'no';
		if ( $enquiry == 'yes' ) return false;
		return $allow;
  }
  
  // Knowledgebase
  function wcfmcap_is_allow_knowledgebase( $allow ) {
		$knowledgebase = ( isset( $this->wcfm_capability_options['knowledgebase'] ) ) ? $this->wcfm_capability_options['knowledgebase'] : 'no';
		if ( $knowledgebase == 'yes' ) return false;
		return $allow;
  }
  
  // Profie
  function wcfmcap_is_allow_profile( $allow ) {
		$profile = ( isset( $this->wcfm_capability_options['profile'] ) ) ? $this->wcfm_capability_options['profile'] : 'no';
		if ( $profile == 'yes' ) return false;
		return $allow;
  }
  
  // Chat Box
  function wcfmcap_is_allow_chatbox( $allow ) {
		$chatbox = ( isset( $this->wcfm_capability_options['chatbox'] ) ) ? $this->wcfm_capability_options['chatbox'] : 'no';
		if ( $chatbox == 'yes' ) return false;
		return $allow;
  }
  
  // Commission Manage
  function wcfmcap_is_allow_commission_manage( $allow ) {
  	$manage_commission = ( isset( $this->wcfm_capability_options['manage_commission'] ) ) ? $this->wcfm_capability_options['manage_commission'] : 'no';
  	if( $manage_commission == 'yes' ) return false;
  	return $allow;
  }
  
  // WP Admin View
  function wcfmcap_is_allow_wp_admin_view( $allow ) {
  	$wp_admin_view = ( isset( $this->wcfm_capability_options['wp_admin_view'] ) ) ? $this->wcfm_capability_options['wp_admin_view'] : 'no';
  	if( $wp_admin_view == 'yes' ) return false;
  	return $allow;
  }
}