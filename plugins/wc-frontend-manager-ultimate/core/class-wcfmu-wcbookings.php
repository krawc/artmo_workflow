<?php

/**
 * WCFMu plugin core
 *
 * Booking WC Booking Support
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   2.0.0
 */
 
class WCFMu_WCBookings {
	
	public function __construct() {
    global $WCFMu;
    
    if( wcfm_is_booking() ) {
    	
    	// WCFM view meta boxes
			add_action( 'add_meta_boxes', array( &$this, 'wcb_meta_boxes' ), 10, 2 );
			
			// WCFM View @dashboards
			add_action( 'restrict_manage_posts', array( $this, 'wcb_view_manage_posts' ) );
    	
			if ( current_user_can( 'manage_bookings' ) ) {
				// WCFM Bookings Endpoint Edit
				add_filter( 'wcfm_endpoints_slug', array( $this, 'wcb_wcfm_endpoints_slug' ) );
				
				// Bookings Load WCFMu Scripts
				add_action( 'after_wcfm_load_scripts', array( &$this, 'wcb_load_scripts' ), 30 );
				
				// Bookings Load WCFMu Styles
				add_action( 'after_wcfm_load_styles', array( &$this, 'wcb_load_styles' ), 30 );
				
				// Bookings Load WCFMu views
				add_action( 'wcfm_load_views', array( &$this, 'wcb_load_views' ), 30 );
				
				// Bookings Ajax Controllers
				add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcb_ajax_controller' ) );
				
					
				// Bookable Product options
				add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcb_product_manage_fields_general' ), 50, 3 );
				add_filter( 'wcfm_wcbokings_availability_fields', array( &$this, 'wcb_product_manage_availability_fields' ), 10, 2 );
				add_filter( 'wcfm_wcbokings_cost_fields', array( &$this, 'wcb_product_manage_cost_fields' ), 10, 2 );
    	
				// Booking Product Manage View
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcb_wcfmu_products_manage_form_load_views' ), 30 );
			}
    	
    	// Booking Mark as Confirmed
			add_action( 'wp_ajax_wcfm_booking_mark_confirm', array( &$this, 'wcfm_booking_mark_confirm' ) );
			
			// Booking Status Update
			add_action( 'wp_ajax_wcfm_modify_booking_status', array( &$this, 'wcfm_modify_booking_status' ) );
			
			// Manual Booking set Customer as Guest
			add_filter( 'wcfm_manual_bookings_props', array( &$this, 'wcfm_manual_bookings_props' ) );
    }
    
    // Add Vendor Direct System Message
    add_filter( 'wcfm_message_types', array( &$this, 'wcb_wcfm_message_types' ), 15 );
    if( apply_filters( 'wcfm_is_allow_bookings_extended_notifications', false ) ) {
			add_action( 'woocommerce_booking_in-cart_to_paid_notification', array( $this, 'wcfm_message_on_new_booking' ) );
			add_action( 'woocommerce_booking_in-cart_to_pending-confirmation_notification', array( $this, 'wcfm_message_on_new_booking' ) );
			add_action( 'woocommerce_booking_unpaid_to_paid_notification', array( $this, 'wcfm_message_on_new_booking' ) );
			add_action( 'woocommerce_booking_unpaid_to_pending-confirmation_notification', array( $this, 'wcfm_message_on_new_booking' ) );
			add_action( 'woocommerce_booking_confirmed_to_paid_notification', array( $this, 'wcfm_message_on_new_booking' ) );
		}
		add_action( 'woocommerce_new_booking_notification', array( $this, 'wcfm_message_on_new_booking' ) );
		add_action( 'woocommerce_admin_new_booking_notification', array( $this, 'wcfm_message_on_new_booking' ) );
  }
  
  /**
	 * Register WCFM Metabox
	 */
	function wcb_meta_boxes( $post_type, $post ) {
		global $WCFMu;
		
		if( in_array( $post_type, array( 'wc_booking' ) ) ) {
			add_meta_box( 'wcfm-view', __( 'WCFM View', 'wc-frontend-manager-ultimate' ), array( &$this, 'wcb_view_metabox' ), 'wc_booking', 'side', 'high' );
		}
 	}
	
