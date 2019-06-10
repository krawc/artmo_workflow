<?php

/**
 * WCFM plugin core
 *
 * Appointments WC Appointments Support
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   2.4.0
 */
 
class WCFMu_WCAppointments {
	
	public function __construct() {
    global $WCFM, $WCFMu;
    
    if( $wcfm_is_allow_appointments = apply_filters( 'wcfm_is_allow_appointments' , true ) ) {
			if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
				// WCFM Appointments Query Var Filter
				add_filter( 'wcfm_query_vars', array( &$this, 'wca_wcfm_query_vars' ), 90 );
				add_filter( 'wcfm_endpoint_title', array( &$this, 'wca_wcfm_endpoint_title' ), 90, 2 );
				add_action( 'init', array( &$this, 'wca_wcfm_init' ), 90 );
				
				// WCFM Appointments Endpoint Edit
				add_filter( 'wcfm_endpoints_slug', array( $this, 'wca_wcfm_endpoints_slug' ) );
				
				if ( current_user_can( 'manage_appointments' ) ) {	
					// WCFM Menu Filter
					add_filter( 'wcfm_menus', array( &$this, 'wca_wcfm_menus' ), 90 );
					
					// Appointments Product Type
					add_filter( 'wcfm_product_types', array( &$this, 'wca_product_types' ), 90 );
					
					// Appointment Product Type Capability
					add_filter( 'wcfm_settings_fields_product_types', array( &$this, 'wcfmcap_product_types' ), 90, 3 );
					
					// Appointments Load WCFMu Scripts
					add_action( 'wcfm_load_scripts', array( &$this, 'wca_load_scripts' ), 90 );
					add_action( 'after_wcfm_load_scripts', array( &$this, 'wca_load_scripts' ), 90 );
					
					// Appointments Load WCFMu Styles
					add_action( 'wcfm_load_styles', array( &$this, 'wca_load_styles' ), 90 );
					add_action( 'after_wcfm_load_styles', array( &$this, 'wca_load_styles' ), 90 );
					
					// Appointments Load WCFMu views
					add_action( 'wcfm_load_views', array( &$this, 'wca_load_views' ), 90 );
					add_action( 'before_wcfm_load_views', array( &$this, 'wca_load_views' ), 90 );
					
					// Appointments Ajax Controllers
					add_action( 'after_wcfm_ajax_controller', array( &$this, 'wca_ajax_controller' ) );
					
					// Appointments General Block
					add_action( 'after_wcfm_products_manage_general', array( &$this, 'wca_product_manage_general' ), 90, 2 );
					
					// Appointments Addons Options
					add_filter( 'wcfm_product_manage_fields_wcaddons', array( &$this, 'wca_product_manage_fields_wcaddons' ), 90 );
					
					// Appointment Mark as Confirmed
					add_action( 'wp_ajax_wcfm_appointment_mark_confirm', array( &$this, 'wcfm_appointment_mark_confirm' ) );
					
					// Appointment Status Update
					add_action( 'wp_ajax_wcfm_modify_appointment_status', array( &$this, 'wcfm_modify_appointment_status' ) );
					
					// Manual Appointment set Customer as Guest
					add_filter( 'wcfm_manual_appointment_props', array( &$this, 'wcfm_manual_appointment_props' ) );
				}
			}
		}
		
		// add vendor email for confirm appointment email
		add_filter( 'woocommerce_email_recipient_new_appointment', array( $this, 'wcfm_filter_appointment_emails' ), 10, 2 );

		// add vendor email for cancelled appointment email
		add_filter( 'woocommerce_email_recipient_appointment_cancelled', array( $this, 'wcfm_filter_appointment_emails' ), 10, 2 );
		
