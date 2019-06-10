<?php

/**
 * WCFMu plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	WCFMu/core
 * @version   1.0.0
 */
 
class WCFMu_Library {
	
	public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $js_lib_path;
  
  public $js_lib_url;
  
  public $css_lib_path;
  
  public $css_lib_url;
  
  public $views_path;
	
	public function __construct() {
    global $WCFMu;
		
	  $this->lib_path = $WCFMu->plugin_path . 'assets/';

    $this->lib_url = $WCFMu->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->views_path = $WCFMu->plugin_path . 'views/';
    
    // Load WCFMu Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load WCFMu Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
    
    // Load WCFMu views
    add_action( 'wcfm_load_views', array( &$this, 'load_views' ) );
    add_action( 'before_wcfm_load_views', array( &$this, 'load_views' ) );
    //add_action( 'after_wcfm_load_views', array( &$this, 'after_load_views' ) );
    
    // Product Manage Ultimate View
    add_action( 'end_wcfm_products_manage', array( &$this, 'end_wcfm_products_manage_form_load_views' ) );
    
    // Coupon Manage Ultimate View
    add_action( 'end_wcfm_coupons_manage_form', array( &$this, 'end_wcfm_coupons_manage_form_load_views' ) );
    
    // Order Details Ultimate View
    add_action( 'end_wcfm_orders_details', array( &$this, 'end_wcfm_orders_details_load_views' ) );
	}
	
	public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	
	  	case 'wcfm-dashboard':
        //wp_enqueue_script( 'wcfmu_piechart_js', '//www.gstatic.com/charts/loader.js', array(), $WCFM->version, true );
        //wp_enqueue_script( 'wcfmu_dashboard_js', $this->js_lib_url . 'wcfmu-script-dashboard.js', array( 'jquery', 'wcfm_dashboard_js', 'jquery-flot_js' ), $WCFMu->version, true );
      break;
	  	