	/**
	 * WCFM View Meta Box
	 */
	function wcb_view_metabox( $post ) {
		global $WCFM, $WCFMu;
		
		$wcfm_url = get_wcfm_page();
		if( $post->ID && $post->post_type ) {
			if( $post->post_type == 'wc_booking' ) $wcfm_url = get_wcfm_view_booking_url($post->ID);
		}
		
		echo '<div style="text-align: center;"><a href="' . $wcfm_url . '"><img src="' . $WCFM->plugin_url . '/assets/images/wcfm-30x30.png" alt="' . __( 'WCFM Home', 'wc-frontend-manager-ultimate' ) . '" /></a></div>';
	}
	
	/**
	 * WCFM View at dashboards
	 */
	function wcb_view_manage_posts() {
		global $WCFM, $WCFMu, $typenow;

		if ( 'wc_booking' == $typenow ) {
			echo '<a style="float: right;" href="' . get_wcfm_bookings_url() . '"><img src="' . $WCFM->plugin_url . '/assets/images/wcfm-30x30.png" alt="' . __( 'WCFM Home', 'wc-frontend-manager-ultimate' ) . '" /></a>';
		}
	}
	
	/**
	 * WC Booking Endpoiint Edit
	 */
	function wcb_wcfm_endpoints_slug( $endpoints ) {
		
		$booking_endpoints = array(
													'wcfm-bookings-dashboard'        => 'bookings-dashboard',
													'wcfm-bookings'                  => 'bookings',
													'wcfm-bookings-resources'        => 'bookings-resources',
													'wcfm-bookings-resources-manage' => 'bookings-resources-manage',
													'wcfm-bookings-manual'    		   => 'bookings-manual',
													'wcfm-bookings-calendar'  		   => 'bookings-calendar',
													'wcfm-bookings-details'          => 'bookings-details',
													'wcfm-bookings-settings'         => 'bookings-settings'
													);
		
		$endpoints = array_merge( $endpoints, $booking_endpoints );
		
		return $endpoints;
	}
	
	/**
   * WC Booking Scripts
   */
  public function wcb_load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
	  		wp_enqueue_script( 'wcfmu_wcbookings_products_manage_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-products-manage.js', array('jquery'), $WCFMu->version, true );
	  	break;
	  	
      case 'wcfm-bookings-resources':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmu_bookings_resources_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-resources.js', array('jquery', 'dataTables_js'), $WCFMu->version, true );
      break;
      
      case 'wcfm-bookings-resources-manage':
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_bookings_resources_manage_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-resources-manage.js', array('jquery'), $WCFMu->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfm_resources_manage_messages();
			  wp_localize_script( 'wcfmu_bookings_resources_manage_js', 'wcfm_resources_manage_messages', $wcfm_messages );
      break;
      
      case 'wcfm-bookings-manual':
      	$WCFM->library->load_select2_lib();
	    	wp_enqueue_script( 'wcfmu_bookings_manual_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-manual.js', array('jquery', 'select2_js'), $WCFMu->version, true );
      break;
      
