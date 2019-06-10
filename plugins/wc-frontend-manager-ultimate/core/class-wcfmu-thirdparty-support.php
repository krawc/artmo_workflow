<?php
/**
 * WCFMu plugin core
 *
 * Third Party Plugin Support Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   2.2.2
 */
 
class WCFMu_ThirdParty_Support {

	public function __construct() {
		global $WCFM;
		
		// WCFM Thirdparty Query Var Filter
		add_filter( 'wcfm_query_vars', array( &$this, 'wcfmu_thirdparty_query_vars' ), 80 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfmu_thirdparty_endpoint_title' ), 80, 2 );
		add_action( 'init', array( &$this, 'wcfmu_thirdparty_auction_init' ), 70 );
		add_action( 'init', array( &$this, 'wcfmu_thirdparty_rental_init' ), 80 );
		
		// WCFMu Thirdparty Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfmu_thirdparty_endpoints_slug' ) );
		
		// WCFMu Thirdparty Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfmu_thirdparty_menus' ), 80 );
		
		// WCFMu Thirdparty Product Type
		add_filter( 'wcfm_product_types', array( &$this, 'wcfmu_thirdparty_product_types' ), 60 );
		
		// Third Party Product Type Capability
		add_filter( 'wcfm_capability_settings_fields_product_types', array( &$this, 'wcfmcap_product_types' ), 60, 3 );
		
		// WCFMu Thirdparty Load WCFMu Scripts
		add_action( 'wcfm_load_scripts', array( &$this, 'wcfmu_thirdparty_load_scripts' ), 80 );
		add_action( 'after_wcfm_load_scripts', array( &$this, 'wcfmu_thirdparty_load_scripts' ), 80 );
		
		// WCFMu Thirdparty Load WCFMu Styles
		add_action( 'wcfm_load_styles', array( &$this, 'wcfmu_thirdparty_load_styles' ), 80 );
		add_action( 'after_wcfm_load_styles', array( &$this, 'wcfmu_thirdparty_load_styles' ), 80 );
		
		// WCFMu Thirdparty Load WCFMu views
		//add_action( 'wcfm_load_views', array( &$this, 'wcfmu_thirdparty_load_views' ), 80 );
		add_action( 'before_wcfm_load_views', array( &$this, 'wcfmu_thirdparty_load_views' ), 80 );
		
		// WCFMu Thirdparty Ajax Controller
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmu_thirdparty_ajax_controller' ) );
		
		// Product Manage Third Party Variation View
    add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfmu_thirdparty_product_manage_fields_variations' ), 100, 4 );
		
    // Product Manage Third Party Variaton Date Edit
		add_filter( 'wcfm_variation_edit_data', array( &$this, 'wcfmu_thirdparty_product_data_variations' ), 100, 3 );
    
    // WP Job Manager - Resume Manager Support - 2.3.4
    if( $wcfm_allow_resume_manager = apply_filters( 'wcfm_is_allow_resume_manager', true ) ) {
			if ( WCFMu_Dependencies::wcfm_resume_manager_active_check() ) {
				// Resume Manager Product options
				add_filter( 'wcfm_product_manage_fields_pricing', array( &$this, 'wcfm_wpjrm_product_manage_fields' ), 60, 2 );
			}
		}
    
    // YITH Auction Support - 2.3.8
    if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_yith_auction_active_check() ) {
				// YITH Auction Product options
				add_filter( 'after_wcfm_products_manage_general', array( &$this, 'wcfm_yithauction_product_manage_fields' ), 70, 2 );
			} else {
				if( get_option( 'wcfm_updated_end_point_auction' ) ) {
					delete_option( 'wcfm_updated_end_point_auction' );
				}
			}
		}
		
		// WooCommerce Simple Auction Support - 2.3.10
    if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
				// WooCommerce Simple Auction Products Query
				//update_option( 'simple_auctions_dont_mix_shop', 'no' );
				