	    case 'wc-products':
	    case 'wcfm-products':
	    	wp_enqueue_script( 'wcfmu_products_js', $this->js_lib_url . 'wcfmu-script-products.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-products-manage':
      	wp_enqueue_script( 'wcfmu_products_manage_js', $this->js_lib_url . 'wcfmu-script-products-manage.js', array('jquery', 'select2_js', 'wcfm_products_manage_js' ), $WCFMu->version, true );
      	
		  	// WC Subscriptions Support 
		  	if( wcfm_is_subscription() ) {
		  		wp_enqueue_script( 'wcfmu_wcsubscriptions_products_manage_js', $this->js_lib_url . 'wcfmu-script-wcsubscriptions-products-manage.js', array('jquery', 'wcfm_products_manage_js'), $WCFMu->version, true );
		  	}
      break;
      
    	case 'wcfm-stock-manage':
    		$WCFM->library->load_select2_lib();
        $WCFM->library->load_datatable_lib();
    		wp_enqueue_script( 'wcfmu_stock_manage_js', $this->js_lib_url . 'wcfmu-script-stock-manage.js', array('jquery'), $WCFMu->version, true );
    		
    		$wcfm_screen_manager_data = array();
    		if( !$WCFMu->is_marketplace || wcfm_is_vendor() ) {
	    		$wcfm_screen_manager_data[4] = 'yes';
	    	}
	    	$wcfm_screen_manager_data = apply_filters( 'wcfm_stocks_screen_manage', $wcfm_screen_manager_data );
	    	wp_localize_script( 'wcfmu_stock_manage_js', 'wcfm_stocks_screen_manage', $wcfm_screen_manager_data );
    	break;
    	
      case 'wcfm-products-import':
	    	//wp_register_script( 'wc-product-import', $this->js_lib_url . 'wcfmu-script-products-import.js', array('jquery'), $WCFMu->version, true );
	    break;
        
      case 'wcfm-coupons':
	    	wp_enqueue_script( 'wcfmu_coupons_js', $this->js_lib_url . 'wcfmu-script-coupons.js', array('jquery'), $WCFMu->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager = (array) get_option( 'wcfm_screen_manager' );
	    	$wcfm_screen_manager_data = array();
	    	if( isset( $wcfm_screen_manager['coupon'] ) ) $wcfm_screen_manager_data = $wcfm_screen_manager['coupon'];
	    	if( !isset( $wcfm_screen_manager_data['admin'] ) ) {
					$wcfm_screen_manager_data['admin'] = $wcfm_screen_manager_data;
					$wcfm_screen_manager_data['vendor'] = $wcfm_screen_manager_data;
				}
				if( wcfm_is_vendor() ) {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['vendor'];
				} else {
					$wcfm_screen_manager_data = $wcfm_screen_manager_data['admin'];
				}
	    	wp_localize_script( 'wcfmu_coupons_js', 'wcfm_coupons_screen_manage', $wcfm_screen_manager_data );
      break;
      
      case 'wcfm-coupons-manage':
      	$WCFM->library->load_select2_lib();
      	wp_enqueue_script( 'wcfmu_coupons_manage_js', $this->js_lib_url . 'wcfmu-script-coupons-manage.js', array('jquery', 'select2_js'), $WCFM->version, true );
      break;
      
      case 'wcfm-orders':
	    	wp_enqueue_script( 'wcfmu_orders_js', $this->js_lib_url . 'wcfmu-script-orders.js', array('jquery'), $WCFMu->version, true );
	    	// Shipping Tracking Label
	    	if( wcfm_is_vendor() ) {
					$tracking_labels = get_wcfm_shipping_tracking_labels();
					wp_localize_script( 'wcfmu_orders_js', 'wcfm_shipping_tracking_labels', $tracking_labels );
				}
      break;
      
      case 'wcfm-orders-details':
	    	wp_enqueue_script( 'wcfmu_orders_details_js', $this->js_lib_url . 'wcfmu-script-orders-details.js', array('jquery'), $WCFMu->version, true );
	    	// Shipping Tracking Label
	    	if( wcfm_is_vendor() ) {
					$tracking_labels = get_wcfm_shipping_tracking_labels();
					wp_localize_script( 'wcfmu_orders_details_js', 'wcfm_shipping_tracking_labels', $tracking_labels );
				}
      break;
      
      case 'wcfm-reports-sales-by-date':
      	$WCFM->library->load_datepicker_lib();
      	wp_enqueue_script( 'wcfmu_reports_js', $this->js_lib_url . 'wcfmu-script-reports-sales-by-date.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-reports-sales-by-product':
      	$WCFM->library->load_chartjs_lib();
      	$WCFM->library->load_datepicker_lib();
      	wp_enqueue_script( 'wcfmu_reports_js', $this->js_lib_url . 'wcfmu-script-reports-sales-by-date.js', array('jquery'), $WCFMu->version, true );
        //wp_enqueue_script( 'wcfm_reports_js', $this->js_lib_url . 'wcfmu-script-reports-sales-by-product.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-reports-coupons-by-date':
      	$WCFM->library->load_chartjs_lib();
      	$WCFM->library->load_datepicker_lib();
      	wp_enqueue_script( 'wcfmu_reports_js', $this->js_lib_url . 'wcfmu-script-reports-sales-by-date.js', array('jquery'), $WCFMu->version, true );
      	//$WCFM->library->load_select2_lib();
        //wp_enqueue_script( 'wcfm_reports_js', $this->js_lib_url . 'wcfmu-script-reports-coupons-by-date.js', array('jquery'), $WCFMu->version, true );
      break;
      
      case 'wcfm-reports-low-in-stock':
      	$WCFM->library->load_datatable_lib();
      	$WCFM->library->load_datatable_download_lib();
        wp_enqueue_script( 'wcfm_reports_js', $this->js_lib_url . 'wcfmu-script-reports-low-in-stock.js', array('jquery', 'dataTables_js'), $WCFMu->version, true );
      break;
      
      case 'wcfm-settings':
      	if( !wcfm_is_vendor() ) {
					wp_enqueue_script( 'wcfmu_settings_js', $this->js_lib_url . 'wcfmu-script-settings.js', array('jquery'), $WCFMu->version, true );
				}
      break;
    }
	}
	
	public function load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-dashboard':
	    	//wp_enqueue_style( 'wcfmu_dashboard_css',  $this->css_lib_url . 'wcfmu-style-dashboard.css', array('wcfm_dashboard_css'), $WCFMu->version );
		  break;
		  
	    case 'wc-products':
	    case 'wcfm-products':
	    	//wp_enqueue_style( 'wcfmu_products_css',  $this->css_lib_url . 'wcfmu-style-products.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-stock-manage':
    		wp_enqueue_style( 'wcfmu_stock_manage_css', $this->css_lib_url . 'wcfmu-style-stock-manage.css', array(), $WCFMu->version );
    	break;
		  
		  case 'wcfm-products-import':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMu->version );
	    	wp_enqueue_style( 'wcfmu_products_impoprt_css',  $this->css_lib_url . 'wcfmu-style-products-import.css', array(), $WCFMu->version );
		  break;
		    