      case 'wcfm-bookings-calendar':
      	$WCFM->library->load_tiptip_lib();
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_bookings_calendar_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-calendar.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-bookings-settings':
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_bookings_settings_js', $WCFMu->library->js_lib_url . 'wc_bookings/wcfmu-script-wcbookings-settings.js', array('jquery'), $WCFMu->version, true );
      break;
	  }
	}
	
	/**
   * WC Booking Styles
   */
	public function wcb_load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
		  	wp_enqueue_style( 'wcfmu_wcbookings_products_manage_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-products-manage.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-bookings-resources':
	    	wp_enqueue_style( 'wcfmu_bookings_resources_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-resources.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-bookings-resources-manage':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_bookings_resources_manage_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-resources-manage.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-bookings-manual':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_bookings_manual_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-manual.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-bookings-calendar':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_bookings_calendar_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-calendar.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-bookings-settings':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_bookings_settings_css',  $WCFMu->library->css_lib_url . 'wc_bookings/wcfmu-style-wcbookings-settings.css', array(), $WCFMu->version );
		  break;
	  }
	}
	
	/**
   * WC Booking Views
   */
  public function wcb_load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
      case 'wcfm-bookings-resources':
        $WCFMu->template->get_template( 'wc_bookings/wcfmu-view-wcbookings-resources.php' );
      break;
      
      case 'wcfm-bookings-resources-manage':
        $WCFMu->template->get_template( 'wc_bookings/wcfmu-view-wcbookings-resources-manage.php' );
      break;
      
      case 'wcfm-bookings-manual':
        $WCFMu->template->get_template( 'wc_bookings/wcfmu-view-wcbookings-manual.php' );
      break;
      
      case 'wcfm-bookings-calendar':
        $WCFMu->template->get_template( 'wc_bookings/wcfmu-view-wcbookings-calendar.php' );
      break;
      
      case 'wcfm-bookings-settings':
        $WCFMu->template->get_template( 'wc_bookings/wcfmu-view-wcbookings-settings.php' );
      break;
	  }
	}
	
	/**
   * WC Booking Ajax Controllers
   */
  public function wcb_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFMu->plugin_path . 'controllers/wc_bookings/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-products-manage':
  				include_once( $controllers_path . 'wcfmu-controller-wcbookings-products-manage.php' );
					new WCFMu_WCBookings_Products_Manage_Controller();
  			break;
  			
				case 'wcfm-bookings-resources':
					include_once( $controllers_path . 'wcfmu-controller-wcbookings-resources.php' );
					new WCFMu_WCBookings_Resources_Controller();
				break;
				
				case 'wcfm-bookings-resources-manage':
					include_once( $controllers_path . 'wcfmu-controller-wcbookings-resources-manage.php' );
					new WCFMu_WCBookings_Resources_Manage_Controller();
				break;
				
				case 'wcfm-bookings-settings':
					include_once( $controllers_path . 'wcfmu-controller-wcbookings-settings.php' );
					new WCFMu_WCBookings_Settings_Controller();
				break;
  		}
  	}
  }
  
  /**
	 * WC Booking Product General options
	 */
	function wcb_product_manage_fields_general( $general_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMu;
		
		$has_resources = '';
		$has_persons = '';
		
		if( $product_id ) {
			// Has Resource
			$has_resources = ( get_post_meta( $product_id, '_wc_booking_has_resources', true) ) ? 'yes' : '';
			if( $product_type == 'accommodation-booking' ) $has_resources = ( get_post_meta( $product_id, '_wc_booking_has_resources', true) ) ? get_post_meta( $product_id, '_wc_booking_has_resources', true) : '';
			
			// Has Persons
			$has_persons = ( get_post_meta( $product_id, '_wc_booking_has_persons', true) ) ? 'yes' : '';
			if( $product_type == 'accommodation-booking' ) $has_persons = ( get_post_meta( $product_id, '_wc_booking_has_persons', true) ) ? get_post_meta( $product_id, '_wc_booking_has_persons', true) : '';
		}
		
		if ( current_user_can( 'manage_bookings' ) && apply_filters( 'wcfm_is_allow_manage_resource', true ) ) {
			$general_fields = array_slice($general_fields, 0, 1, true) +
																		array("_wc_booking_has_resources" => array('desc' => __('Has Resources', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele wcfm_half_ele_checkbox booking accommodation-booking', 'desc_class' => 'wcfm_title wcfm_ele has_resource_ele_title checkbox_title booking accommodation-booking', 'value' => 'yes', 'dfvalue' => $has_resources),
																					"_wc_booking_has_persons" => array('desc' => __('Has Persons', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele wcfm_half_ele_checkbox booking accommodation-booking', 'desc_class' => 'wcfm_title wcfm_ele has_person_ele_title checkbox_title booking accommodation-booking', 'value' => 'yes', 'dfvalue' => $has_persons),
																					) +
																		array_slice($general_fields, 1, count($general_fields) - 1, true) ;
		} else {
			$general_fields = array_slice($general_fields, 0, 1, true) +
																		array("_wc_booking_has_resources" => array('desc' => __('Has Resources', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele_hide', 'desc_class' => 'wcfm_title wcfm_ele_hide', 'value' => 'yes', 'dfvalue' => $has_resources),
																					"_wc_booking_has_persons" => array('desc' => __('Has Persons', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele_hide', 'desc_class' => 'wcfm_title wcfm_ele_hide', 'value' => 'yes', 'dfvalue' => $has_persons),
																					) +
																		array_slice($general_fields, 1, count($general_fields) - 1, true) ;
		}
		
		return $general_fields;
	}
	
	/**
	 * WC Bookings Availability Range rules
	 */
	function wcb_product_manage_availability_fields( $availability_fields, $product_id ) {
		global $WCFM, $WCFMu;
		
		$intervals = array();

		$intervals['months'] = array(
			'1'  => __( 'January', 'woocommerce-bookings' ),
			'2'  => __( 'February', 'woocommerce-bookings' ),
			'3'  => __( 'March', 'woocommerce-bookings' ),
			'4'  => __( 'April', 'woocommerce-bookings' ),
			'5'  => __( 'May', 'woocommerce-bookings' ),
			'6'  => __( 'June', 'woocommerce-bookings' ),
			'7'  => __( 'July', 'woocommerce-bookings' ),
			'8'  => __( 'August', 'woocommerce-bookings' ),
			'9'  => __( 'September', 'woocommerce-bookings' ),
			'10' => __( 'October', 'woocommerce-bookings' ),
			'11' => __( 'November', 'woocommerce-bookings' ),
			'12' => __( 'December', 'woocommerce-bookings' )
		);
	
		$intervals['days'] = array(
			'1' => __( 'Monday', 'woocommerce-bookings' ),
			'2' => __( 'Tuesday', 'woocommerce-bookings' ),
			'3' => __( 'Wednesday', 'woocommerce-bookings' ),
			'4' => __( 'Thursday', 'woocommerce-bookings' ),
			'5' => __( 'Friday', 'woocommerce-bookings' ),
			'6' => __( 'Saturday', 'woocommerce-bookings' ),
			'7' => __( 'Sunday', 'woocommerce-bookings' )
		);
	
		for ( $i = 1; $i <= 53; $i ++ ) {
			$intervals['weeks'][ $i ] = sprintf( __( 'Week %s', 'woocommerce-bookings' ), $i );
		}
		
		$range_types = array(
													'custom'     => __( 'Date range', 'woocommerce-bookings' ),
													'months'     => __( 'Range of months', 'woocommerce-bookings' ),
													'weeks'      => __( 'Range of weeks', 'woocommerce-bookings' ),
													'days'       => __( 'Range of days', 'woocommerce-bookings' ),
													'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range (all week)', 'woocommerce-bookings' ),
													'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'woocommerce-bookings' )
												);
		foreach ( $intervals['days'] as $key => $label ) :
		  $range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
		endforeach;
		
		
		
		$check_availability_against = '';
		$has_restricted_days = 'no';
		$restricted_days = array();
		$first_block_time = '';
		
		$availability_rule_values = array();
		$availability_default_rules = apply_filters( 'wcfm_booking_availability_default_rules', array(  "type"   => 'custom',
																																																		"from_custom"  => '',
																																																		"to_custom"    => '',
																																																		"from_months"  => '',
																																																		"to_months"    => '',
																																																		"from_weeks"   => '',
																																																		"to_weeks"     => '',
																																																		"from_days"    => '',
																																																		"to_days"      => '', 
																																																		"from_time"    => '',
																																																		"to_time"      => '', 
																																																		"bookable"     => '',
																																																		"priority"     => 10
																																																	) );
		
		if( $product_id ) {
			$bookable_product = new WC_Product_Booking( $product_id );
			
			$check_availability_against = $bookable_product->get_check_start_block_only( 'edit' ) ? 'start' : '';
			
			if ( version_compare( get_option( 'wc_bookings_version', WC_BOOKINGS_VERSION ), '1.10.7', '>' ) ) {
				$has_restricted_days = $bookable_product->get_has_restricted_days( 'edit' ) ? 'yes' : 'no';
				$restricted_days = $bookable_product->get_restricted_days( 'edit' );
				if( !$restricted_days ) $restricted_days = array();
			}
			
			$first_block_time = $bookable_product->get_first_block_time( 'edit' );
			
			$availability_rules = $bookable_product->get_availability( 'edit' );
			
			if( !empty( $availability_rules ) ) {
				foreach( $availability_rules as $a_index => $availability_rule ) {
					$availability_rule_values[$a_index] = $availability_default_rules;
					$availability_rule_values[$a_index]['type'] = $availability_rule['type'];
					if($availability_rule['type'] == 'custom' ) {
						$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to'];
					} elseif($availability_rule['type'] == 'months' ) {
						$availability_rule_values[$a_index]['from_months'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_months']   = $availability_rule['to'];
					} elseif($availability_rule['type'] == 'weeks' ) {
						$availability_rule_values[$a_index]['from_weeks'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_weeks']   = $availability_rule['to'];
					} elseif($availability_rule['type'] == 'days' ) {
						$availability_rule_values[$a_index]['from_days'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_days']   = $availability_rule['to'];
					} elseif($availability_rule['type'] == 'time:range' ) {
						$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from_date'];
						$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to_date'];
						$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
					} else {
						$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
						$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
					}
					$availability_rule_values[$a_index]['bookable'] = $availability_rule['bookable'];
					$availability_rule_values[$a_index]['priority'] = $availability_rule['priority'];
				}
			}
		} else {
			$availability_rule_values[0] = $availability_default_rules;
		}
		
		$wcfmu_availability_fields = array (
																				"_wc_booking_check_availability_against" => array('label' => __('Check rules against...', 'woocommerce-bookings') , 'type' => 'select', 'options' => array( '' => __( 'All blocks being booked', 'woocommerce-bookings'), 'start' => __( 'The starting block only', 'woocommerce-bookings' ) ), 'class' => 'wcfm-select wcfm_ele booking', 'label_class' => 'wcfm_title booking', 'value' => $check_availability_against, 'hints' => __( 'This option affects how bookings are checked for availability.', 'woocommerce-bookings' ) ),
																				"_wc_booking_has_restricted_days" => array('label' => __('Restrict start days?', 'woocommerce-bookings') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele booking', 'label_class' => 'wcfm_title checkbox_title booking', 'value' => 'yes', 'dfvalue' => $has_restricted_days, 'hints' => __( 'Restrict bookings so that they can only start on certain days of the week. Does not affect availability.', 'woocommerce-bookings' ) ),
																				"_wc_booking_restricted_days" => array('label' => __('Restricted days', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'options' => array( 0 => __( 'Sunday', 'woocommerce-bookings'), 1 => __( 'Monday', 'woocommerce-bookings' ), 2 => __( 'Tuesday', 'woocommerce-bookings' ), 3 => __( 'Wednesday', 'woocommerce-bookings' ), 4 => __( 'Thrusday', 'woocommerce-bookings' ), 5 => __( 'Friday', 'woocommerce-bookings' ), 6 => __( 'Saturday', 'woocommerce-bookings' ) ), 'class' => 'wcfm-select wcfm_ele booking', 'label_class' => 'wcfm_title booking', 'value' => $restricted_days ),
																				"_wc_booking_first_block_time" => array('label' => __('First block starts at...', 'wc-frontend-manager-ultimate') , 'type' => 'time', 'class' => 'wcfm-text wcfm_ele booking', 'label_class' => 'wcfm_title booking', 'placeholder' => "HH:MM", 'value' => $first_block_time ),
																				"_wc_booking_availability_rules" =>     array('label' => __('Rules', 'wc-frontend-manager-ultimate') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele booking', 'label_class' => 'wcfm_title booking', 'desc' => __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Ordering is only applied within the same priority and higher order overrides lower order.', 'woocommerce-bookings' ), 'desc_class' => 'avail_rules_desc', 'value' => $availability_rule_values, 'options' => array(
																									"type" => array('label' => __('Type', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $range_types, 'class' => 'wcfm-select wcfm_ele avail_range_type booking', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label booking' ),
																									"from_custom" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																									"to_custom" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																									"from_months" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																									"to_months" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																									"from_weeks" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																									"to_weeks" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																									"from_days" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																									"to_days" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																									"from_time" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																									"to_time" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																									"bookable" => array('label' => __('Bookable', 'woocommerce-bookings'), 'type' => 'select', 'options' => array( 'no' => 'NO', 'yes' => 'YES' ), 'class' => 'wcfm-select wcfm_ele avail_rules_ele avail_rules_text booking', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label', 'hints' => __( 'If not bookable, users won\'t be able to choose this block for their booking.', 'woocommerce-bookings' ) ),
																									"priority" => array('label' => __('Priority', 'woocommerce-bookings'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele avail_rules_ele avail_rule_priority avail_rules_text booking', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label booking', 'hints' => esc_attr( get_wc_booking_priority_explanation() ) ),
																							    )	)
																				);
		
		$availability_fields = array_merge( $availability_fields, $wcfmu_availability_fields );
		return $availability_fields;
	}
	
	/**
	 * WC Bookings Cost Range rules
	 */
	function wcb_product_manage_cost_fields( $cost_fields, $product_id ) {
		global $WCFM, $WCFMu;
		
		$intervals = array();

		$intervals['months'] = array(
			'1'  => __( 'January', 'woocommerce-bookings' ),
			'2'  => __( 'February', 'woocommerce-bookings' ),
			'3'  => __( 'March', 'woocommerce-bookings' ),
			'4'  => __( 'April', 'woocommerce-bookings' ),
			'5'  => __( 'May', 'woocommerce-bookings' ),
			'6'  => __( 'June', 'woocommerce-bookings' ),
			'7'  => __( 'July', 'woocommerce-bookings' ),
			'8'  => __( 'August', 'woocommerce-bookings' ),
			'9'  => __( 'September', 'woocommerce-bookings' ),
			'10' => __( 'October', 'woocommerce-bookings' ),
			'11' => __( 'November', 'woocommerce-bookings' ),
			'12' => __( 'December', 'woocommerce-bookings' )
		);
	
		$intervals['days'] = array(
			'1' => __( 'Monday', 'woocommerce-bookings' ),
			'2' => __( 'Tuesday', 'woocommerce-bookings' ),
			'3' => __( 'Wednesday', 'woocommerce-bookings' ),
			'4' => __( 'Thursday', 'woocommerce-bookings' ),
			'5' => __( 'Friday', 'woocommerce-bookings' ),
			'6' => __( 'Saturday', 'woocommerce-bookings' ),
			'7' => __( 'Sunday', 'woocommerce-bookings' )
		);
	
		for ( $i = 1; $i <= 53; $i ++ ) {
			$intervals['weeks'][ $i ] = sprintf( __( 'Week %s', 'woocommerce-bookings' ), $i );
		}
		
		$range_types = array(
													'custom'     => __( 'Date range', 'woocommerce-bookings' ),
													'months'     => __( 'Range of months', 'woocommerce-bookings' ),
													'weeks'      => __( 'Range of weeks', 'woocommerce-bookings' ),
													'days'       => __( 'Range of days', 'woocommerce-bookings' ),
													'persons'    => __( 'Person count', 'woocommerce-bookings' ),
													'blocks'     => __( 'Block count', 'woocommerce-bookings' ),
													'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range', 'woocommerce-bookings' ),
													'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'woocommerce-bookings' )
												);
		foreach ( $intervals['days'] as $key => $label ) :
		  $range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
		endforeach;
		
		
		
		$check_cost_against = '';
		$first_block_time = '';
		
		$cost_rule_values = array();
		$cost_default_rules = array(  "type"            => 'custom',
																	"from_custom"     => '',
																	"to_custom"       => '',
																	"from_months"     => '',
																	"to_months"       => '',
																	"from_weeks"      => '',
																	"to_weeks"        => '',
																	"from_days"       => '',
																	"to_days"         => '', 
																	"from_time"       => '',
																	"to_time"         => '',
																	"from_count"      => '',
																	"to_count"        => '',
																	"base_modifier"   => '',
																	"base_cost"       => '',
																	"block_modifier"  => '',
																	"block_cost"      => ''
																);
		$cost_rule_values[0] = $cost_default_rules;
		
		if( $product_id ) {
			$bookable_product = new WC_Product_Booking( $product_id );
			
			$cost_rules = $bookable_product->get_pricing( 'edit' );
			
			if( !empty( $cost_rules ) ) {
				foreach( $cost_rules as $a_index => $cost_rule ) {
					$cost_rule_values[$a_index] = $cost_default_rules;
					$cost_rule_values[$a_index]['type'] = $cost_rule['type'];
					if($cost_rule['type'] == 'custom' ) {
						$cost_rule_values[$a_index]['from_custom'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_custom']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'months' ) {
						$cost_rule_values[$a_index]['from_months'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_months']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'weeks' ) {
						$cost_rule_values[$a_index]['from_weeks'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_weeks']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'days' ) {
						$cost_rule_values[$a_index]['from_days'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_days']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'persons' ) {
						$cost_rule_values[$a_index]['from_count'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_count']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'blocks' ) {
						$cost_rule_values[$a_index]['from_count'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_count']   = $cost_rule['to'];
					} elseif($cost_rule['type'] == 'time:range' ) {
						$cost_rule_values[$a_index]['from_custom'] = $cost_rule['from_date'];
						$cost_rule_values[$a_index]['to_custom']   = $cost_rule['to_date'];
						$cost_rule_values[$a_index]['from_time'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_time']   = $cost_rule['to'];
					} else {
						$cost_rule_values[$a_index]['from_time'] = $cost_rule['from'];
						$cost_rule_values[$a_index]['to_time']   = $cost_rule['to'];
					}
					$cost_rule_values[$a_index]['base_modifier']  = $cost_rule['base_modifier'];
					$cost_rule_values[$a_index]['base_cost']      = $cost_rule['base_cost'];
					$cost_rule_values[$a_index]['block_modifier'] = $cost_rule['modifier'];
					$cost_rule_values[$a_index]['block_cost']     = $cost_rule['cost'];
				}
			}
		}
		
		$wcfmu_cost_fields = array (
																				"_wc_booking_cost_rules" =>     array('label' => __('Rules', 'wc-frontend-manager-ultimate') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele booking', 'label_class' => 'wcfm_title booking', 'desc' => __( 'All matching rules will be applied to the booking.', 'woocommerce-bookings' ), 'desc_class' => 'cost_rules_desc', 'value' => $cost_rule_values, 'options' => array(
																									"type" => array('label' => __('Type', 'woocommerce-bookings'), 'type' => 'select', 'options' => $range_types, 'class' => 'wcfm-select wcfm_ele cost_range_type booking', 'label_class' => 'wcfm_title cost_rules_ele cost_rules_label booking' ),
																									"from_custom" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker cost_rule_field cost_rule_custom cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_custom cost_rules_ele cost_rules_label' ),
																									"to_custom" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker cost_rule_field cost_rule_custom cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_custom cost_rules_ele cost_rules_label' ),
																									"from_months" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select cost_rule_field cost_rule_months cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_months cost_rules_ele cost_rules_label' ),
																									"to_months" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select cost_rule_field cost_rule_months cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_months cost_rules_ele cost_rules_label' ),
																									"from_weeks" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select cost_rule_field cost_rule_weeks cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_weeks cost_rules_ele cost_rules_label' ),
																									"to_weeks" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select cost_rule_field cost_rule_weeks cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_weeks cost_rules_ele cost_rules_label' ),
																									"from_days" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select cost_rule_field cost_rule_days cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_days cost_rules_ele cost_rules_label' ),
																									"to_days" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select cost_rule_field cost_rule_days cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_days cost_rules_ele cost_rules_label' ),
																									"from_time" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-select cost_rule_field cost_rule_time cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_time cost_rules_ele cost_rules_label' ),
																									"to_time" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-select cost_rule_field cost_rule_time cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_time cost_rules_ele cost_rules_label' ),
																									"from_count" => array('label' => __('From', 'woocommerce-bookings'), 'type' => 'number', 'class' => 'wcfm-text cost_rule_field cost_rule_count cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_count cost_rules_ele cost_rules_label' ),
																									"to_count" => array('label' => __('To', 'woocommerce-bookings'), 'type' => 'number', 'class' => 'wcfm-text cost_rule_field cost_rule_count cost_rules_ele cost_rules_text', 'label_class' => 'wcfm_title cost_rule_field cost_rule_count cost_rules_ele cost_rules_label' ),
																									"base_modifier" => array('label' => __('Base Cost', 'woocommerce-bookings'), 'type' => 'select', 'options' => array( '' => '+', 'minus' => '-', 'times' => '&times;', 'divide' => '&divide;' ), 'class' => 'wcfm-select wcfm_ele cost_rules_ele cost_rules_text cost_price_ele booking', 'label_class' => 'wcfm_title cost_rules_ele cost_rules_label', 'hints' => __( 'Enter a cost for this rule. Applied to the booking as a whole.', 'woocommerce-bookings' ) ),
																									"base_cost" => array( 'type' => 'number', 'class' => 'wcfm-text wcfm_ele cost_rules_ele cost_rule_base_cost cost_rules_text cost_price_ele cost_price_field booking', 'label_class' => 'wcfm_title cost_rules_ele cost_rules_label booking' ),
																									"block_modifier" => array('label' => __('Block Cost', 'woocommerce-bookings'), 'type' => 'select', 'options' => array( '' => '+', 'minus' => '-', 'times' => '&times;', 'divide' => '&divide;' ), 'class' => 'wcfm-select wcfm_ele cost_rules_ele cost_rules_text cost_price_ele booking', 'label_class' => 'wcfm_title cost_rules_ele cost_rules_label', 'hints' => __( 'Enter a cost for this rule. Applied to each booking block.', 'woocommerce-bookings' ) ),
																									"block_cost" => array( 'type' => 'number', 'class' => 'wcfm-text wcfm_ele cost_rules_ele cost_rule_block_cost cost_rules_text cost_price_ele cost_price_field booking', 'label_class' => 'wcfm_title cost_rules_ele cost_rules_label booking' ),
																							    )	)
																				);
		
		$cost_fields = array_merge( $cost_fields, $wcfmu_cost_fields );
		return $cost_fields;
	}
  
  /**
   * WC Booking load views
   */
  function wcb_wcfmu_products_manage_form_load_views( ) {
		global $WCFMu;
	  
	 include_once( $WCFMu->library->views_path . 'wc_bookings/wcfmu-view-wcbookings-products-manage.php' );
	}
	
	/**
   * Handle Booking confirmation
   */
  public function wcfm_booking_mark_confirm() {
  	global $WCFM, $WCFMu;
  	
  	$booking_id = $_POST['bookingid'];
  	
  	$booking = get_wc_booking( $booking_id );
		if ( 'confirmed' !== $booking->get_status() ) {
			$booking->update_status( 'confirmed' );
		}
		die;
  }
  
  /**
   * Handle Booking Details Status Update
   */
  public function wcfm_modify_booking_status() {
  	global $WCFM, $WCFMu;
  	
  	$booking_id = $_POST['booking_id'];
  	$booking_status = $_POST['booking_status'];
  	
  	$booking = get_wc_booking( $booking_id );
  	$booking->update_status( $booking_status );
  	
  	// Status Update Notification
  	$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$shop_name =  get_user_by( 'ID', $user_id )->display_name;
		if( wcfm_is_vendor() ) {
			$shop_name =  $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($user_id) );
		}
  	$wcfm_messages = sprintf( __( '<b>%s</b> booking status updated to <b>%s</b> by <b>%s</b>', 'wc-frontend-manager-ultimate' ), '#<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_booking_url($booking_id) . '">' . $booking_id . '</a>', ucfirst( $booking_status ), $shop_name );
		$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'status-update' );
  	
  	echo '{"status": true, "message": "' . __( 'Booking status updated.', 'wc-frontend-manager-ultimate' ) . '"}';
  	
		die;
  }
  
  function wcfm_manual_bookings_props( $wcbooking ) {
  	if( wcfm_is_vendor() ) {
  		$customer_id = $wcbooking->get_customer_id();
  		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
  		if( $vendor_id == $customer_id ) {
  			$wcbooking->set_customer_id( 0 );
  		}
  	}
  	return $wcbooking;
  }
  
  function wcb_wcfm_message_types( $message_types ) {
  	
  	if( current_user_can( 'manage_bookings' ) ) {
  		$message_types['booking'] = __( 'New Booking', 'wc-frontend-manager-ultimate' );
  	}
  	
  	return $message_types;
  }
  
  /**
	 * Vendor direct message on new booking - 3.0.6
	 */
	function wcfm_message_on_new_booking( $booking_id ) {
		global $WCFM, $wpdb;
  	
  	if( is_admin() ) return;
  	
  	$author_id = -2;
  	$author_is_admin = 1;
		$author_is_vendor = 0;
		$message_to = 0;
		
		if ( $booking_id ) {
			$booking_object = get_wc_booking( $booking_id );
			
			if ( ! is_object( $booking_object ) ) {
				return;
			}
			
			if ( $booking_object->has_status( 'in-cart' ) ) {
				//return;
			}
			
			$product_id = $booking_object->get_product()->get_id();
			
			// Admin Notification
			$wcfm_messages = sprintf( __( 'You have received a Booking <b>#%s</b> for <b>%s</b>', 'wc-frontend-manager-ultimate' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_booking_url($booking_id) . '">' . $booking_id . '</a>', $booking_object->get_product()->get_title() );
			$WCFM->wcfm_notification->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'booking' );
			
			// Vendor Notification
			if( $WCFM->is_marketplace ) {
				$author_id = -1;
				$message_to = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
				
				if( $message_to ) {
					$wcfm_messages = sprintf( __( 'You have received a Booking <b>#%s</b> for <b>%s</b>', 'wc-frontend-manager-ultimate' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_booking_url($booking_id) . '">' . $booking_id . '</a>', $booking_object->get_product()->get_title() );
					$WCFM->wcfm_notification->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'booking' );
				}
			}
		}
	}
}