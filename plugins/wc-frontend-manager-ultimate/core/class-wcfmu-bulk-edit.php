<?php
/**
 * WCFMu plugin core
 *
 * Plugin Bulk Edit Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   3.2.4
 */
 
class WCFMu_Bulk_Edit {

	public function __construct() {
		
		add_action( 'wcfm_product_filters_before', array( &$this, 'wcfm_bulk_action_button' ) );
		
		// Load WCFMu Scripts
		add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
		add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
		
		// Load WCFMu Styles
		add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
		add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
		
		// Generate Bulk Edit Html
    add_action('wp_ajax_wcfmu_bulk_edit_html', array( &$this, 'wcfmu_bulk_edit_html' ) );
    
    // Bulk Edit Ajax Controllers
	  add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmu_bulk_edit_ajax_controller' ) );
	}
	
	function wcfm_bulk_action_button() {
		?>
		<input type="submit" id="wcfm_bulk_edit" class="wcfm_bulk_edit wcfm_submit_button" value="<?php _e( 'Bulk Edit', 'wc-frontend-manager-ultimate' ); ?>" />
		<?php
	}
	
	public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	
	    case 'wcfm-products':
	    	wp_enqueue_script( 'wcfmu_products_bulk_edit_js', $WCFMu->library->js_lib_url . 'products/wcfmu-script-products-bulk-edit.js', array('jquery'), $WCFMu->version, true );
	    break;
	  }
	}
	
	public function load_styles( $end_point ) {
	  global $WCFM, $WCFMu;
		
	  switch( $end_point ) {
	  	
	    case 'wcfm-products':
	    	wp_enqueue_style( 'wcfmu_products_bulk_edit_css',  $WCFMu->library->css_lib_url . 'products/wcfmu-style-products-bulk-edit.css', array(), $WCFMu->version );
		  break;
		}
	}
	
	/**
	 * Generate Product Bulk Edit HTMl
	 */
	function wcfmu_bulk_edit_html() {
		global $WCFM, $WCFMu;
		
		include_once( $WCFMu->library->views_path . 'products/wcfmu-view-products-bulk-manage.php' );
		die;
	}
	
	/**
   * WCFM Bulk Edit Ajax Controllers
   */
  public function wcfmu_bulk_edit_ajax_controller() {
  	global $WCFM, $WCFMu;
  	
  	$controllers_path = $WCFMu->plugin_path . 'controllers/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-products-bulk-manage':
  				include_once( $controllers_path . 'products/wcfmu-controller-products-bulk-manage.php' );
					new WCFMu_Products_Bulk_Manage_Controller();
				break;
			}
		}
	}
}