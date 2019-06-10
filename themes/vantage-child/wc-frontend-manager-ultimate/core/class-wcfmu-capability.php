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
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', (array) get_option( 'wcfm_capability_options' ) );
		
		// Products Filter
		add_filter( 'wcfm_non_allowd_product_type_options', array( &$this, 'wcfmcap_non_allowd_product_type_options' ), 500 );
		
		// Manage Vendor Product Permissions
		add_filter( 'wcfm_is_allow_resume_manager', array( &$this, 'wcfmcap_is_allow_resume_manager'), 500 );
		add_filter( 'wcfm_is_allow_auction', array( &$this, 'wcfmcap_is_allow_auction'), 500 );
		add_filter( 'wcfm_is_allow_rental', array( &$this, 'wcfmcap_is_allow_rental'), 500 );
		add_filter( 'wcfm_is_allow_appointments', array( &$this, 'wcfmcap_is_allow_appointments'), 500 );
		add_filter( 'wcfm_is_allow_accommodation', array( &$this, 'wcfmcap_is_allow_accommodation'), 500 );
		add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcfmcap_is_allow_fields_general' ), 500 );
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
		
		// Limit
		add_filter( 'wcfm_gallerylimit', array( &$this, 'wcfmcap_gallerylimit' ), 500 );
		add_filter( 'wcfm_catlimit', array( &$this, 'wcfmcap_catlimit' ), 500 );
		add_filter( 'wcfm_allowed_taxonomies', array( &$this, 'wcfmcap_allowed_taxonomies' ), 500, 3 );
		
		// Insights
		add_filter( 'wcfm_is_allow_add_attribute', array( &$this, 'wcfmcap_is_allow_add_attribute' ), 500 );
		add_filter( 'wcfm_is_allow_add_attribute_term', array( &$this, 'wcfmcap_is_allow_add_attribute_term' ), 500 );
		add_filter( 'wcfm_is_allow_rich_editor', array( &$this, 'wcfmcap_is_allow_rich_editor' ), 500 );
		add_filter( 'wcfm_is_allow_duplicate_product', array( &$this, 'wcfmcap_is_allow_duplicate_product' ), 500 );
		add_filter( 'wcfm_is_allow_featured_product', array( &$this, 'wcfmcap_is_allow_featured_product' ), 500 );
		add_filter( 'wcfm_is_allow_quick_edit_product', array( &$this, 'wcfmcap_is_allow_quick_edit_product' ), 500 );
		add_filter( 'wcfm_is_allow_bulk_edit', array( &$this, 'wcfmcap_is_allow_bulk_edit_product' ), 500 );
		add_filter( 'wcfm_is_allow_stock_manager', array( &$this, 'wcfmcap_is_allow_stock_manager' ), 500 );
		
		// Product Import / Exoprt - 2.4.2
		add_filter( 'wcfm_is_allow_products_export', array( &$this, 'wcfmcap_is_allow_products_export' ), 500 );
		add_filter( 'wcfm_is_allow_products_import', array( &$this, 'wcfmcap_is_allow_products_import' ), 500 );
		
		
		// Order Notes
		add_filter( 'wcfm_allow_order_notes', array( &$this, 'wcfmcap_is_allow_order_notes' ), 500 );
		add_filter( 'wcfm_view_order_notes', array( &$this, 'wcfmcap_is_allow_view_order_notes' ), 500 );
		add_filter( 'wcfm_add_order_notes', array( &$this, 'wcfmcap_is_allow_add_order_notes' ), 500 );
		
		// Shipping Tracking - 3.1.1
		add_filter( 'wcfm_is_allow_shipping_tracking', array( &$this, 'wcfmcap_is_allow_shipping_tracking' ), 500 );
		
		// Profile
		add_filter( 'wcfm_is_allow_address_profile', array( &$this, 'wcfmcap_is_allow_address_profile' ), 500 );
		add_filter( 'wcfm_is_allow_social_profile', array( &$this, 'wcfmcap_is_allow_social_profile' ), 500 );
		
		// Settings
		add_filter( 'wcfm_is_allow_vacation_settings', array( &$this, 'wcfmcap_is_allow_vacation_settings' ), 500 );
		add_filter( 'wcfm_is_allow_brand_settings', array( &$this, 'wcfmcap_is_allow_brand_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vshipping_settings', array( &$this, 'wcfmcap_is_allow_vshipping_settings' ), 500 );
		add_filter( 'wcfm_is_allow_billing_settings', array( &$this, 'wcfmcap_is_allow_billing_settings' ), 500 );
		
		// Header Panels
		add_filter( 'wcfm_is_allow_notice', array( &$this, 'wcfmcap_is_allow_notice' ), 500 );
		add_filter( 'wcfm_is_allow_notice_reply', array( &$this, 'wcfmcap_is_allow_notice_reply' ), 500 );
		add_filter( 'wcfm_is_allow_notifications', array( &$this, 'wcfmcap_is_allow_notifications' ), 500 );
		add_filter( 'wcfm_is_allow_direct_message', array( &$this, 'wcfmcap_is_allow_direct_message' ), 500 );
		add_filter( 'wcfm_is_allow_enquiry', array( &$this, 'wcfmcap_is_allow_enquiry' ), 500 );
		add_filter( 'wcfm_is_allow_knowledgebase', array( &$this, 'wcfmcap_is_allow_knowledgebase' ), 500 );
		add_filter( 'wcfm_is_allow_profile', array( &$this, 'wcfmcap_is_allow_profile' ), 500 );
		
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
  	//if( !empty( $product_misc['sku'] ) ) unset( $variation_fields['sku'] );
  	
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
  
  // Duplicate Product - 3.0.1
  function wcfmcap_is_allow_featured_product( $allow ) {
  	$featured_product = ( isset( $this->wcfm_capability_options['featured_product'] ) ) ? $this->wcfm_capability_options['featured_product'] : 'no';
  	if( $featured_product == 'yes' ) return false;
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
		
		$allowed_taxonomies    = ( !empty( $this->wcfm_capability_options['allowed_' . $taxonomy] ) ) ? $this->wcfm_capability_options['allowed_' . $taxonomy] : array();
		
		if( is_array( $allowed_taxonomies ) && !empty( $allowed_taxonomies ) ) {
			if( !in_array( $term_id, $allowed_taxonomies ) ) {
				$allow = false;
			}
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
  
  // Settings Vacation
  function wcfmcap_is_allow_vacation_settings( $allow ) {
		$vacation = ( isset( $this->wcfm_capability_options['vacation'] ) ) ? $this->wcfm_capability_options['vacation'] : 'no';
		if ( $vacation == 'yes' ) return false;
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