		// Add Vendor Direct System Message
		add_filter( 'wcfm_message_types', array( &$this, 'wca_wcfm_message_types' ), 20 );
		//add_action( 'woocommerce_appointment_in-cart_to_paid_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		//add_action( 'woocommerce_appointment_in-cart_to_pending-confirmation_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		//add_action( 'woocommerce_appointment_unpaid_to_paid_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		//add_action( 'woocommerce_appointment_unpaid_to_pending-confirmation_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		//add_action( 'woocommerce_appointment_confirmed_to_paid_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		add_action( 'woocommerce_new_appointment_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
		//add_action( 'woocommerce_admin_new_appointment_notification', array( $this, 'wcfm_message_on_new_appointment' ) );
  }
  
  /**
   * WC Appointments Query Var
   */
  function wca_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_appointments_vars = array(
			'wcfm-appointments-dashboard'       => ! empty( $wcfm_modified_endpoints['wcfm-appointments-dashboard'] ) ? $wcfm_modified_endpoints['wcfm-appointments-dashboard'] : 'wcfm-appointments-dashboard',
			'wcfm-appointments'                 => ! empty( $wcfm_modified_endpoints['wcfm-appointments'] ) ? $wcfm_modified_endpoints['wcfm-appointments'] : 'wcfm-appointments',
			'wcfm-appointments-staffs'          => ! empty( $wcfm_modified_endpoints['wcfm-appointments-staffs'] ) ? $wcfm_modified_endpoints['wcfm-appointments-staffs'] : 'wcfm-appointments-staffs',
			'wcfm-appointments-staffs-manage'   => ! empty( $wcfm_modified_endpoints['wcfm-appointments-staffs-manage'] ) ? $wcfm_modified_endpoints['wcfm-appointments-staffs-manage'] : 'wcfm-appointments-staffs-manage',
			'wcfm-appointments-manual'          => ! empty( $wcfm_modified_endpoints['wcfm-appointments-manual'] ) ? $wcfm_modified_endpoints['wcfm-appointments-manual'] : 'wcfm-appointments-manual',
			'wcfm-appointments-calendar'        => ! empty( $wcfm_modified_endpoints['wcfm-appointments-calendar'] ) ? $wcfm_modified_endpoints['wcfm-appointments-calendar'] : 'wcfm-appointments-calendar',
			'wcfm-appointments-details'         => ! empty( $wcfm_modified_endpoints['wcfm-appointments-details'] ) ? $wcfm_modified_endpoints['wcfm-appointments-details'] : 'wcfm-appointments-details',
			'wcfm-appointments-settings'        => ! empty( $wcfm_modified_endpoints['wcfm-appointments-settings'] ) ? $wcfm_modified_endpoints['wcfm-appointments-settings'] : 'wcfm-appointments-settings',
		);
		
		$query_vars = array_merge( $query_vars, $query_appointments_vars );
		
		return $query_vars;
  }
  
  /**
   * WC Appointments End Point Title
   */
  function wca_wcfm_endpoint_title( $title, $endpoint ) {
  	global $WCFM, $WCFMu, $wp;
  	
  	switch ( $endpoint ) {
  		case 'wcfm-appointments-dashboard' :
				$title = __( 'Appointments Dashboard', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments' :
				$title = __( 'Appointments List', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments-staffs' :
				$title = __( 'Appointments Staffs', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments-staffs-manage' :
				$title = __( 'Appointments Staffs Manage', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments-manual' :
				$title = __( 'Create Appointments', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments-calendar' :
				$title = __( 'Appointments Calendar', 'wc-frontend-manager-ultimate' );
			break;
			case 'wcfm-appointments-details' :
				$title = sprintf( __( 'Appointments Details #%s', 'wc-frontend-manager-ultimate' ), $wp->query_vars['wcfm-appointments-details'] );
			break;
			case 'wcfm-appointments-settings' :
				$title = __( 'Appointments Settings', 'wc-frontend-manager-ultimate' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WC Appointments Endpoint Intialize
   */
  function wca_wcfm_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wc_appointments' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wc_appointments', 1 );
		}
  }
  
  /**
	 * WC Appointments Endpoiint Edit
	 */
	function wca_wcfm_endpoints_slug( $endpoints ) {
		
		$appointment_endpoints = array(
													'wcfm-appointments-dashboard'        => 'wcfm-appointments-dashboard',
													'wcfm-appointments'                  => 'wcfm-appointments',
													'wcfm-appointments-staffs'           => 'wcfm-appointments-staffs',
													'wcfm-appointments-staffs-manage'    => 'wcfm-appointments-staffs-manage',
													'wcfm-appointments-manual'    		   => 'wcfm-appointments-manual',
													'wcfm-appointments-calendar'  		   => 'wcfm-appointments-calendar',
													'wcfm-appointments-details'          => 'wcfm-appointments-details',
													'wcfm-appointments-settings'         => 'wcfm-appointments-settings'
													);
		
		$endpoints = array_merge( $endpoints, $appointment_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WC Appointments Menu
   */
  function wca_wcfm_menus( $menus ) {
  	global $WCFM;
  	
  	if ( current_user_can( 'manage_appointments' ) ) {
			$menus = array_slice($menus, 0, 3, true) +
													array( 'wcfm-appointments-dashboard' => array(   'label'  => __( 'Appointments', 'woocommerce-appointments'),
																											 'url'        => get_wcfm_appointments_dashboard_url(),
																											 'icon'       => 'clock-o',
																											 'priority'   => 20
																											) )	 +
														array_slice($menus, 3, count($menus) - 3, true) ;
		}
		
  	return $menus;
  }
  
  /**
   * WC Appointments Product Type
   */
  function wca_product_types( $pro_types ) {
  	global $WCFM;
  	if ( current_user_can( 'manage_appointments' ) ) {
  		$pro_types['appointment'] = __( 'Appointable product', 'woocommerce-appointments' );
  	}
  	
  	return $pro_types;
  }
  
  /**
	 * WCFM Capability Product Types
	 */
	function wcfmcap_product_types( $product_types, $handler = 'wcfm_capability_options', $wcfm_capability_options = array() ) {
		global $WCFM, $WCFMu;
		
		$appointment = ( isset( $wcfm_capability_options['appointment'] ) ) ? $wcfm_capability_options['appointment'] : 'no';
		
		$product_types["appointment"] = array('label' => __('Appointment', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[appointment]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $appointment);
		
		return $product_types;
	}
	
	/**
	* WC Appointments Scripts
	*/
  public function wca_load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
				wp_enqueue_script( 'wcfmu_wc_appointments_products_manage_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-products-manage.js', array( 'jquery' ), $WCFMu->version, true );
			break;
			
			case 'wcfm-appointments':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments.js', array('jquery', 'dataTables_js'), $WCFMu->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager = (array) get_option( 'wcfm_screen_manager' );
	    	$wcfm_screen_manager_data = array();
	    	if( isset( $wcfm_screen_manager['appointment'] ) ) $wcfm_screen_manager_data = $wcfm_screen_manager['appointment'];
	    	if( !isset( $wcfm_screen_manager_data['admin'] ) ) {
					$wcfm_screen_manager_data['admin'] = $wcfm_screen_manager_data;
					$wcfm_screen_manager_data['vendor'] = $wcfm_screen_manager_data;
				}
				if( wcfm_is_vendor() ) {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['vendor'];
				} else {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['admin'];
				}
				$wcfm_screen_manager_data[6] = apply_filters( 'wcfm_appointments_additonal_data_hidden', 'yes' );
	    	wp_localize_script( 'wcfmu_appointments_js', 'wcfm_appointments_screen_manage', $wcfm_screen_manager_data );
      break;
      
      case 'wcfm-appointments-staffs':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_staffs_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-staffs.js', array('jquery', 'dataTables_js'), $WCFMu->version, true );
      break;
      
      case 'wcfm-appointments-staffs-manage':
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_staffs_manage_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-staffs-manage.js', array('jquery'), $WCFMu->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfm_staffs_manage_messages();
			  wp_localize_script( 'wcfmu_appointments_staffs_manage_js', 'wcfm_staffs_manage_messages', $wcfm_messages );
      break;
      
      case 'wcfm-appointments-manual':
      	$WCFM->library->load_select2_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_manual_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-manual.js', array('jquery', 'select2_js'), $WCFMu->version, true );
      break;
      
      case 'wcfm-appointments-calendar':
      	$WCFM->library->load_tiptip_lib();
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_calendar_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-calendar.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-appointments-details':
	    	wp_enqueue_script( 'wcfmu_appointments_details_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-details.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-appointments-settings':
      	$WCFM->library->load_datepicker_lib();
	    	wp_enqueue_script( 'wcfmu_appointments_settings_js', $WCFMu->library->js_lib_url . 'wc_appointments/wcfmu-script-wcappointments-settings.js', array('jquery'), $WCFMu->version, true );
      break;
	  }
	}
	
	/**
   * WC Appointments Styles
   */
	public function wca_load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
				wp_enqueue_style( 'wcfmu_wc_appointments_products_manage_css', $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-products-manage.css', array( ), $WCFMu->version );
			break;
			
			case 'wcfm-appointments-dashboard':
	    	wp_enqueue_style( 'wcfmu_appointments_dashboard_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-dashboard.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments':
	    	wp_enqueue_style( 'wcfmu_appointments_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-staffs':
	    	wp_enqueue_style( 'wcfmu_appointments_staffs_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-staffs.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-staffs-manage':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_appointments_staffs_manage_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-staffs-manage.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-manual':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_appointments_manual_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-manual.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-calendar':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_appointments_calendar_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-calendar.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-details':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_appointments_details_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-details.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-appointments-settings':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_appointments_settings_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-settings.css', array(), $WCFMu->version );
		  break;
	  }
	}
	
	/**
   * WC Appointments Views
   */
  public function wca_load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
	  	case 'wcfm-appointments-dashboard':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-dashboard.php' );
      break;
      
      case 'wcfm-appointments':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments.php' );
      break;
      
      case 'wcfm-appointments-staffs':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-staffs.php' );
      break;
      
      case 'wcfm-appointments-staffs-manage':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-staffs-manage.php' );
      break;
      
      case 'wcfm-appointments-manual':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-manual.php' );
      break;
      
      case 'wcfm-appointments-calendar':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-calendar.php' );
      break;
      
      case 'wcfm-appointments-details':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-details.php' );
      break;
      
      case 'wcfm-appointments-settings':
        require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-settings.php' );
      break;
	  }
	}
	
	/**
   * WC Appointments Ajax Controllers
   */
  public function wca_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFMu->plugin_path . 'controllers/wc_appointments/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-products-manage':
  				require_once( $controllers_path . 'wcfmu-controller-wcappointments-products-manage.php' );
					new WCFMu_WCAppointments_Products_Manage_Controller();
				break;
				
				case 'wcfm-appointments':
					require_once( $controllers_path . 'wcfmu-controller-wcappointments.php' );
					new WCFMu_WCAppointments_Controller();
				break;
				
				case 'wcfm-appointments-staffs':
					require_once( $controllers_path . 'wcfmu-controller-wcappointments-staffs.php' );
					new WCFMu_WCAppointments_Staffs_Controller();
				break;
				
				case 'wcfm-appointments-staffs-manage':
					require_once( $controllers_path . 'wcfmu-controller-wcappointments-staffs-manage.php' );
					new WCFMu_WCAppointments_Staffs_Manage_Controller();
				break;
				
				case 'wcfm-appointments-settings':
					require_once( $controllers_path . 'wcfmu-controller-wcappointments-settings.php' );
					new WCFMu_WCAppointments_Settings_Controller();
				break;
  		}
  	}
  }
  
  /**
   * WC Appointments Product General Options
   */
  function wca_product_manage_general( $product_id, $product_type ) {
  	global $WCFM, $WCFMu;
  	
  	require_once( $WCFMu->library->views_path . 'wc_appointments/wcfmu-view-wcappointments-products-manage.php' );
  }
  
  /**
   * WC Appointments Product Addon Options
   */
  function wca_product_manage_fields_wcaddons( $product_addon_fields ) {
  	global $WCFM, $WCFMu;
  	
  	$product_addon_fields['_product_addons']['options'] = array_slice($product_addon_fields['_product_addons']['options'], 0, 4, true) +
																															array( "wc_appointment_hide_duration_label" => array( 'label' => __('Hide duration label for customers', 'woocommerce-appointments') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 1 ),
																																		 "wc_appointment_hide_price_label" => array( 'label' => __('Hide price label for customers', 'woocommerce-appointments') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 1 ),
																																		) +
																															array_slice($product_addon_fields['_product_addons']['options'], 4, count($product_addon_fields['_product_addons']['options']) - 4, true) ;
																															
		$product_addon_fields['_product_addons']['options']['options']['options'] = array_slice($product_addon_fields['_product_addons']['options']['options']['options'], 0, 3, true) +
																																										array(  "duration" => array( 'label' => __('Duration', 'woocommerce-appointments'), 'type' => 'number', 'placeholder' => __('N/A', 'woocommerce-appointments'), 'attributes' => array( 'min' => 0, 'step' => 1 ), 'class' => 'wcfm-text addon_duration', 'label_class' => 'wcfm_title addon_duration' )
																																												  ) +
																																										array_slice($product_addon_fields['_product_addons']['options']['options']['options'], 3, count($product_addon_fields['_product_addons']['options']['options']['options']) - 3, true) ;
  	
  	return $product_addon_fields;
  }
  
  /**
   * Handle Appointment confirmation
   */
  public function wcfm_appointment_mark_confirm() {
  	global $WCFM, $WCFMu;
  	
  	$appointment_id = $_POST['appointmentid'];
  	
  	$appointment = get_wc_appointment( $appointment_id );
		if ( 'confirmed' !== $appointment->get_status() ) {
			$appointment->update_status( 'confirmed' );
		}
		die;
  }
  
  /**
   * Handle Appointment Details Status Update
   */
  public function wcfm_modify_appointment_status() {
  	global $WCFM, $WCFMu;
  	
  	$appointment_id = $_POST['appointment_id'];
  	$appointment_status = $_POST['appointment_status'];
  	
  	$appointment = get_wc_appointment( $appointment_id );
  	$appointment->update_status( $appointment_status );
  	
		die;
  }
  
  function wcfm_manual_appointment_props( $new_appointment ) {
  	if( wcfm_is_vendor() ) {
  		$customer_id = $new_appointment->get_customer_id();
  		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
  		if( $vendor_id == $customer_id ) {
  			$new_appointment->set_customer_id( 0 );
  		}
  	}
  	return $new_appointment;
  }
  
  /**
	 * Add vendor email to appointment admin emails - 2.6.2
	 */
	public function wcfm_filter_appointment_emails( $recipients, $this_email ) {
		global $WCFMu;
		
		if ( ! empty( $this_email ) ) {
			if( $WCFMu->is_marketplace ) {
				if( $WCFMu->is_marketplace == 'wcmarketplace' ) {
				  $vendor = get_wcmp_product_vendors( $this_email->product_id );
					if( $vendor ) {
						$vendor_id = $vendor->id;
						$vendor_data = get_userdata( $vendor_id );
						if ( ! empty( $vendor_data ) ) {
							if ( isset( $recipients ) ) {
								$recipients .= ',' . $vendor_data->user_email;
							} else {
								$recipients = $vendor_data->user_email;
							}
						}
					}
				} elseif( $WCFMu->is_marketplace == 'wcvendors' ) {
					$product = get_post( $this_email->product_id );
					$vendor_id = $product->post_author;
					if( WCV_Vendors::is_vendor( $vendor_id ) ) {
						$vendor_data = get_userdata( $vendor_id );
						if ( ! empty( $vendor_data ) ) {
							if ( isset( $recipients ) ) {
								$recipients .= ',' . $vendor_data->user_email;
							} else {
								$recipients = $vendor_data->user_email;
							}
						}
					}
				} elseif( $WCFMu->is_marketplace == 'wcpvendors' ) {
					$vendor_id = WC_Product_Vendors_Utils::get_vendor_id_from_product( $this_email->product_id );
					$vendor_data = WC_Product_Vendors_Utils::get_vendor_data_by_id( $vendor_id );
		
					if ( ! empty( $vendor_id ) && ! empty( $vendor_data ) ) {
						if ( isset( $recipients ) ) {
							$recipients .= ',' . $vendor_data['email'];
						} else {
							$recipients = $vendor_data['email'];
						}
					}
				} elseif( $WCFM->is_marketplace == 'dokan' ) {
					$product = get_post( $this_email->product_id );
					$vendor_id = $product->post_author;
					if( dokan_is_user_seller( $vendor_id ) ) {
						$vendor_data = get_userdata( $vendor_id );
						if ( ! empty( $vendor_data ) ) {
							if ( isset( $recipients ) ) {
								$recipients .= ',' . $vendor_data->user_email;
							} else {
								$recipients = $vendor_data->user_email;
							}
						}
					}
				}
			}
		}

		return $recipients;
	}
	
	function wca_wcfm_message_types( $message_types ) {
  	
  	if( current_user_can( 'manage_appointments' ) ) {
  		$message_types['appointment'] = __( 'New Appointment', 'wc-frontend-manager-ultimate' );
  	}
  	
  	return $message_types;
  }
	
	/**
	 * Vendor direct message on new appointment - 3.0.6
	 */
	function wcfm_message_on_new_appointment( $appointment_id ) {
		global $WCFM, $wpdb;
  	
  	if( is_admin() ) return;
  	
  	$author_id = -2;
  	$author_is_admin = 1;
		$author_is_vendor = 0;
		$message_to = 0;
		
		if ( $appointment_id ) {
			
			$appointment_object = get_wc_appointment( $appointment_id );
			
			if ( ! is_object( $appointment_object ) ) {
				return;
			}
			
			if ( $appointment_object->has_status( 'in-cart' ) ) {
				//return;
			}
			
			$product_id = $appointment_object->get_product()->get_id();
			
			// Admin Notification
			$wcfm_messages = sprintf( __( 'You have received an Appointment <b>#%s</b> for <b>%s</b>', 'wc-frontend-manager-ultimate' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_appointment_url($appointment_id) . '">' . $appointment_id . '</a>', $appointment_object->get_product()->get_title() );
			$WCFM->frontend->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'appointment' );
			
			// Vendor Notification
			if( $WCFM->is_marketplace ) {
				$author_id = -1;
				$message_to = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			
				if( $message_to ) {
					$wcfm_messages = sprintf( __( 'You have received an Appointment <b>#%s</b> for <b>%s</b>', 'wc-frontend-manager-ultimate' ), '<a target="_blank" class="wcfm_dashboard_item_title" href="' . get_wcfm_view_appointment_url($appointment_id) . '">' . $appointment_id . '</a>', $appointment_object->get_product()->get_title() );
					$WCFM->frontend->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'appointment' );
				}
			}
		}
	}
}