		  case 'wcfm-coupons':
	    	wp_enqueue_style( 'wcfmu_coupons_css',  $this->css_lib_url . 'wcfmu-style-coupons.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-orders':
	    	wp_enqueue_style( 'wcfmu_orders_css',  $this->css_lib_url . 'wcfmu-style-orders.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-orders-details':
	    	wp_enqueue_style( 'wcfmu_orders_details_css',  $this->css_lib_url . 'wcfmu-style-orders-details.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-reports-sales-by-date':
		    wp_enqueue_style( 'wcfmu_reports_css',  $this->css_lib_url . 'wcfmu-style-reports-sales-by-date.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-reports-sales-by-product':
		  	wp_enqueue_style( 'reports_menus_css',  $WCFM->library->css_lib_url . 'wcfm-style-reports-menus.css', array(), $WCFMu->version );
		    wp_enqueue_style( 'wcfmu_reports_css',  $this->css_lib_url . 'wcfmu-style-reports-sales-by-product.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-reports-coupons-by-date':
		  	wp_enqueue_style( 'reports_menus_css',  $WCFM->library->css_lib_url . 'wcfm-style-reports-menus.css', array(), $WCFMu->version );
		    wp_enqueue_style( 'wcfmu_reports_css',  $this->css_lib_url . 'wcfmu-style-reports-coupons-by-date.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-reports-low-in-stock':
		  	wp_enqueue_style( 'reports_menus_css',  $WCFM->library->css_lib_url . 'wcfm-style-reports-menus.css', array(), $WCFMu->version );
		  	wp_enqueue_style( 'dataTables_css',  $WCFM->library->css_lib_url . 'jquery.dataTables.min.css', array(), $WCFMu->version );
		  break;
		  
		  case 'wcfm-profile':
      	if( wcfm_is_vendor() ) {
					wp_enqueue_style( 'wcfmu_profile_css',  $this->css_lib_url . 'wcfmu-style-profile.css', array(), $WCFMu->version );
				}
      break;
		}
	}
	
	public function load_views( $end_point ) {
	  global $WCFM, $WCFMu;
	  
	  switch( $end_point ) {
	  	
	    case 'wc-products':
	    case 'wcfm-products':
        //require_once( $this->views_path . 'wcfmu-view-products.php' );
      break;
      
      case 'wcfm-stock-manage':
    		require_once( $this->views_path . 'wcfmu-view-stock-manage.php' );
    	break;
      
      case 'wcfm-products-import':
        require_once( $this->views_path . 'wcfmu-view-products-import.php' );
      break;
        
      case 'wcfm-coupons':
        require_once( $this->views_path . 'wcfmu-view-coupons.php' );
      break;
      
      case 'wcfm-orders':
        require_once( $this->views_path . 'wcfmu-view-orders.php' );
      break;
      
      case 'wcfm-reports-sales-by-product':
      	require_once( $this->views_path . 'wcfmu-view-reports-sales-by-product.php' );
      break;
      
      case 'wcfm-reports-coupons-by-date':
      	require_once( $this->views_path . 'wcfmu-view-reports-coupons-by-date.php' );
      break;
      
      case 'wcfm-reports-low-in-stock':
        require_once( $this->views_path . 'wcfmu-view-reports-low-in-stock.php' );
      break;
      
      case 'wcfm-settings':
      	if( !wcfm_is_vendor() ) {
					require_once( $this->views_path . 'wcfmu-view-settings.php' );
				}
      break;
      
      case 'wcfm-capability':
      	if( !wcfm_is_vendor() ) {
					require_once( $this->views_path . 'wcfmu-view-capability.php' );
				}
      break;
    }
	}
	
	/**
	 * Fancybox library
	*/
	public function load_fancybox_lib() {
	  global $WCFMu;
	  wp_enqueue_script( 'wcfm_fancybox_js', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js', array('jquery'), $WCFMu->version, true );
	  wp_enqueue_style( 'wcfm_fancybox_css',  'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css', array(), $WCFMu->version );
	}
	
	/**
	 * PopModal library
	*/
	public function load_popmodal_lib() {
	  global $WCFMu;
	  wp_enqueue_script( 'popmodal_js', $WCFMu->plugin_url . 'includes/libs/popModal/popModal.min.js', array('jquery'), $WCFMu->version, true );
	  wp_enqueue_style( 'popmodal_css',  $WCFMu->plugin_url . 'includes/libs/popModal/popModal.min.css', array(), $WCFMu->version );
	}
	
	/**
	 * Full Calendar library
	*/
	public function load_fullcalendar_lib() {
	  global $WCFMu;
	  wp_enqueue_script( 'wcfm_moment_js', $WCFMu->plugin_url . 'includes/libs/fullcalendar/moment.js', array('jquery'), $WCFMu->version, true );
	  //wp_enqueue_script( 'wcfm_qtip2_js', '//cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.js', array('jquery','wcfm_moment_js'), $WCFMu->version, true );
	  wp_enqueue_script( 'wcfm_fullcalendar_js', $WCFMu->plugin_url . 'includes/libs/fullcalendar/fullcalendar.min.js', array('jquery','wcfm_moment_js'), $WCFMu->version, true );
	  //wp_enqueue_script( 'wcfm_magnific_js', $WCFMu->plugin_url . 'includes/libs/fullcalendar/jquery.magnific-popup.min.js', array('jquery'), $WCFMu->version, true );
	  
	  //wp_enqueue_style( 'wcfm_qtip2_css',  '//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css', array(), $WCFMu->version );
	  //wp_enqueue_style( 'wcfm_magnific_css',  $WCFMu->plugin_url . 'includes/libs/fullcalendar/magnific-popup.css', array(), $WCFMu->version );
	  wp_enqueue_style( 'wcfm_fullcalendar_css',  $WCFMu->plugin_url . 'includes/libs/fullcalendar/fullcalendar.css', array(), $WCFMu->version );
	}
	
	function end_wcfm_products_manage_form_load_views( ) {
		global $WCFMu;
	  
	 require_once( $this->views_path . 'wcfmu-view-products-manage.php' );
	}
	
	public function end_wcfm_coupons_manage_form_load_views( ) {
	  global $WCFMu;
	  
	 require_once( $this->views_path . 'wcfmu-view-coupons-manage.php' );
	}
	
	public function end_wcfm_orders_details_load_views( ) {
	  global $WCFMu;
	  
	 require_once( $this->views_path . 'wcfmu-view-orders-details.php' );
	}
}