<?php

/**
 * WCFMu plugin core
 *
 * Marketplace WC Product Vendors Support
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   2.1.0
 */
 
class WCFMu_WCPVendors {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() );
    	
    	// Manage Vendor Product Import Vendor Association - 2.4.2
			add_action( 'woocommerce_product_import_inserted_product_object', array( &$this, 'wcpvendors_product_import_vendor_association' ), 10, 2 );
    	
			// Orders Menu
			add_filter( 'wcfmu_orders_menus', array( &$this, 'wcpvendors_orders_menu' ) );
			
			// Orders Filter
			add_action( 'before_wcfm_orders', array( &$this, 'wcpvendors_orders_filter' ) );
			
			// Order actions
			add_filter( 'wcpvendors_orders_actions', array( &$this, 'wcfmu_wcpvendors_orders_actions' ), 10, 4 );
			
			// Order Invoice
			add_filter( 'wcfm_order_details_shipping_line_item_invoice', array( &$this, 'wcpvendors_is_allow_order_details_shipping_line_item_invoice' ) );
			
			// Order Notes
			add_filter( 'wcfm_order_notes', array( &$this, 'wcpvendors_order_notes' ), 10, 2 );
			
			// WCFMu Report Menu
			add_filter( 'wcfm_reports_menus', array( &$this, 'wcpvendors_reports_menus' ), 100 );
			
			// Report Filter
			add_filter( 'woocommerce_reports_get_order_report_data_args', array( &$this, 'wcpvendors_reports_get_order_report_data_args'), 100 );
			add_filter( 'wcfm_report_low_in_stock_query_from', array( &$this, 'wcpvendors_report_low_in_stock_query_from' ), 100, 3 );
			
			// Booking Filter products for specific vendor
			//add_filter( 'get_booking_products_args', array( $this, 'wcpvendors_filter_resources' ) );
			
			// Filter resources for specific vendor - Product Vendors bug
    	add_filter( 'get_booking_resources_args', array( $this, 'wcpvendors_filter_resources' ), 20 );
			
			// Booking filter products from booking calendar
			add_filter( 'woocommerce_bookings_in_date_range_query', array( $this, 'wcpvendors_filter_bookings_calendar' ) );
			
			// Appointment Filter
			add_filter( 'wcfm_wca_include_appointments', array( &$this, 'wcpvendors_wca_include_appointments' ) );
			
			// Appointment filter products from appointment calendar
			add_filter( 'woocommerce_appointments_in_date_range_query', array( $this, 'wcpvendors_filter_appointments_calendar' ) );
			
			// Appointment Staffs args
			add_filter( 'get_appointment_staff_args', array( &$this, 'wcpvendors_filter_appointment_staffs' ) );
			
			// Appointment Manage Staff
			add_action( 'wcfm_staffs_manage', array( &$this, 'wcpvendors_wcfm_staffs_manage' ) );
			
			// Auctions Filter
			add_filter( 'wcfm_valid_auctions', array( &$this, 'wcpvendors_wcfm_valid_auctions' ) );
			
			// Rental Request Quote Filter
			add_filter( 'wcfm_rental_include_quotes', array( &$this, 'wcpvendors_rental_include_quotes' ) );
			
			// Profile Update
			add_action( 'wcfm_profile_update', array( &$this, 'wcpvendors_profile_update' ), 10, 2 );
    }
  }
  
  // Product Vendor association on Product Import - 2.4.2
  function wcpvendors_product_import_vendor_association( $product_obj ) {
  	global $WCFM, $WCFMu;
  	
  	$new_product_id = $product_obj->get_id();
  	
  	if ( WC_Product_Vendors_Utils::auth_vendor_user() ) {

			// check post type to be product
			if ( 'product' === get_post_type( $new_product_id ) ) {

				// automatically set the vendor term for this product
				wp_set_object_terms( $new_product_id, $this->vendor_id, WC_PRODUCT_VENDORS_TAXONOMY );

				// set visibility to catalog/search
				update_post_meta( $new_product_id, '_visibility', 'visible' );
			}
		}
  }
  
  // Orders Menu
  function wcpvendors_orders_menu( $menus ) {
  	return array();
  }
  
  // Orders Filter
  function wcpvendors_orders_filter() {
  	global $WCFM, $WCFMu, $wpdb, $wp_locale;
  	?>
  	<h2><?php _e('Orders Listing', 'wc-frontend-manager' ); ?></h2>
  	<?php
  	$months = $wpdb->get_results( $wpdb->prepare( '
			SELECT DISTINCT YEAR( commission.order_date ) AS year, MONTH( commission.order_date ) AS month
			FROM ' . WC_PRODUCT_VENDORS_COMMISSION_TABLE . ' AS commission
			WHERE commission.vendor_id = %s
			ORDER BY commission.order_date DESC
		', $this->vendor_id ) );

		$month_count = count( $months );

		if ( ! $month_count || ( 1 === $month_count && 0 === $months[0]->month ) ) {
			return;
		}

		$m = isset( $_REQUEST['m'] ) ? (int) $_REQUEST['m'] : 0;
		?>
		<div class="wcfm_orders_filter_wrap wcfm_filters_wrap">
			<select name="m" id="filter-by-date" style="width: 150px;">
				<option<?php selected( $m, 0 ); ?> value='0'><?php esc_html_e( 'Show all dates', 'wc-frontend-manager-ultimate' ); ?></option>
				<?php
				foreach ( $months as $arc_row ) {
					if ( 0 === $arc_row->year ) {
						continue;
					}
	
					$month = zeroise( $arc_row->month, 2 );
					$year  = $arc_row->year;
	
					if ( '00' === $month || '0' === $year ) {
						continue;
					}
	
					printf( "<option %s value='%s'>%s</option>\n",
						selected( $m, $year . $month, false ),
						esc_attr( $arc_row->year . $month ),
						/* translators: 1: month name, 2: 4-digit year */
						sprintf( __( '%1$s %2$d', 'wc-frontend-manager-ultimate' ), $wp_locale->get_month( $month ), $year )
					);
				}
				?>
			</select>
			
			<select name="commission-status" id="commission-status" style="width: 150px;">
				<option value=''><?php esc_html_e( 'Show all', 'wc-frontend-manager-ultimate' ); ?></option>
				<option value="unpaid"><?php esc_html_e( 'Unpaid', 'wc-frontend-manager-ultimate' ); ?></option>
				<option value="paid"><?php esc_html_e( 'Paid', 'wc-frontend-manager-ultimate' ); ?></option>
				<option value="void"><?php esc_html_e( 'Void', 'wc-frontend-manager-ultimate' ); ?></option>
			</select>
		</div>
  	<?php
  }
  
  // Order Actions
  public function wcfmu_wcpvendors_orders_actions( $actions, $user_id, $the_order, $order ) {
  	global $WCFM, $WCFMu, $wpdb;
  	
  	if( !$wcfm_allow_shipping_tracking = apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) {
			return $actions;
		}
  	
  	$vendor_id   = $this->vendor_id;
		$valid_items = array();
  	
		// See if product needs shipping 
		$needs_shipping = true; 

		$status = WC_Product_Vendors_Utils::get_fulfillment_status( $order->order_item_id );
		$product = wc_get_product( $order->product_id );
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product );
  	
		if ( $status && 'fulfilled' === $status ) {
			//$actions .= '<a class="wcfm-action-icon" href="#"><span class="fa fa-ship text_tip" data-tip="' . esc_attr__( 'Fulfilled', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} elseif ( $needs_shipping ) {
			$actions .= '<a class="wcfm_wcpvendors_order_mark_fulfilled wcfm-action-icon" href="#" data-productid="' . $order->product_id . '" data-orderid="' . $order->order_id . '" data-orderitemid="' . $order->order_item_id . '"><span class="fa fa-truck text_tip" data-tip="' . esc_attr__( 'Mark Fulfilled', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
		} 
		
  	
  	return $actions;
  }
  
  // Order Details Shipping Line Item Invoice
  function wcpvendors_is_allow_order_details_shipping_line_item_invoice( $allow ) {
  	//$allow = false;
  	return $allow;
  }
  
  // Order Notes
  function wcpvendors_order_notes( $notes, $order_id ) {
  	$order    = wc_get_order( $order_id );
		$notes = $order->get_customer_order_notes();
  	return $notes;
  }
  
  // Filter Comment User as Vendor
  public function filter_wcfm_vendors_comment( $commentdata, $order ) {
		$user_id = get_current_user_id();

		$commentdata[ 'user_id' ]              = $user_id;
		//$commentdata[ 'comment_author' ]       = WCV_Vendors::get_vendor_shop_name( $user_id );
		//$commentdata[ 'comment_author_url' ]   = WCV_Vendors::get_vendor_shop_page( $user_id );
		$commentdata[ 'comment_author_email' ] = wp_get_current_user()->user_email;

		return $commentdata;
	}
	
	/**
	 * WCFMu WCV Reports Menu
	 */
	function wcpvendors_reports_menus( $reports_menus ) {
		global $WCFM, $WCFMu;
		
		unset($reports_menus['coupons-by-date']);
		return $reports_menus;
	}
	
	// Report Data args filter as per vendor
  function wcpvendors_reports_get_order_report_data_args( $args ) {
  	global $WCFM, $wpdb, $_POST, $wp;
  	
  	if ( !isset( $wp->query_vars['wcfm-reports-sales-by-product'] ) ) return $args;
  	if( $args['query_type'] != 'get_results' ) return $args;
  	
		$vendor_id   = $this->vendor_id;
		$products = array(0);
  	
  	$sql = 'SELECT * FROM ' . WC_PRODUCT_VENDORS_COMMISSION_TABLE . ' AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND `vendor_id` = {$vendor_id}";
		$data_items = $wpdb->get_results( $sql );
		
		if( !empty($data_items) ) {
			foreach( $data_items as $data_item ) {
				if ( ! empty( $data_item->variation_id ) ) $products[] = $data_item->variation_id;
				$products[] = $data_item->product_id;
			}
		}
		
		//$args['order_types'] = wc_get_order_types( 'sales-reports' );
		$args['where'][] = array( 'key' => 'order_item_meta__product_id.meta_value', 'operator' => 'in', 'value' => $products );
  	
  	return $args;
  }
	
	// Report Vendor Filter
  function wcpvendors_report_low_in_stock_query_from( $query_from, $stock, $nostock ) {
  	global $WCFM, $wpdb, $_POST;
  	
  	$vendor_product_ids = WC_Product_Vendors_Utils::get_vendor_product_ids();
  	
  	$query_from = "FROM {$wpdb->posts} as posts
			INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$stock}'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '{$nostock}'
			AND posts.ID IN ( '" . implode( "','", $vendor_product_ids ) . "' )
		";
		
		return $query_from;
  }
  
  // Filter resources for specific vendor - Fixing Product Vendors bug
  function wcpvendors_filter_resources( $query_args ) {
		unset($query_args['post__in']);
		$query_args['author'] = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );	
  	return $query_args;
  }
  
  /**
	 * Filter products booking calendar to specific vendor
	 *
	 * @since 2.2.6
	 * @param array $booking_ids booking ids
	 * @return array
	 */
	public function wcpvendors_filter_bookings_calendar( $booking_ids ) {
		$filtered_ids = array();

		if ( WC_Product_Vendors_Utils::is_vendor() ) {
			$product_ids = WC_Product_Vendors_Utils::get_vendor_product_ids();

			if ( ! empty( $product_ids ) ) {
				foreach ( $booking_ids as $id ) {
					$booking = get_wc_booking( $id );

					if ( in_array( $booking->product_id, $product_ids ) ) {
						$filtered_ids[] = $id;
					}
				}

				$filtered_ids = array_unique( $filtered_ids );

				return $filtered_ids;
			} else {
				return array();
			}
		}

		return $booking_ids;
	}
	
	/**
   * WC Product Vendors Appointments
   */
  function wcpvendors_wca_include_appointments( ) {
  	global $WCFM, $WCFMu, $wpdb, $_POST;
  	
  	$vendor_id   = $this->vendor_id;
		$vendor_products = WC_Product_Vendors_Utils::get_vendor_product_ids();
  	
		if( empty($vendor_products) ) return array(0);
		
  	$query = "SELECT ID FROM {$wpdb->posts} as posts
							INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
							WHERE 1=1
							AND posts.post_type IN ( 'wc_appointment' )
							AND postmeta.meta_key = '_appointment_product_id' AND postmeta.meta_value in (" . implode(',', $vendor_products) . ")";
		
		$vendor_appointments = $wpdb->get_results($query);
		if( empty($vendor_appointments) ) return array(0);
		$vendor_appointments_arr = array();
		foreach( $vendor_appointments as $vendor_appointment ) {
			$vendor_appointments_arr[] = $vendor_appointment->ID;
		}
		if( !empty($vendor_appointments_arr) ) return $vendor_appointments_arr;
		return array(0);
  }
  
  /**
	 * Filter products appointment calendar to specific vendor
	 *
	 * @since 2.4.0
	 * @param array $appointment_ids appointment ids
	 * @return array
	 */
	public function wcpvendors_filter_appointments_calendar( $appointment_ids ) {
		$filtered_ids = array();

		if ( WC_Product_Vendors_Utils::is_vendor() ) {
			$product_ids = WC_Product_Vendors_Utils::get_vendor_product_ids();

			if ( ! empty( $product_ids ) ) {
				foreach ( $appointment_ids as $id ) {
					$appointment = get_wc_appointment( $id );

					if ( in_array( $appointment->product_id, $product_ids ) ) {
						$filtered_ids[] = $id;
					}
				}

				$filtered_ids = array_unique( $filtered_ids );

				return $filtered_ids;
			} else {
				return array();
			}
		}

		return $appointment_ids;
	}
	
	// Product Vendors Filter Staffs
	function wcpvendors_filter_appointment_staffs( $args ) {
		$args['meta_key'] = '_wcfm_vendor';
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// Product Vendors Appointment Staff Manage
	function wcpvendors_wcfm_staffs_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
	
	// Product Vendors Valid Auction
	function wcpvendors_wcfm_valid_auctions( $valid_actions ) {
		global $WCFM, $WCFMu;
		
		$valid_actions = WC_Product_Vendors_Utils::get_vendor_product_ids();
		if( empty($valid_actions) ) return array(0);
		
		return $valid_actions; 
	}
	
	/**
   * WC Product Vendors Rental Qotes
   */
  function wcpvendors_rental_include_quotes( ) {
  	global $WCFM, $wpdb, $_POST;
  	
		$vendor_products = WC_Product_Vendors_Utils::get_vendor_product_ids();
  	
		if( empty($vendor_products) ) return array(0);
		
  	$query = "SELECT ID FROM {$wpdb->posts} as posts
							INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
							WHERE 1=1
							AND posts.post_type IN ( 'request_quote' )
							AND postmeta.meta_key = 'add-to-cart' AND postmeta.meta_value in (" . implode(',', $vendor_products) . ")";
		
		$vendor_quotes = $wpdb->get_results($query);
		if( empty($vendor_quotes) ) return array(0);
		$vendor_quotes_arr = array();
		foreach( $vendor_quotes as $vendor_quote ) {
			$vendor_quotes_arr[] = $vendor_quote->ID;
		}
		if( !empty($vendor_quotes_arr) ) return $vendor_quotes_arr;
		return array(0);
  }
	
	// Product Vendors Profile Update
  function wcpvendors_profile_update( $user_id, $wcfm_profile_form ) {
  	global $WCFM, $wpdb, $_POST;
  	
		$wcfm_profile_social_fields = array( 
																					  '_twitter_profile'      => 'twitter',
																						'_fb_profile'           => 'facebook',
																						'_instagram'            => 'instagram',
																						'_youtube'              => 'youtube',
																						'_linkdin_profile'      => 'linkdin',
																						'_google_plus_profile'  => 'google_plus',
																						'_snapchat'             => 'snapchat',
																						'_pinterest'            => 'pinterest',
																						'googleplus'            => 'google_plus',
																						'twitter'               => 'twitter',
																						'facebook'              => 'facebook',
																			  );
		foreach( $wcfm_profile_social_fields as $wcfm_profile_social_key => $wcfm_profile_social_field ) {
			update_user_meta( $user_id, $wcfm_profile_social_key, $wcfm_profile_form[$wcfm_profile_social_field] );
		}
  }
}