				// WooCommerce Simple Auction Product options
				add_filter( 'after_wcfm_products_manage_general', array( &$this, 'wcfm_wcsauction_product_manage_fields' ), 70, 2 );
			} else {
				if( get_option( 'wcfm_updated_end_point_auction' ) ) {
					delete_option( 'wcfm_updated_end_point_auction' );
				}
			}
		}
		
		// WC Rental & Booking Pro Support - 2.3.10
    if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
				// WC Rental Product options
				add_filter( 'after_wcfm_products_manage_general', array( &$this, 'wcfm_wcrental_pro_product_manage_fields' ), 80, 2 );
				
				// WC Rental Product Inventory Management - 2.4.3
				add_filter( 'wcfm_product_fields_stock', array( &$this, 'wcfm_wcrental_product_inventory_manage' ), 80, 3 );
				
				// Order Item Meta Filter
				apply_filters( 'woocommerce_hidden_order_itemmeta', array( &$this, 'wcfm_wcrental_pro_hidden_order_itemmeta' ), 80 );
				
				// Quote Status Update
				add_action( 'wp_ajax_wcfm_modify_rental_quote_status', array( &$this, 'wcfm_modify_rental_quote_status' ) );
				
				// Quote Message
				add_action( 'wp_ajax_wcfm_rental_quote_message', array( &$this, 'wcfm_rental_quote_message' ) );
			} else {
				if( get_option( 'wcfm_updated_end_point_wcrental_pro_quote' ) ) {
					delete_option( 'wcfm_updated_end_point_wcrental_pro_quote' );
				}
			}
		}
		
		// WP Job Manager - Products Support - 2.3.4
    if( apply_filters( 'wcfm_is_allow_listings', true ) && apply_filters( 'wcfm_is_allow_products_for_listings', true ) ) {
			if ( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
				if( WCFM_Dependencies::wcfm_products_listings_active_check() && apply_filters( 'wcfm_is_allow_associate_listings_for_products', true ) ) {
					add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wpjm_associate_listings_product_manage_fields' ), 120 );
				}
				if( apply_filters( 'wcfm_is_allow_manage_products', true ) && apply_filters( 'wcfm_is_allow_add_products', true ) ) {
					if( WCFM_Dependencies::wcfm_products_listings_active_check() || WCFM_Dependencies::wcfm_products_mylistings_active_check() ) {
						add_filter( 'submit_job_form_fields', array( &$this, 'wcfm_add_listing_product_manage_fields' ), 999 );
						add_filter( 'submit_job_form_required_label', array( &$this, 'wcfm_my_listing_product_manage_fields' ), 999, 2 );
						add_filter( 'the_content', array( &$this, 'wcfmu_add_listing_page' ), 50 );
					}
				}
				add_action( 'wp_enqueue_scripts', array( $this, 'wcfmu_add_listing_enqueue_scripts' ) );
			}
		}
		
		// Toolset Types - Products Support - 2.5.0
    if( apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
			if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
				add_action( 'end_wcfm_settings', array( &$this, 'wcfm_toolset_types_settings' ), 15 );
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_toolset_types_product_manage_fields' ), 15 );
				add_action( 'end_wcfm_articles_manage', array( &$this, 'wcfm_toolset_types_article_manage_fields' ), 15 );
			}
		}
		
		// MapPress Support - 2.6.2
		if( $wcfm_is_allow_map = apply_filters( 'wcfm_is_allow_mappress', true ) ) {
			if ( WCFMu_Dependencies::wcfm_mappress_active_check() ) {
				//add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_mappress_product_manage_fields' ), 170 );
			}
		}
		
		// Toolset Types - User Fields Support - 3.0.1
		if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
			if( apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
				add_action( 'end_wcfm_user_profile', array( &$this, 'wcfm_toolset_types_user_profile_fields' ), 150 );
				add_action( 'end_wcfm_customers_manage_form', array( &$this, 'wcfm_toolset_types_user_profile_fields' ), 150 );
			}
			if( apply_filters( 'wcfm_is_allow_toolset_types_view', true ) ) {
				add_action( 'after_wcfm_vendor_general_details', array( &$this, 'wcfm_toolset_types_user_profile_fields_view' ), 150 );
				add_action( 'after_wcfm_customer_general_details', array( &$this, 'wcfm_toolset_types_user_profile_fields_view' ), 150 );
			}
		}
		
		// Toolset Types - Taxonomy Fields Support - 3.0.2
    if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
			if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
				add_action( 'end_wcfm_wcpvendors_settings', array( &$this, 'wcfm_toolset_types_taxonomy_fields' ), 150 );
			}
		}
		
    if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
    	if ( WCFMu_Dependencies::wcfm_acf_pro_active_check() ) { 
    		// Advanced Custom Fields(ACF) Pro - Products Support - 3.3.7
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_acf_pro_product_manage_fields' ), 160 );
				add_action( 'end_wcfm_articles_manage', array( &$this, 'wcfm_acf_pro_article_manage_fields' ), 160 );
			} elseif ( WCFMu_Dependencies::wcfm_acf_active_check() ) {
				// Advanced Custom Fields(ACF) - Products Support - 3.0.4
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_acf_product_manage_fields' ), 160 );
				add_action( 'end_wcfm_articles_manage', array( &$this, 'wcfm_acf_article_manage_fields' ), 160 );
			}
		}
		
		
		// Address Geocoder Support - 3.1.1
		if( $wcfm_is_allow_map = apply_filters( 'wcfm_is_allow_mappress', true ) ) {
			if ( WCFMu_Dependencies::wcfm_address_geocoder_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_address_geocoder_product_manage_fields' ), 170 );
			}
		}
		
		// Woocommerce Box Office Support - 3.3.3
    if( $wcfm_is_allow_wc_box_office = apply_filters( 'wcfm_is_allow_wc_box_office', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_box_office_active_check() ) {
				add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcfm_wc_box_office_product_manage_fields_general' ), 20, 3 );
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_box_office_product_manage_fields' ), 90 );
			}
		}
		
		// WooCommerce Lottery - 3.5.0
    if( apply_filters( 'wcfm_is_allow_lottery', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_lottery_active_check() ) {
				add_filter( 'after_wcfm_products_manage_general', array( &$this, 'wcfm_wc_lottery_product_manage_fields' ), 70, 2 );
			}
		}
		
		
		// WooCommerce Deposit - 3.5.9
		if( apply_filters( 'wcfm_is_allow_wc_deposits', true ) ) {
			if ( WCFMu_Dependencies::wcfm_wc_deposits_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_deposits_product_manage_fields' ), 180 );
			}
		}
		
		// WooCommerce PDF Vouchers - 4.0.0
		if( apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) && apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
			if ( WCFMu_Dependencies::wcfm_wc_pdf_voucher_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_pdf_vouchers_product_manage_fields' ), 180 );
				add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfm_wc_pdf_vouchers_product_manage_fields_variations' ), 11, 4 );
				
				// Generate Voucher Cosed Form HTML
				add_action( 'wp_ajax_wcfm_generate_voucher_code_html', array( &$this, 'wcfm_generate_voucher_code_html' ) );
			}
		}
		
		// WooCommerce Tab Manager - 4.1.0
		if( apply_filters( 'wcfm_is_allow_wc_tabs_manager', true ) ) {
			if ( WCFMu_Dependencies::wcfm_wc_tabs_manager_plugin_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_tabs_manager_product_manage_fields' ), 200 );
			}
		}
		
		// WooCommerce Warranty - 4.1.5
		if( apply_filters( 'wcfm_is_allow_wc_warranty', true ) ) {
			if ( WCFMu_Dependencies::wcfm_wc_warranty_plugin_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_warranty_product_manage_fields' ), 210 );
			}
		}
		
		// WooCommerce Waitlist - 4.1.5
		if( apply_filters( 'wcfm_is_allow_wc_waitlist', true ) ) {
			if ( WCFMu_Dependencies::wcfm_wc_waitlist_plugin_active_check() ) {
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_wc_waitlist_product_manage_fields' ), 220 );
			}
		}
	}
	
	/**
   * Thirdparty Query Var
   */
  function wcfmu_thirdparty_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
  	// Auction
  	if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_yith_auction_active_check() || WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
				$query_auction_vars = array(
					'wcfm-auctions'        => ! empty( $wcfm_modified_endpoints['wcfm-auctions'] ) ? $wcfm_modified_endpoints['wcfm-auctions'] : 'auctions',
				);
				$query_vars = array_merge( $query_vars, $query_auction_vars );
			}
		}
		
		// Rental
		if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
				$query_rental_vars = array(
					'wcfm-rental-calendar'        => ! empty( $wcfm_modified_endpoints['wcfm-rental-calendar'] ) ? $wcfm_modified_endpoints['wcfm-rental-calendar'] : 'rental-calendar',
					'wcfm-rental-quote'           => ! empty( $wcfm_modified_endpoints['wcfm-rental-quote'] ) ? $wcfm_modified_endpoints['wcfm-rental-quote'] : 'rental-quote',
					'wcfm-rental-quote-details'   => ! empty( $wcfm_modified_endpoints['wcfm-rental-quote-details'] ) ? $wcfm_modified_endpoints['wcfm-rental-quote-details'] : 'rental-quote-details',
				);
				$query_vars = array_merge( $query_vars, $query_rental_vars );
			}
		}
		
		return $query_vars;
  }
  
  /**
   * Thirdparty End Point Title
   */
  function wcfmu_thirdparty_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-auctions' :
				$title = __( 'Auctions', 'wc-frontend-manager-ultimate' );
			break;
			
			case 'wcfm-rental-calendar' :
				$title = __( 'Rental Calendar', 'wc-frontend-manager-ultimate' );
			break;
			
			case 'wcfm-rental-quote' :
				$title = __( 'Quote Request', 'wc-frontend-manager-ultimate' );
			break;
			
			case 'wcfm-rental-quote-details' :
			  $title = __( 'Manage Quote Request', 'wc-frontend-manager-ultimate' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * Thirdparty Endpoint Intialize - Auction
   */
  function wcfmu_thirdparty_auction_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_auction' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_auction', 1 );
		}
  }
  
  /**
   * Thirdparty Endpoint Intialize - Rental
   */
  function wcfmu_thirdparty_rental_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcrental_pro_quote' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcrental_pro_quote', 1 );
		}
  }
  
  /**
	 * Thirdparty Endpoiint Edit
	 */
	function wcfmu_thirdparty_endpoints_slug( $endpoints ) {
		
		// Auction
		if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_yith_auction_active_check() || WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
				$auction_endpoints = array(
															'wcfm-auctions'  		   => 'auctions',
															);
				$endpoints = array_merge( $endpoints, $auction_endpoints );
			}
		}
		
		// Rental
		if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
				$rental_endpoints = array(
															'wcfm-rental-calendar'  		   => 'rental-calendar',
															'wcfm-rental-quote'  		       => 'rental-quote',
															'wcfm-rental-quote-details'    => 'rental-quote-details'
															);
				$endpoints = array_merge( $endpoints, $rental_endpoints );
			}
		}
		
		return $endpoints;
	}
  
  /**
   * Thirdparty Menu
   */
  function wcfmu_thirdparty_menus( $menus ) {
  	global $WCFM;
  	
  	// Auction
  	if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_yith_auction_active_check() || WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
				$menus = array_slice($menus, 0, 3, true) +
														array( 'wcfm-auctions' => array(   'label'  => __( 'Auctions', 'wc-frontend-manager-ultimate' ),
																												 'url'       => get_wcfm_auction_url(),
																												 'icon'      => 'gavel',
																												 'priority'  => 25
																												) )	 +
															array_slice($menus, 3, count($menus) - 3, true) ;
			}
		}
		
		// Rental
		if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
				$menus = array_slice($menus, 0, 3, true) +
														array( 'wcfm-rental-calendar' => array(   'label'  => __( 'Rentals', 'wc-frontend-manager-ultimate' ),
																												 'url'       => get_wcfm_rental_url(),
																												 'icon'      => 'calendar-check-o',
																												 'priority'  => 30
																												),
																		'wcfm-rental-quote' => array(   'label'  => __( 'Quote', 'wc-frontend-manager-ultimate' ),
																												 'url'       => get_wcfm_rental_quote_url(),
																												 'icon'      => 'snowflake-o',
																												 'priority'  => 32
																												)
																												)	 +
															array_slice($menus, 3, count($menus) - 3, true) ;
															
				if( get_option( 'rnb_enable_rfq_btn', 'closed' ) == 'closed' ) {
					unset( $menus['wcfm-rental-quote'] );
				}
			}
		}
		
  	return $menus;
  }
  
  /**
   * WCFM Third Party Product Type
   */
  function wcfmu_thirdparty_product_types( $pro_types ) {
  	global $WCFM;
  	
  	// WP Job Manager - Resume Manager Product Type
  	if( $wcfm_allow_resume_manager = apply_filters( 'wcfm_is_allow_resume_manager', true ) ) {
			if ( WCFMu_Dependencies::wcfm_resume_manager_active_check() ) {
				$pro_types['resume_package'] = __( 'Resume Package', 'wp-job-manager-resumes' );
			}
		}
  	
  	// Auction
  	if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
			if( WCFMu_Dependencies::wcfm_yith_auction_active_check() || WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
				$pro_types['auction'] = __( 'Auction', 'wc-frontend-manager-ultimate' );
			}
		}
  	
  	// Rental
  	if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
				$pro_types['redq_rental'] = __( 'Rental Product', 'wc-frontend-manager-ultimate' );
			}
		}
		
		// Lottery
  	if( apply_filters( 'wcfm_is_allow_lottery', true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_lottery_active_check() ) {
				$pro_types['lottery'] = __( 'Lottery', 'wc-frontend-manager-ultimate' );
			}
		}
  	
  	return $pro_types;
  }
  
  /**
	 * WCFM Capability Product Types
	 */
	function wcfmcap_product_types( $product_types, $handler = 'wcfm_capability_options', $wcfm_capability_options = array() ) {
		global $WCFM, $WCFMu;
		
		if ( WCFMu_Dependencies::wcfm_resume_manager_active_check() ) {
			$resume_package = ( isset( $wcfm_capability_options['resume_package'] ) ) ? $wcfm_capability_options['resume_package'] : 'no';
		
			$product_types["resume_package"] = array('label' => __('Resume Package', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[resume_package]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $resume_package);
		}
		
		if( WCFMu_Dependencies::wcfm_yith_auction_active_check() || WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
			$auction = ( isset( $wcfm_capability_options['auction'] ) ) ? $wcfm_capability_options['auction'] : 'no';
		
			$product_types["auction"] = array('label' => __('Auction', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[auction]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $auction);
		}
		
		if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
			$rental = ( isset( $wcfm_capability_options['rental'] ) ) ? $wcfm_capability_options['rental'] : 'no';
			
			$product_types["rental"] = array('label' => __('Rental', 'wc-frontend-manager') , 'name' => $handler . '[rental]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $rental);
		}
		
		if( WCFMu_Dependencies::wcfm_wc_box_office_active_check() ) {
			$wc_box_office_ticket = ( isset( $wcfm_capability_options['wc_box_office_ticket'] ) ) ? $wcfm_capability_options['wc_box_office_ticket'] : 'no';
			
			$product_types["wc_box_office_ticket"] = array('label' => __('Ticket', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[wc_box_office_ticket]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $wc_box_office_ticket);
		}
		
		if( WCFMu_Dependencies::wcfm_wc_lottery_active_check() ) {
			$lottery = ( isset( $wcfm_capability_options['lottery'] ) ) ? $wcfm_capability_options['lottery'] : 'no';
		
			$product_types["lottery"] = array('label' => __('Lottery', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[lottery]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $lottery);
		}
		
		return $product_types;
	}
	
  /**
   * Third Party Scripts
   */
  public function wcfmu_thirdparty_load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	case 'wcfm-articles-manage':
	  	  // Advanced Custom Fields(ACF) - Articles Support - 4.2.3
				if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
					if ( WCFMu_Dependencies::wcfm_acf_active_check() ) {
						wp_enqueue_script( 'wcfmu_acf_articles_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-acf-articles-manage.js', array( 'jquery', 'wcfm_articles_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// Advanced Custom Fields(ACF) Pro - Articles Support - 4.2.3
				if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
					if ( WCFMu_Dependencies::wcfm_acf_pro_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_acf_pro_articles_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-acf-pro-articles-manage.js', array( 'jquery', 'wcfm_articles_manage_js' ), $WCFMu->version, true );
					}
				}
	  	break;
	  	
	  	case 'wcfm-products-manage':
	  	 	 // YITH Auction Support
		  	if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
					if( WCFMu_Dependencies::wcfm_yith_auction_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_yithauction_products_manage_js', $WCFM->library->js_lib_url . 'products-manager/wcfm-script-yithauction-products-manage.js', array( 'jquery', 'wcfm_timepicker_js', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// WooCommerce Simple Auction Support
		  	if( $wcfm_allow_auction = apply_filters( 'wcfm_is_allow_auction', true ) ) {
					if( WCFMu_Dependencies::wcfm_wcs_auction_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_wcsauction_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wcsauction-products-manage.js', array( 'jquery', 'wcfm_timepicker_js', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// WC Rental & Booking Pro Support - 2.3.10
				if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
					if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
						wp_enqueue_script( 'wcfmu_wc_rental_pro_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wc-rental-pro-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// Toolset Types - Products Support - 3.1.7
				if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
					if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
						wp_enqueue_script( 'wcfmu_toolset_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-toolset-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
						$wcfm_product_type_toolset_fields = (array) get_option( 'wcfm_product_type_toolset_fields' );
						wp_localize_script( 'wcfmu_toolset_products_manage_js', 'wcfm_product_type_toolset_fields', $wcfm_product_type_toolset_fields );
					}
				}
				
				// Advanced Custom Fields(ACF) - Products Support - 3.0.4
				if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
					if ( WCFMu_Dependencies::wcfm_acf_active_check() ) {
						wp_enqueue_script( 'wcfmu_acf_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-acf-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// Advanced Custom Fields(ACF) Pro - Products Support - 3.3.7
				if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
					if ( WCFMu_Dependencies::wcfm_acf_pro_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_acf_pro_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-acf-pro-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// Address Geocoder Support - 3.1.1
				if( $wcfm_is_allow_map = apply_filters( 'wcfm_is_allow_mappress', true ) ) {
					if ( WCFMu_Dependencies::wcfm_address_geocoder_active_check() ) {
						$address_geocoder_options = get_option('address_geocoder_options');
            $apikey = $address_geocoder_options['apikey'];

            if ( ! empty( $apikey ) ) {
							$mapsapi = '//maps.googleapis.com/maps/api/js?key=' . $apikey;
							wp_register_script( 'wcfmu_googlemaps', $mapsapi );
							wp_register_script( 'wcfmu_geocoder_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-address-geocoder-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );

							wp_enqueue_script( 'wcfmu_googlemaps' );
							wp_enqueue_script( 'wcfmu_geocoder_products_manage_js' );
            }
					}
				}
				
				// Woocommerce Box Office Support - 3.3.3
				if( $wcfm_is_allow_wc_box_office = apply_filters( 'wcfm_is_allow_wc_box_office', true ) ) {
					if( WCFMu_Dependencies::wcfm_wc_box_office_active_check() ) {
						wp_enqueue_script( 'wcfmu_wc_box_office_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wc-box-office-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// WooCommerce Lottery Support
		  	if( apply_filters( 'wcfm_is_allow_lottery', true ) ) {
					if( WCFMu_Dependencies::wcfm_wc_lottery_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_wc_lottery_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wc-lottery-products-manage.js', array( 'jquery', 'wcfm_timepicker_js', 'wcfm_products_manage_js' ), $WCFMu->version, true );
					}
				}
				
				// WooCommerce PDF Vouchers Support - 4.0.0
				if( apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) && apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
					if ( WCFMu_Dependencies::wcfm_wc_pdf_voucher_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmu_wc_pdf_vouchers_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wc-pdf-vouchers-products-manage.js', array( 'jquery', 'wcfm_timepicker_js', 'wcfm_products_manage_js' ), $WCFMu->version, true );
						wp_localize_script( 'wcfmu_wc_pdf_vouchers_products_manage_js', 'WooVouMeta', array(	
																					'noofvouchererror' 			=> '<div>' . __( 'Please enter Number of Voucher Codes.', 'woovoucher' ) . '</div>',
																					'patternemptyerror' 		=> '<div>' . __( 'Please enter Pattern to import voucher code(s).', 'woovoucher' ) . '</div>',
																					'onlydigitserror' 			=> '<div>' . __( 'Please enter only Numeric values in Number of Voucher Codes.', 'woovoucher' ) . '</div>',
																					'generateerror' 			=> '<div>' . __( 'Please enter Valid Pattern to import voucher code(s).', 'woovoucher' ) . '</div>',
																					'filetypeerror'				=> '<div>' . __( 'Please upload csv file.', 'woovoucher' ) . '</div>',
																					'fileerror'					=> '<div>' . __( 'File can not be empty, please upload valid file.', 'woovoucher' ) . '</div>',
																					'enable_voucher'        	=> get_option( 'vou_enable_voucher' ), //Localize "Auto Enable Voucher" setting to use in JS 
																					'price_options'        		=> get_option( 'vou_voucher_price_options' ), //Localize "Voucher Price Options" setting to use in JS 
																					'invalid_price'         	=> __( 'You can\'t leave this empty.', 'woovoucher' ),
																					'woo_vou_nonce'				=> wp_create_nonce( 'woo_vou_pre_publish_validation' ),
																					'prefix_placeholder'		=> __('WPWeb', 'woovoucher'),
																					'seperator_placeholder' 	=> __('-', 'woovoucher'),
																					'pattern_placeholder'		=> __('LLDD', 'woovoucher'),
																					'global_vou_pdf_usability'	=> get_option('vou_pdf_usability'),
																					'vouchercodegenerated'  => __( 'Voucher codes successfully generated.', 'wc-frontend-manager-ultimate' )
																				) );
					}
				}
				
	  	break; 	
	  	
	  	case 'wcfm-rental-calendar':
      	$WCFMu->library->load_fullcalendar_lib();
	    	wp_enqueue_script( 'wcfmu_rental_calendar_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wcrental-calendar.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-rental-quote':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmu_rental_quote_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wcrental-quote.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-rental-quote-details':
	    	wp_enqueue_script( 'wcfmu_rental_quote_details_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-wcrental-quote-details.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-auctions':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmu_auctions_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-auctions.js', array('jquery'), $WCFMu->version, true );
      break;
	  }
	}
	
	/**
   * Third Party Styles
   */
	public function wcfmu_thirdparty_load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
		  	// WC Rental & Booking Pro Support - 2.3.10
				if( $wcfm_allow_rental = apply_filters( 'wcfm_is_allow_rental', true ) ) {
					if( WCFMu_Dependencies::wcfm_wc_rental_pro_active_check() ) {
						wp_enqueue_style( 'wcfmu_wc_rental_pro_products_manage_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-wc-rental-pro-products-manage.css', array( ), $WCFMu->version );
					}
				}
				
				// MapPress Support - 2.6.2
				if( $wcfm_is_allow_map = apply_filters( 'wcfm_is_allow_map', true ) ) {
					if ( WCFMu_Dependencies::wcfm_mappress_active_check() ) {
						//wp_enqueue_style('mappress-admin', Mappress::$baseurl . '/css/mappress_admin.css', null, Mappress::VERSION);
						//wp_enqueue_style( 'wcfmu_mappress_products_manage_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-mappress-products-manage.css', array( 'mappress-admin' ), $WCFMu->version );
					}
				}
				
				// Woocommerce Box Office Support - 3.3.3
				if( $wcfm_is_allow_wc_box_office = apply_filters( 'wcfm_is_allow_wc_box_office', true ) ) {
					if( WCFMu_Dependencies::wcfm_wc_box_office_active_check() ) {
						wp_enqueue_style( 'wcfmu_wc_box_office_products_manage_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-wc-box-office-products-manage.css', array( ), $WCFMu->version );
					}
				}
				
				// WooCommerce PDF Vouchers Support - 4.0.0
				if( apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) && apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
					if ( WCFMu_Dependencies::wcfm_wc_pdf_voucher_active_check() ) {
						wp_enqueue_style( 'wcfmu_wc_pdf_vouchers_products_manage_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-wc-pdf-vouchers-products-manage.css', array( ), $WCFMu->version );
					}
				}
		  break;
		  
		  case 'wcfm-rental-quote':
	    	wp_enqueue_style( 'wcfmu_rental_quote_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-wcrental-quote.css', array(), $WCFMu->version );
      break;
      
      case 'wcfm-rental-quote-details':
      	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_rental_quote_details_css', $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-wcrental-quote-details.css', array(), $WCFMu->version );
      break;
		  
	  	case 'wcfm-auctions':
	    	wp_enqueue_style( 'wcfmu_auctions_css',  $WCFMu->library->css_lib_url . 'thirdparty/wcfmu-style-auctions.css', array(), $WCFMu->version );
		  break;
	  }
	}
	
	/**
   * Third Party Views
   */
  public function wcfmu_thirdparty_load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
	  		// WC Per Product Shipping Support - 2.5.0
				if( apply_filters( 'wcfm_is_allow_shipping', true ) && apply_filters( 'wcfm_is_allow_per_product_shipping', true ) ) {
					if ( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() || ( $WCFMu->is_marketplace == 'wcpvendors' ) ) {
						$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-per-product-shipping-products-manage.php' );
					}
				}
				
				// WooCommerce Additional Variation Images - 3.0.2
				if( apply_filters( 'wcfm_is_allow_gallery', true ) ) {
					if ( WCFMu_Dependencies::wcfm_wc_variation_gallery_active_check() ) {
						$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-variation-gallery-products-manage.php' );
					}
				}
	  	 break;
	  	 
	  	case 'wcfm-rental-calendar':
        $WCFMu->template->get_template( 'thirdparty/wcfmu-view-wcrental-calendar.php' );
      break;
      
      case 'wcfm-rental-quote':
        $WCFMu->template->get_template( 'thirdparty/wcfmu-view-wcrental-quote.php' );
      break;
      
      case 'wcfm-rental-quote-details':
        $WCFMu->template->get_template( 'thirdparty/wcfmu-view-wcrental-quote-details.php' );
      break;
      
      case 'wcfm-auctions':
        $WCFMu->template->get_template( 'thirdparty/wcfmu-view-auctions.php' );
      break;
	  }
	}
	
	/**
   * Third Party Ajax Controllers
   */
  public function wcfmu_thirdparty_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFMu->plugin_path . 'controllers/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-articles-manage':
  				// Toolset Types - Articles Support - 4.2.3
					if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
						if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-toolset-types-articles-manage.php' );
							new WCFMu_Toolset_Types_Articles_Manage_Controller();
						}
					}
					
					// Advanced Custom Fields(ACF) - Articles Support - 4.2.3
					if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
						if ( WCFMu_Dependencies::wcfm_acf_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-acf-articles-manage.php' );
							new WCFMu_ACF_Articles_Manage_Controller();
						}
					}
					
					// Advanced Custom Fields(ACF) - Articles Support - 4.2.3
					if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
						if ( WCFMu_Dependencies::wcfm_acf_pro_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-acf-pro-articles-manage.php' );
							new WCFMu_ACF_Pro_Articles_Manage_Controller();
						}
					}
				break;
  			
  			case 'wcfm-products-manage':
  				include_once( $controllers_path . 'thirdparty/wcfmu-controller-thirdparty-products-manage.php' );
					new WCFMu_ThirdParty_Products_Manage_Controller();
					
					// WC Per Product Shipping - Products Support - 2.5.0
					if( apply_filters( 'wcfm_is_allow_shipping', true ) && apply_filters( 'wcfm_is_allow_per_product_shipping', true ) ) {
						if ( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() || ( $WCFMu->is_marketplace == 'wcpvendors' ) ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-wc-per-product-shipping-products-manage.php' );
							new WCFMu_WC_Per_Product_Shipping_Products_Manage_Controller();
						}
					}
					
					// Toolset Types - Products Support - 2.5.0
					if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
						if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-toolset-types-products-manage.php' );
							new WCFMu_Toolset_Types_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Additional Variation Images - 3.0.2
					if( $wcfm_is_allow_gallery = apply_filters( 'wcfm_is_allow_gallery', true ) ) {
						if ( WCFMu_Dependencies::wcfm_wc_variation_gallery_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-wc-variation-gallery-products-manage.php' );
							new WCFMu_WC_Variation_Gallery_Products_Manage_Controller();
						}
					}
					
					// Advanced Custom Fields(ACF) - Products Support - 3.0.4
					if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
						if ( WCFMu_Dependencies::wcfm_acf_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-acf-products-manage.php' );
							new WCFMu_ACF_Products_Manage_Controller();
						}
					}
					
					// Advanced Custom Fields(ACF) - Products Support - 3.0.4
					if( $wcfm_allow_acf_fields = apply_filters( 'wcfm_is_allow_acf_fields', true ) ) {
						if ( WCFMu_Dependencies::wcfm_acf_pro_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-acf-pro-products-manage.php' );
							new WCFMu_ACF_Pro_Products_Manage_Controller();
						}
					}
					
					// Address Geocoder Support - 3.1.1
					if( $wcfm_is_allow_map = apply_filters( 'wcfm_is_allow_mappress', true ) ) {
						if ( WCFMu_Dependencies::wcfm_address_geocoder_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-address-geocoder-products-manage.php' );
						  new WCFMu_Address_Geocoder_Products_Manage_Controller();
						}
					}
					
					// WooCommerce PDF Vouchers Support - 4.0.0
					if( apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) && apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
						if ( WCFMu_Dependencies::wcfm_wc_pdf_voucher_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-wc-pdf-vouchers-products-manage.php' );
						  new WCFMu_WC_PDF_Vouchers_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Tab Manager - 4.1.0
					if( apply_filters( 'wcfm_is_allow_wc_tabs_manager', true ) ) {
						if ( WCFMu_Dependencies::wcfm_wc_tabs_manager_plugin_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-wc-tabs-manager-products-manage.php' );
						  new WCFMu_WC_Tabs_Manager_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Warranty - 4.1.5
					if( apply_filters( 'wcfm_is_allow_wc_warranty', true ) ) {
						if ( WCFMu_Dependencies::wcfm_wc_warranty_plugin_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-wc-warranty-products-manage.php' );
						  new WCFMu_WC_Warranty_Products_Manage_Controller();
						}
					}
  			break;
  			
  			case 'wcfm-rental-quote':
  				include_once( $controllers_path . 'thirdparty/wcfmu-controller-rental-quote.php' );
					new WCFMu_Rental_Quote_Controller();
  			break;
  			
  			case 'wcfm-auctions':
  				include_once( $controllers_path . 'thirdparty/wcfmu-controller-auctions.php' );
					new WCFMu_Auctions_Controller();
  			break;
  			
  			case 'wcfm-profile':
  			case 'wcfm-customers-manage':
  				// Toolset Types - Products Support - 3.0.1
					if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
						if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-toolset-types-user-profile.php' );
							new WCFMu_Toolset_Types_User_Profile_Controller();
						}
					}
				break;
				
				case 'wcfm-settings':
  				// Toolset Types - Products Support - 3.1.7
					if( $wcfm_allow_toolset_types = apply_filters( 'wcfm_is_allow_toolset_types', true ) ) {
						if ( WCFMu_Dependencies::wcfm_toolset_types_active_check() ) {
							include_once( $controllers_path . 'thirdparty/wcfmu-controller-toolset-types-settings.php' );
							new WCFMu_Toolset_Types_Settings_Controller();
						}
					}
  			break;
  		}
  	}
  }
  
  /**
	 * Product Manage Third Party Variation aditional options
	 */
	function wcfmu_thirdparty_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
		global $WCFM, $WCFMu;
		
		// WooCommerce Barcode & ISBN Support
		if( $allow_barcode_isbn = apply_filters( 'wcfm_is_allow_barcode_isbn', true ) ) {
			if(WCFM_Dependencies::wcfm_wc_barcode_isbn_plugin_active_check()) {
				$barcode_fields = array(  
																"barcode" => array('label' => __('Barcode', 'wc-frontend-manager-ultimate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable', 'label_class' => 'wcfm_title wcfm_ele wcfm_half_ele_title variable' ),
																"ISBN" => array('label' => __('ISBN', 'wc-frontend-manager-ultimate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable', 'label_class' => 'wcfm_ele wcfm_title wcfm_half_ele_title variable' )
																);
				$variation_fileds = array_merge( $variation_fileds, $barcode_fields);
			}
		}
		
		// WooCommerce MSRP Pricing Support
		if( $allow_msrp_pricing = apply_filters( 'wcfm_is_allow_msrp_pricing', true ) ) {
			if(WCFM_Dependencies::wcfm_wc_msrp_pricing_plugin_active_check()) {
				$msrp_fields = array(  
																"_msrp" => array('label' => __('MSRP Price', 'wc-frontend-manager-ultimate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable' ),
																);
				$variation_fileds = array_merge( $variation_fileds, $msrp_fields);
			}
		}
		
		// WooCommerce Product Fees Support
		if( $allow_product_fees = apply_filters( 'wcfm_is_allow_product_fees', true ) ) {
			if(WCFM_Dependencies::wcfm_wc_product_fees_plugin_active_check()) {
				$product_fees_fields = array(  
																			"product-fee-name" => array('label' => __('Fee Name', 'wc-frontend-manager-ultimate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable', 'label_class' => 'wcfm_title wcfm_half_ele_title wcfm_ele variable', 'hints' => __( 'This will be shown at the checkout description the added fee.', 'wc-frontend-manager-ultimate' )),
																			"product-fee-amount" => array('label' => __('Fee Amount', 'wc-frontend-manager-ultimate') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable', 'label_class' => 'wcfm_ele wcfm_title wcfm_half_ele_title variable', 'hints' => __( 'Enter a monetary decimal without any currency symbols or thousand separator. This field also accepts percentages.', 'wc-frontend-manager-ultimate' )),
																			"product-fee-multiplier" => array('label' => __('Multiple Fee by Quantity', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele variable', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title variable', 'hints' => __( 'Multiply the fee by the quantity of this product that is added to the cart.', 'wc-frontend-manager-ultimate' ) ),
																		);
				$variation_fileds = array_merge( $variation_fileds, $product_fees_fields);
			}
		}
		
		// WooCOmmerce Role Based Price Suport
		if( apply_filters( 'wcfm_is_allow_role_based_price', true ) ) {
			if(WCFM_Dependencies::wcfm_wc_role_based_price_active_check()) {
				if ( !function_exists('get_editable_roles') ) {
					 include_once( ABSPATH . '/wp-admin/includes/user.php' );
				}
				$wp_roles = get_editable_roles();
				$wc_rbp_general = (array) get_option( 'wc_rbp_general' );
				if( !empty( $wc_rbp_general ) ) {
					$wc_rbp_allowed_roles = ( isset( $wc_rbp_general['wc_rbp_allowed_roles'] ) ) ? $wc_rbp_general['wc_rbp_allowed_roles'] : array();
					$wc_rbp_regular_price_label = ( isset( $wc_rbp_general['wc_rbp_regular_price_label'] ) ) ? $wc_rbp_general['wc_rbp_regular_price_label'] : __( 'Regular Price', 'wc-frontend-manager' );
					$wc_rbp_selling_price_label = ( isset( $wc_rbp_general['wc_rbp_selling_price_label'] ) ) ? $wc_rbp_general['wc_rbp_selling_price_label'] : __( 'Selling Price', 'wc-frontend-manager' );
					if( !empty( $wc_rbp_allowed_roles ) ) {
						foreach( $wc_rbp_allowed_roles as $wc_rbp_allowed_role ) {
							$role_based_price_fields = array(  
																								$wc_rbp_allowed_role . "-regularprice-rolebased" => array( 'label' => $wp_roles[$wc_rbp_allowed_role]['name'] . ' ' . $wc_rbp_regular_price_label, 'type' => 'text', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_ele wcfm_title variable' ),
																								$wc_rbp_allowed_role . "-sellingprice-rolebased"    => array( 'label' => $wp_roles[$wc_rbp_allowed_role]['name'] . ' ' . $wc_rbp_selling_price_label, 'type' => 'text', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_ele wcfm_title variable' ),
																							);
							$variation_fileds = array_merge( $variation_fileds, $role_based_price_fields);
						}
					}
				}
			}
		}
		
	  return $variation_fileds;									
	}
	
	/**
	 * Product Manage Third Party Variaton edit data
	 */
	function wcfmu_thirdparty_product_data_variations( $variations, $variation_id, $variation_id_key ) {
		global $WCFM, $WCFMu;
		
		if( $variation_id  ) {
			// WooCommerce Barcode & ISBN Support
			if( $allow_barcode_isbn = apply_filters( 'wcfm_is_allow_barcode_isbn', true ) ) {
				if(WCFM_Dependencies::wcfm_wc_barcode_isbn_plugin_active_check()) {
					$variations[$variation_id_key]['barcode'] = get_post_meta( $variation_id, 'barcode', true );
					$variations[$variation_id_key]['ISBN'] = get_post_meta( $variation_id, 'ISBN', true);
				}
			}
			
			// WooCommerce MSRP Pricing Support
			if( $allow_msrp_pricing = apply_filters( 'wcfm_is_allow_msrp_pricing', true ) ) {
				if(WCFM_Dependencies::wcfm_wc_msrp_pricing_plugin_active_check()) {
					$variations[$variation_id_key]['_msrp'] = get_post_meta( $variation_id, '_msrp', true);
				}
			}
			
			// WooCommerce Product Fees Support
			if( $allow_product_fees = apply_filters( 'wcfm_is_allow_product_fees', true ) ) {
				if(WCFM_Dependencies::wcfm_wc_product_fees_plugin_active_check()) {
					$variations[$variation_id_key]['product_fee_name'] = get_post_meta( $variation_id, 'product-fee-name', true );
					$variations[$variation_id_key]['product_fee_amount'] = get_post_meta( $variation_id, 'product-fee-amount', true );
					$variations[$variation_id_key]['product_fee_multiplier'] = get_post_meta( $variation_id, 'product-fee-multiplier', true );
				}
			}
			
			// WooCOmmerce Role Based Price Suport
			if( apply_filters( 'wcfm_is_allow_role_based_price', true ) ) {
				if(WCFM_Dependencies::wcfm_wc_role_based_price_active_check()) {
					$wc_rbp_general = (array) get_option( 'wc_rbp_general' );
					if( !empty( $wc_rbp_general ) ) {
						$wc_rbp_allowed_roles = ( isset( $wc_rbp_general['wc_rbp_allowed_roles'] ) ) ? $wc_rbp_general['wc_rbp_allowed_roles'] : array();
						if( !empty( $wc_rbp_allowed_roles ) ) {
							$role_based_price = (array) get_post_meta( $variation_id, '_role_based_price', true );
							foreach( $wc_rbp_allowed_roles as $wc_rbp_allowed_role ) {
								$regular_price = '';
								$selling_price = '';
								if( isset( $role_based_price[$wc_rbp_allowed_role] ) && isset( $role_based_price[$wc_rbp_allowed_role]['regular_price'] ) ) $regular_price = $role_based_price[$wc_rbp_allowed_role]['regular_price'];
								if( isset( $role_based_price[$wc_rbp_allowed_role] ) && isset( $role_based_price[$wc_rbp_allowed_role]['selling_price'] ) ) $selling_price = $role_based_price[$wc_rbp_allowed_role]['selling_price'];
								$variations[$variation_id_key][$wc_rbp_allowed_role.'-regularprice-rolebased'] = $regular_price;
								$variations[$variation_id_key][$wc_rbp_allowed_role.'-sellingprice-rolebased'] = $selling_price;
							}
						}
					}
				}
			}
			
			if( apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) && apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
				if ( WCFMu_Dependencies::wcfm_wc_pdf_voucher_active_check() ) {
					$variations[$variation_id_key]['_woo_vou_variable_pdf_template'] = get_post_meta( $variation_id, '_woo_vou_pdf_template', true );
					$variations[$variation_id_key]['_woo_vou_variable_voucher_delivery'] = get_post_meta( $variation_id, '_woo_vou_voucher_delivery', true );
					$variations[$variation_id_key]['_woo_vou_variable_codes'] = get_post_meta( $variation_id, '_woo_vou_codes', true );
					$variations[$variation_id_key]['_woo_vou_variable_vendor_address'] = get_post_meta( $variation_id, '_woo_vou_vendor_address', true );
				}
			}
			
		}
		
		return $variations;
	}
	
  /**
	 * WP Job Manager - Resume Manager Product General options
	 */
	function wcfm_wpjrm_product_manage_fields( $general_fields, $product_id ) {
		global $WCFM;
		
		$_resume_package_subscription_type        = '';
		$_resume_limit     = '';
		$_resume_duration       = '';
		$_resume_featured = 'no';
		
		if( $product_id ) {
			$_resume_package_subscription_type        = get_post_meta( $product_id, '_resume_package_subscription_type', true );
			$_resume_limit     = get_post_meta( $product_id, '_resume_limit', true );
			$_resume_duration       = get_post_meta( $product_id, '_resume_duration', true );
			$_resume_featured = get_post_meta( $product_id, '_resume_featured', true );
		}
		
		$pos_counter = 4;
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) $pos_counter = 6;
		
		$general_fields = array_slice($general_fields, 0, $pos_counter, true) +
																	array( 
																				"_resume_package_subscription_type" => array( 'label' => __('Subscription Type', 'wp-job-manager-resumes' ), 'type' => 'select', 'options' => array( 'package' => __( 'Link the subscription to the package (renew listing limit every subscription term)', 'wp-job-manager-resumes' ), 'listing' => __( 'Link the subscription to posted listings (renew posted listings every subscription term)', 'wp-job-manager-resumes' ) ), 'class' => 'wcfm-select wcfm_ele resume_package_price_ele resume_package', 'label_class' => 'wcfm_title wcfm_ele resume_package', 'hints' => __( 'Choose how subscriptions affect this package', 'wp-job-manager-resumes' ), 'value' => $_resume_package_subscription_type ),
																				"_resume_limit" => array( 'label' => __('Resume listing limit', 'wp-job-manager-resumes' ), 'placeholder' => __( 'Unlimited', 'wc-frontend-manager-ultimate'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele resume_package_price_ele resume_package', 'label_class' => 'wcfm_title wcfm_ele resume_package', 'attributes' => array( 'min'   => '', 'step' 	=> '1' ), 'hints' => __( 'The number of resumes a user can post with this package.', 'wp-job-manager-resumes' ), 'value' => $_resume_limit ),
																				"_resume_duration" => array( 'label' => __('Resume listing duration', 'wp-job-manager-resumes' ), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele resume_package_price_ele resume_package', 'label_class' => 'wcfm_title wcfm_ele resume_package', 'attributes' => array( 'min'   => '', 'step' 	=> '1' ), 'hints' => __( 'The number of days that the resume will be active.', 'wp-job-manager-resumes' ), 'value' => $_resume_duration ),
																				"_resume_featured" => array( 'label' => __('Feature Listings?', 'wp-job-manager-resumes' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele resume_package_price_ele resume_package', 'label_class' => 'wcfm_title checkbox_title wcfm_ele resume_package', 'hints' => __( 'Feature this resume - it will be styled differently and sticky.', 'wp-job-manager-resumes' ), 'value' => 'yes', 'dfvalue' => $_resume_featured ),
																				) +
																	array_slice($general_fields, $pos_counter, count($general_fields) - 1, true) ;
		return $general_fields;
	}
	
  /**
	 * YITH Auction Product General options
	 */
	function wcfm_yithauction_product_manage_fields( $product_id = 0, $product_type ) {
		global $WCFM, $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-yithauctions-product-manage.php' );
	}
	
  /**
	 * WooCommerce Simple Auction Product General options
	 */
	function wcfm_wcsauction_product_manage_fields( $product_id = 0, $product_type ) {
		global $WCFM, $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wcsauctions-product-manage.php' );
	}
	
  /**
	 * WC Rental Pro Product General options
	 */
	function wcfm_wcrental_pro_product_manage_fields( $product_id = 0, $product_type ) {
		global $WCFM, $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wcrentalpro-product-manage.php' );
	}
	
	/**
   * WP Job Manager - Products Manage General options
   */
  function wcfm_wpjm_associate_listings_product_manage_fields( ) {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-wpjm-associate-listings-products-manage.php' );
	}
	
	/**
	 * WC Lottery Product General options
	 */
	function wcfm_wc_lottery_product_manage_fields( $product_id = 0, $product_type ) {
		global $WCFM, $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-lottery-product-manage.php' );
	}
	
	
	/**
	 * Listings - Associate Products Init - 3.2.1
	 */
	function wcfm_add_listing_product_manage_fields( $fields ) {
		
		if ( ! get_option( 'wpjmp_enable_products_on_listings' ) ) {
			return $fields;
		}
		
		if( isset( $fields['company'] ) && isset( $fields['company']['products'] ) ) {
			$products_create_description = sprintf( __( '%s | %s', 'wp-job-manager' ), '<a href="#" data-product_field="products" class="wcfm-add-product wcfm_listing_product_option"><i class="fa fa-cube"></i> ' . __( 'Create New Product', 'wc-frontend-manager-ultimate' ) . '</a>',  '<a href="' . get_wcfm_products_url() . '" class="wcfm-manage-products wcfm_listing_product_option" target="_blank"><i class="fa fa-cubes"></i> ' . __( 'Manage Products', 'wc-frontend-manager-ultimate' ) . '</a>');
			$fields['company']['products']['description'] = $products_create_description;
		} else {
			$products_create_description = sprintf( __( '%s | %s', 'wp-job-manager' ), '<a href="#" data-product_field="products" class="wcfm-add-product wcfm_listing_product_option"><i class="fa fa-cube"></i> ' . __( 'Create New Product', 'wc-frontend-manager-ultimate' ) . '</a>',  '<a href="' . get_wcfm_products_url() . '" class="wcfm-manage-products wcfm_listing_product_option" target="_blank"><i class="fa fa-cubes"></i> ' . __( 'Manage Products', 'wc-frontend-manager-ultimate' ) . '</a>');
			$fields['company']['products'] = array(
				'label'			  => get_option( 'wpjmp_select_products_text' ),
				'type'			  => 'multiselect',
				'options'		  => array(),
				'required'	  => false,
				'description' => $products_create_description,
				'priority' 	  => 10,
			);
		}
		
		return $fields;
	}
	
	/**
	 * My Listings - Associate Product init - 3.6.0
	 */
	function wcfm_my_listing_product_manage_fields( $label, $field ) {
		if( isset( $field['type'] ) && ( $field['type'] == 'select-products' ) ) {
			$products_create_description = sprintf( __( '%s | %s', 'wp-job-manager' ), '<a href="#" data-product_field="' . $field['slug'] . '" class="wcfm-add-product wcfm_listing_product_option"><i class="fa fa-cube"></i> ' . __( 'Create New Product', 'wc-frontend-manager-ultimate' ) . '</a>',  '<a href="' . get_wcfm_products_url() . '" class="wcfm-manage-products wcfm_listing_product_option" target="_blank"><i class="fa fa-cubes"></i> ' . __( 'Manage Products', 'wc-frontend-manager-ultimate' ) . '</a>');
			$label .= '&nbsp;&nbsp;' . $products_create_description;
		}
		return $label;
	}
	
	/**
	 * Listings - Associate Products CSS/JS - 3.2.1
	 */
	function wcfmu_add_listing_enqueue_scripts() {
		global $WCFM, $WCFMu, $post, $_GET;
		
		$job_dashboard_page = get_option( 'job_manager_job_dashboard_page_id' );
		$add_listings_page = get_option( 'job_manager_submit_job_form_page_id' );
		if( ( $add_listings_page && is_object( $post ) && ( $add_listings_page == $post->ID ) ) || ( $job_dashboard_page && is_object( $post ) && ( $job_dashboard_page == $post->ID ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'edit' ) ) ) {
			
			if( WCFM_Dependencies::wcfm_products_listings_active_check() || WCFM_Dependencies::wcfm_products_mylistings_active_check() ) {
				// Load Scripts
				$WCFM->library->load_scripts( 'wcfm-products-manage' );
				wp_enqueue_script( 'wcfm_product_popup_js', $WCFM->library->js_lib_url . 'products-popup/wcfm-script-product-popup.js', array('jquery', 'wcfm_products_manage_js'), $WCFM->version, true );
				wp_enqueue_script( 'wcfm_add_listings_products_manage_js', $WCFMu->library->js_lib_url . 'thirdparty/wcfmu-script-add-listings-products-manage.js', array('jquery', 'wcfm_product_popup_js'), $WCFMu->version, true );
				
				// Load Styles
				$WCFM->library->load_styles( 'wcfm-products-manage' );
				wp_enqueue_style( 'wcfm_product_popup_css',  $WCFM->library->css_lib_url . 'products-popup/wcfm-style-product-popup.css', array( 'wcfm_products_manage_css' ), $WCFM->version );
				wp_enqueue_style( 'wcfm_add_listings_css', $WCFM->library->css_lib_url . 'listings/wcfm-style-listings-manage.css', array(), $WCFM->version );
			}
		}
	}
	
	/**
	 * Listings - Associate Product manager - 3.2.1
	 */
	function wcfmu_add_listing_page( $content ) {
		global $post, $WCFM;
		
		$job_dashboard_page = get_option( 'job_manager_job_dashboard_page_id' );
		$add_listings_page = get_option( 'job_manager_submit_job_form_page_id' );
		if( $add_listings_page && ( $add_listings_page == $post->ID ) ) {
			ob_start();
			$WCFM->template->get_template( 'products-popup/wcfm-view-product-popup.php' );
			$content .= '<div id="wcfm_listing_product_popup_wrapper">' . ob_get_clean() . '</div>';
		} elseif( $job_dashboard_page && ( $job_dashboard_page == $post->ID ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'edit' ) ) {
			ob_start();
			$WCFM->template->get_template( 'products-popup/wcfm-view-product-popup.php' );
			$content .= '<div id="wcfm_listing_product_popup_wrapper">' . ob_get_clean() . '</div>';
		}
		
		return $content;
	}
	
	/**
   * Toolset Types - Products Type wise field group settings - 3.1.7
   */
	function wcfm_toolset_types_settings() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-toolset-types-settings.php' );
	}
	
	/**
   * Toolset Types - Products Manage General options - 2.5.0
   */
  function wcfm_toolset_types_product_manage_fields( ) {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-toolset-types-products-manage.php' );
	}
	
	/**
   * Toolset Types - Articles Manage General options - 4.2.3
   */
  function wcfm_toolset_types_article_manage_fields( ) {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-toolset-types-articles-manage.php' );
	}
	
	/**
   * MapPress - Products Manage General options
   */
  function wcfm_mappress_product_manage_fields( ) {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-mappress-products-manage.php' );
	}
	
	/**
   * Toolset Types - User Profile Fields - 3.0.1
   */
  function wcfm_toolset_types_user_profile_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-toolset-types-user-profile.php' );
	}
	
	/**
   * Toolset Types - User Profile Fields View - 3.5.3
   */
	function wcfm_toolset_types_user_profile_fields_view( $user_id ) {
		global $WCFM, $WCFMu;
		$vendor_user = get_userdata( $user_id );
		if ( !is_object($vendor_user) ){
			$vendor_user = new stdClass();
			$vendor_user->ID = 0;
		}
		$current_user_roles = isset( $vendor_user->roles ) ? $vendor_user->roles : apply_filters( 'wcfm_allwoed_user_roles', array( 'vendor', 'dc_vendor', 'seller', 'customer', 'disable_vendor', 'wcfm_vendor' ) );
		$current_user_roles = array_values( $current_user_roles );
		$user_role = array_shift( $current_user_roles );
		include_once( WPCF_EMBEDDED_ABSPATH . '/includes/usermeta-post.php' );
		$field_groups = wpcf_admin_usermeta_get_groups_fields( );
		if( !empty( $field_groups )) {
			foreach( $field_groups as $field_group_index => $field_group ) {
				
				// User Role Based Fields
				$for_users = wpcf_admin_get_groups_showfor_by_group($field_group['id']);
				if ( count( $for_users ) != 0 ) {
					if ( !in_array( $user_role, $for_users ) ) {
						continue;
					}
				}
						
				//If Access plugin activated
				if ( function_exists( 'wpcf_access_register_caps' ) ) {
					//If user can't view own profile fields
					if ( !current_user_can( 'view_own_in_profile_' . $field_group['slug'] ) ) {
						continue;
					}
					//If user can modify current group in own profile
					if ( !current_user_can( 'modify_own_' . $field_group['slug'] ) ) {
						continue;
					}
				}
				
				if( version_compare( TYPES_VERSION, '3.0', '>=' ) || version_compare( TYPES_VERSION, '3.0.1', '>=' ) ) {
					$field_group_load = Toolset_Field_Group_User_Factory::load( $field_group['slug'] );
				} else {
					$field_group_load = Types_Field_Group_User_Factory::load( $field_group['slug'] );
				}
				if( null === $field_group_load ) continue;
				
				$wcfm_is_allowed_toolset_field_group = apply_filters( 'wcfm_is_allow_user_toolset_field_group', true, $field_group_index, $field_group, $user_id );
				if( !$wcfm_is_allowed_toolset_field_group ) continue;
				
				if ( !empty( $field_group['fields'] ) ) { 
					echo "<h2>" . $field_group['name'] . "</h2><div class=\"wcfm_clearfix\"></div>";
					
					if ( !empty( $field_group['fields'] ) ) {
						foreach( $field_group['fields'] as $field_group_field ) {
							
							$wcfm_is_allowed_toolset_field = apply_filters( 'wcfm_is_allow_user_toolset_field', true, $field_group_field, $user_id );
				  		if( !$wcfm_is_allowed_toolset_field ) continue;
							
							// Field Value
							$field_value = '';
							if( isset( $field_group_field['data'] ) && isset( $field_group_field['data']['user_default_value'] ) ) $field_value = $field_group_field['data']['user_default_value'];
							if( $user_id ) $field_value = get_user_meta( $user_id, $field_group_field['meta_key'], true );
							
							// Paceholder
							$field_paceholder = '';
							if( isset( $field_group_field['data'] ) && isset( $field_group_field['data']['placeholder'] ) ) $field_paceholder = $field_group_field['data']['placeholder'];
							
							// Is Required
							$custom_attributes = array();
							if( isset( $field_group_field['data'] ) && isset( $field_group_field['data']['validate'] ) && isset( $field_group_field['data']['validate']['required'] ) && $field_group_field['data']['validate']['required'] ) $custom_attributes = array( 'required' => 1 );
							if( isset( $field_group_field['data'] ) && isset( $field_group_field['data']['validate'] ) && isset( $field_group_field['data']['validate']['required'] ) && $field_group_field['data']['validate']['required'] && isset( $field_group_field['data']['validate']['message'] ) && $field_group_field['data']['validate']['message'] ) $custom_attributes['required_message'] = $field_group_field['data']['validate']['message'];
							
							// For Multi-line Fields
							if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
								$field_value = array();
								$field_value_repetatives = (array) get_user_meta( $user_id, $field_group_field['meta_key'] );
								if( !empty( $field_value_repetatives ) ) {
									foreach( $field_value_repetatives as $field_value_repetative ) {
										$field_value[] = array( 'field' => $field_value_repetative );
									}
								}
							}
							
							// Field show befor filtr
							$wcfm_is_allowed_toolset_field_show = apply_filters( 'wcfm_is_allow_user_toolset_field_show', true, $field_group_field, $field_value, $user_id );
				  		if( !$wcfm_is_allowed_toolset_field_show ) continue;
				  		
				  		$field_type = 'text';
				  		$attributes = array();
				  		if( is_array( $field_value ) ) {
				  			$field_value_content = '';
				  			foreach( $field_value as $field_value_data ) {
				  				if( $field_group_field['type'] == 'colorpicker' ) {
				  					$field_type = 'html';
				  					$attributes = array( 'style' => 'width: 60%; padding: 5px; display: inline-block;' );
				  					$field_value_content .= '<span style="width: 15px; height: 15px; display: inline-block; margin-right: 10px; background-color: ' . implode( ',', $field_value_data ) . ';" class="text_tip" data-tip="'.implode( ',', $field_value_data ).'"></span>';
				  				} else {
										if( $field_value_content ) $field_value_content .= ', ';
										$field_value_content .= implode( ',', $field_value_data );
									}
				  			}
				  			$field_value = $field_value_content;
				  		}
				  		if( $field_group_field['type'] == 'date' ) {
				  			if($field_value) $field_value = date( wc_date_format(), $field_value ); 
				  		}
							if( !$field_value ) $field_value = '&ndash;';
							$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'attributes' => $attributes, 'custom_attributes' => $custom_attributes, 'placeholder' => $field_paceholder, 'hints' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => $field_type, 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value ) ) );
						}
					}
				}
			}
		}
	}
	
	/**
   * Toolset Types - Taxonomy Fields - 3.0.2
   */
  function wcfm_toolset_types_taxonomy_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-toolset-types-taxonomy.php' );
	}
	
	/**
   * ACF - Products Manage General options - 3.0.4
   */
  function wcfm_acf_product_manage_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-acf-products-manage.php' );
	}
	
	/**
   * ACF Pro - Products Manage General options - 3.3.7
   */
  function wcfm_acf_pro_product_manage_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-acf-pro-products-manage.php' );
	}
	
	/**
   * ACF - Articles Manage General options - 4.2.3
   */
  function wcfm_acf_article_manage_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-acf-articles-manage.php' );
	}
	
	/**
   * ACF Pro - Articles Manage General options - 4.2.3
   */
  function wcfm_acf_pro_article_manage_fields() {
		global $WCFMu;
	  $WCFMu->template->get_template( 'thirdparty/wcfmu-view-acf-pro-articles-manage.php' );
	}
	
	/**
   * Address Geocoder - Products Manage General options
   */
  function wcfm_address_geocoder_product_manage_fields( ) {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-address-geocoder-products-manage.php' );
	}
	
	/**
   * WC Deposits - Products Manage General options
   */
  function wcfm_wc_deposits_product_manage_fields( ) {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-deposits-products-manage.php' );
	}
	
	/**
	 * WC PDF Vouchers - Product manage General Options
	 */
	function wcfm_wc_pdf_vouchers_product_manage_fields() {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-pdf-vouchers-products-manage.php' );
	}
	
	/**
	 * WC Tabs Manager - Product manager Genaral Options
	 */
	function wcfm_wc_tabs_manager_product_manage_fields() {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-tabs-manager-products-manage.php' );
	}
	
	/**
	 * WC Warranty - Product manager Genaral Options
	 */
	function wcfm_wc_warranty_product_manage_fields() {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-warranty-products-manage.php' );
	}
	
	/**
	 * WC Waitlist - Product manager Genaral Options
	 */
	function wcfm_wc_waitlist_product_manage_fields() {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-waitlist-products-manage.php' );
	}
	
	/**
	 * WC PDF Vouchers - Product manage Variation Options
	 */
	function wcfm_wc_pdf_vouchers_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
		global $WCFM, $WCFMu, $woo_vou_voucher;
		
		$voucher_options 	= array( '' => __( 'Select a PDF template.', 'woovoucher' ) );
		$multiple_voucher_options = array();
		$voucher_data 		= $woo_vou_voucher->woo_vou_get_vouchers();
		foreach ( $voucher_data as $voucher ) {
			if( isset( $voucher['ID'] ) && !empty( $voucher['ID'] ) ) { // Check voucher id is not empty
				$voucher_options[$voucher['ID']] = $voucher['post_title'];
				$multiple_voucher_options[$voucher['ID']] = $voucher['post_title'];
			}
		}
		
		$voucher_delivery_opt 	= array(
																		'default' 	=> __( 'Default', 'woovoucher' ), 
																		'email' 	=> __( 'Email', 'woovoucher' ), 
																		'offline' 	=> __( 'Offline', 'woovoucher' )
																	);
		
		
		$wcfmu_variation_fields = array(
																		"_woo_vou_variable_pdf_template" => array('label' => __('PDF Template', 'woovoucher') , 'type' => 'select', 'options' => $multiple_voucher_options, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_ele wcfm_half_ele_title variable variable-subscription variation_downloadable_ele'),
																		"_woo_vou_variable_voucher_delivery" => array('label' => __('Voucher Delivery', 'woovoucher') , 'type' => 'select', 'options' => $voucher_delivery_opt, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_ele wcfm_half_ele_title variable variable-subscription variation_downloadable_ele'),
																		"_woo_vou_variable_codes" => array('label' => __('Voucher Codes', 'woovoucher') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_ele variable variable-subscription variation_downloadable_ele', 'hints' => __( 'If you have a list of Voucher Codes you can copy and paste them in to this option. Make sure, that they are comma separated.', 'woovoucher' ) ),
																		"_woo_vou_variable_vendor_address" => array('label' => __('Vendor Address', 'woovoucher') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_ele wcfm_full_ele variable variable-subscription variation_downloadable_ele', 'hints' => __( 'Here you can enter the complete Vendor\'s address. This will be displayed on the PDF document sent to the customers so that they know where to redeem this Voucher. Limited HTML is allowed.', 'woovoucher' ) ),
																		);
		$variation_fileds = array_merge( $variation_fileds, $wcfmu_variation_fields );
		
		return $variation_fileds;
	}
	
	/**
	 * WC PDF Vouchers - Generate Voucher code form HTML
	 */
	function wcfm_generate_voucher_code_html() {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-pdf-vouchers-generate-codes.php' );
		die;
	}
	
	/**
   * Product Manage WooCommerce Box Office Fields - General
   */
	function wcfm_wc_box_office_product_manage_fields_general( $general_fields, $product_id, $product_type ) {
		global $WCFM;
		
		$_ticket = ( get_post_meta( $product_id, '_ticket', true) == 'yes' ) ? 'yes' : '';
		
		$general_fields = array_slice($general_fields, 0, 1, true) + 
													array(
														"_ticket" => array( 'desc' => __( 'Ticket', 'woocommerce-box-office') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele wcfm_half_ele_checkbox simple variable non-variable-subscription non-job_package non-resume_package non-auction non-redq_rental non-appointment non-accommodation-booking', 'desc_class' => 'wcfm_title wcfm_ele virtual_ele_title checkbox_title simple variable non-variable-subscription non-job_package non-resume_package non-auction non-redq_rental non-appointment non-accommodation-booking', 'value' => 'yes', 'dfvalue' => $_ticket),
														) +
											array_slice($general_fields, 1, count($general_fields) - 1, true) ;
		
		return $general_fields;
	}
	
	/**
   * WooCommerce Box Office - Products Manage General options
   */
  function wcfm_wc_box_office_product_manage_fields( ) {
		global $WCFMu;
		$WCFMu->template->get_template( 'thirdparty/wcfmu-view-wc-box-office-products-manage.php' );
	}
	
	/**
	 * WC Rental Pro Product Inventory Manage - 2.4.3
	 */
	function wcfm_wcrental_product_inventory_manage( $stock_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMu;
		$redq_inventory_products = array();
		
		$inventory_taxonomies = array( 'rnb_categories', 'pickup_location', 'dropoff_location', 'resource', 'person', 'deposite', 'attributes', 'features' );
		$inventory_taxonomie_elements = array(); 
		$inventory_taxonomie_elements['unique_name'] = array( 'label' => __('Unique product model', 'redq-rental'), 'placeholder' => __('Unique product model', 'redq-rental'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele redq_rental redq_rental_unique_name', 'label_class' => 'wcfm_title redq_rental redq_rental_unique_name', 'hints' => __( 'Hourly price will be applicabe if booking or rental days min 1day', 'redq-rental' ) );
		foreach( $inventory_taxonomies as $inventory_taxonomy ) {
			//$inventory_taxonomy_terms   = get_terms( $inventory_taxonomy, array( 'hide_empty' => false ) );
			$inventory_taxonomy_terms = new WP_Term_Query( array( 'taxonomy' => $inventory_taxonomy, 'hide_empty' => false ) );
			$inventory_taxonomy_options = array( '' => __('Set', 'redq-rental') . ' ' . str_replace( '_', ' ',  str_replace( 'rnb_', '', $inventory_taxonomy ) ) );
			if ( ! empty( $inventory_taxonomy_terms->terms ) ) {
				foreach( $inventory_taxonomy_terms->terms as $inventory_taxonomy_term ) {
					$inventory_taxonomy_options[$inventory_taxonomy_term->slug] = $inventory_taxonomy_term->name;
				}
			}
			$inventory_taxonomie_elements[$inventory_taxonomy] = array( 'label' => ucfirst( str_replace( '_', ' ', str_replace( 'rnb_', '', $inventory_taxonomy ) ) ), 'placeholder' => __('Set', 'redq-rental') . ' ' . $inventory_taxonomy, 'attributes' => array( 'style' => 'width: 60%;' ), 'type' => 'select', 'options' => $inventory_taxonomy_options, 'class' => 'wcfm-select wcfm_ele redq_rental', 'label_class' => 'wcfm_title redq_rental' );
		}
		$inventory_taxonomie_elements['inventory_id'] = array( 'type' => 'hidden' );
		
		$redq_rental_availability = array();
		
		// Stored Inventory Values
		if( $product_id ) {
			$redq_inventory_products = array();
			$resource_identifier = get_post_meta( $product_id, 'resource_identifier', true );
			$redq_inventory_child_ids = get_post_meta( $product_id, 'inventory_child_posts', true );
			$redq_inventory_unique_names = get_post_meta( $product_id, 'redq_inventory_products_quique_models', true );
			if( !empty( $redq_inventory_child_ids ) ) {
				foreach( $redq_inventory_child_ids as $inventory_index => $redq_inventory_child_id ) {
					$redq_inventory_products[$inventory_index]['inventory_id'] = $redq_inventory_child_id;
					$redq_inventory_products[$inventory_index]['unique_name'] = isset( $redq_inventory_unique_names[$inventory_index] ) ? $redq_inventory_unique_names[$inventory_index] : '';
					// Taxonomies
					foreach( $inventory_taxonomies as $inventory_taxonomy ) {
						$inventory_taxonomy_values = get_the_terms( $redq_inventory_child_id, $inventory_taxonomy );
						if( !empty( $inventory_taxonomy_values ) ) {
							foreach( $inventory_taxonomy_values as $inventory_taxonomy_value ) {
								$redq_inventory_products[$inventory_index][$inventory_taxonomy] = $inventory_taxonomy_value->slug;
							}
						}
					}
					$redq_inventory_products[$inventory_index]['redq_rental_availability'] = (array) get_post_meta( $redq_inventory_child_id, 'redq_rental_availability', true );
				}
			}
		}
		
		$inventory_taxonomie_elements["redq_rental_availability"] =   array( 'label' => __('Product Availabilities', 'wc-frontend-manager') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele redq_rental_availability redq_rental', 'label_class' => 'wcfm_title redq_rental', 'desc' => __( 'Please select the date range to be disabled for the product.', 'wc-frontend-manager' ), 'desc_class' => 'avail_rules_desc', 'value' => $redq_rental_availability, 'options' => array(
																																					"type" => array('label' => __('Type', 'wc-frontend-manager'), 'type' => 'select', 'options' => array( 'custom_date' => __( 'Custom Date', 'wc-frontend-manager' )), 'class' => 'wcfm-select wcfm_ele wcfm_half_ele redq_rental', 'label_class' => 'wcfm_title wcfm_half_ele_title redq_rental' ),
																																					"from" => array('label' => __('From', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_datepicker wcfm_half_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title' ),
																																					"to" => array('label' => __('To', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_datepicker wcfm_half_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title' ),
																																					"rentable" => array('label' => __('Bookable', 'wc-frontend-manager'), 'type' => 'select', 'options' => array( 'no' => __('NO', 'wc-frontend-manager') ), 'class' => 'wcfm-select wcfm_ele wcfm_half_ele redq_rental', 'label_class' => 'wcfm_title wcfm_half_ele_title' ),
																																					)	);
		
		$inventory_taxonomie_multy_elements = array( "redq_inventory_products" => array('label' => __('Inventory management', 'redq-rental') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele redq_rental', 'label_class' => 'wcfm_title redq_rental', 'value' => $redq_inventory_products, 'options' => $inventory_taxonomie_elements ) );
		
		$stock_fields = array_merge( $stock_fields, $inventory_taxonomie_multy_elements );
		
		return $stock_fields;
	}
	
	function wcfm_wcrental_pro_hidden_order_itemmeta( $hidden_metas ) {
		
		$hidden_metas[] = 'pickup_hidden_datetime';
		$hidden_metas[] = 'return_hidden_datetime';
		$hidden_metas[] = 'return_hidden_days';
		
		return $hidden_metas; 
	}
	
	/**
   * Handle Rental Request Quote Details Status Update
   */
  public function wcfm_modify_rental_quote_status() {
  	global $WCFM, $WCFMu;
  	
  	$quote_id     = $_POST['quote_id'];
  	$quote_status = $_POST['quote_status'];
  	$quote_price  = $_POST['quote_price'];
  	
  	$post_id      = $quote_id;
  	$post         = get_post( $post_id );
  	
  	if( isset($_POST['quote_status']) && ( $_POST['quote_status'] !== $post->post_status ) ) {
  		$my_post = array(
					'ID'           => $quote_id,
					'post_status'  => $quote_status,
			);
			wp_update_post( $my_post );
  
      // send email

      $form_data = json_decode( get_post_meta($post_id, 'order_quote_meta', true), true );



      $from_name = '';
      $from_email = '';
      $from_phone = '';
      $product_id = '';
      $to_email = '';
      $to_author_id = '';


      $message_from_sender_html = '';

      foreach ($form_data as $key => $meta) {
        /**
         * Get the post author_id, author_email, prodct_id
         */
        if( isset( $meta['name'] ) && $meta['name'] === 'add-to-cart' ) {
          $product_id = $meta['value'];
          $to_author_id = get_post_field( 'post_author', $product_id );
          $to_email = get_the_author_meta( 'user_email', $to_author_id);
        }

        /**
         * Get the customer name, email, phone, message
         */
        else if( isset( $meta['forms'] ) ) {
          $forms = $meta['forms'];
          foreach ($forms as $k => $v) {
            $message_from_sender_html .= "<p>".$k . " : " . $v . "</p>";
            if($k === 'email') {
              $from_email = $v;
            }
            if($k === 'name') {
              $from_name = $v;
            }

          }
        }
      }

      switch ($quote_status) {
        case 'quote-accepted':
          // send email to the customer

          $prodct_id = get_post_meta( $post->ID, 'add-to-cart', true );
          $from_author_id = get_post_field( 'post_author', $prodct_id );
          $from_email = get_the_author_meta( 'user_email', $from_author_id);
          $from_name = get_the_author_meta( 'user_nicename', $from_author_id);

          // To info
          $to_author_id = get_post_field( 'post_author', $post->ID );
          $to_email = get_the_author_meta( 'user_email', $to_author_id);

          $subject = __( "Congratulations! Your quote request has been accepted", 'wc-frontend-manager-ultimate' );;
          // $reply_message = $_POST['add-quote-message'];
          $data_object = array(
            // 'reply_message' => $reply_message,
            'quote_id'         => $quote_id,
          );

          // Send the mail to the customer
          $email = new RnB_Email();
          $email->quote_accepted_notify_customer( $to_email, $subject, $from_email, $from_name, $data_object );
          break;

        default:
          // send email to the customer

          $prodct_id = get_post_meta( $post->ID, 'add-to-cart', true );
          $from_author_id = get_post_field( 'post_author', $prodct_id );
          $from_email = get_the_author_meta( 'user_email', $from_author_id);
          $from_name = get_the_author_meta( 'user_nicename', $from_author_id);

          // To info
          $to_author_id = get_post_field( 'post_author', $post->ID );
          $to_email = get_the_author_meta( 'user_email', $to_author_id);

          $subject = __( "Your quote request status has been updated", 'wc-frontend-manager-ultimate' );
          // $reply_message = $_POST['add-quote-message'];
          $data_object = array(
            // 'reply_message' => $reply_message,
            'quote_id'         => $quote_id,
          );

          // Send the mail to the customer
          $email = new RnB_Email();
          $email->quote_status_update_notify_customer( $to_email, $subject, $from_email, $from_name, $data_object );
          break;
      }

    }

    if ( isset( $_POST['quote_price'] ) ) {
      update_post_meta($post_id, '_quote_price', $quote_price);
    }
    
    echo '{"status": true, "message": "'. __( 'Quote request status has been updated.', 'wc-frontend-manager-ultimate' ) .'"}';
  	
		die;
  }
  
  /**
   * Send Quote Request Message
   */
  function wcfm_rental_quote_message() {
  	global $WCFM, $WCFMu;
  	
  	if( isset( $_POST['quote_id'] ) ) {
			$quote_id     = $_POST['quote_id'];
			
			$post_id      = $quote_id;
			$post         = get_post( $post_id );
			
			global $current_user;

      $time = current_time('mysql');
      
      if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				//check ip from share internet
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				//to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

      $data = array(
        'comment_post_ID' => $post->ID,
        'comment_author' => $current_user->user_nicename,
        'comment_author_email' => $current_user->user_email,
        'comment_author_url' => $current_user->user_url,
        'comment_content' => $_POST['note'],
        'comment_type' => 'quote_message',
        'comment_parent' => 0,
        'user_id' => $current_user->ID,
        'comment_author_IP' => $ip,
        'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
        'comment_date' => $time,
        'comment_approved' => 1,
      );

      $comment_id = wp_insert_comment($data);


      // send email to the customer
      $prodct_id = get_post_meta( $post->ID, 'add-to-cart', true );
      $from_author_id = get_post_field( 'post_author', $prodct_id );
      $from_email = get_the_author_meta( 'user_email', $from_author_id);
      $from_name = get_the_author_meta( 'user_nicename', $from_author_id);

      // To info
      $to_author_id = get_post_field( 'post_author', $post->ID );
      $to_email = get_the_author_meta( 'user_email', $to_author_id);

      $quote_id = $post->ID;

      $subject = __( "New reply for your quote request", 'wc-frontend-manager-ultimate' );
      $reply_message = $_POST['note'];
      $data_object = array(
        'reply_message' => $reply_message,
        'quote_id'      => $quote_id,
      );

      // Send the mail to the customer
      $email = new RnB_Email();
      $email->owner_reply_message( $to_email, $subject, $from_email, $from_name, $data_object );
			
		}
  	
  	die;
  }
}