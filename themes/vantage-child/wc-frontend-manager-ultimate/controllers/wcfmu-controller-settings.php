<?php
/**
 * WCFMu plugin controllers
 *
 * Plugin Settings Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers
 * @version   2.2.6
 */

class WCFMu_Settings_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		add_action( 'wcfm_settings_update', array( &$this, 'wcfmu_settings_update' ) );
	}
	
	function wcfmu_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMu, $WCFM_Query;
		
		if( isset( $wcfm_settings_form['wcfm_endpoints'] ) ) {
			update_option( 'wcfm_endpoints', $wcfm_settings_form['wcfm_endpoints'] );
			
			// Intialize WCFM End points
			$WCFM_Query->init_query_vars();
			$WCFM_Query->add_endpoints();
		
			// Flush rules after endpoint update
			flush_rewrite_rules();
		}
	}
	
}