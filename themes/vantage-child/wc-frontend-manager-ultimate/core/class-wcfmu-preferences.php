<?php
/**
 * WCFM plugin core
 *
 * Plugin WCFMu Preferences Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   3.2.10
 */
 
class WCFMu_Preferences {
	
	private $wcfm_module_options = array();

	public function __construct() {
		global $WCFM, $WCFMu;
		
		$wcfm_options = (array) get_option( 'wcfm_options' );
		$this->wcfm_module_options = isset( $wcfm_options['module_options'] ) ? $wcfm_options['module_options'] : array();
		$this->wcfm_module_options = apply_filters( 'wcfm_module_options', $this->wcfm_module_options );
		
		add_filter( 'wcfm_is_pref_vendor_badges', array( &$this, 'wcfmpref_vendor_badges' ), 750 );
		
		add_filter( 'wcfm_is_pref_vendor_verification', array( &$this, 'wcfmpref_vendor_verification' ), 750 );
	}
	
	// Vendor Badges
  function wcfmpref_vendor_badges( $is_pref ) {
  	$vendor_badges = ( isset( $this->wcfm_module_options['vendor_badges'] ) ) ? $this->wcfm_module_options['vendor_badges'] : 'no';
  	if( $vendor_badges == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
	
	// Vendor Verification
  function wcfmpref_vendor_verification( $is_pref ) {
  	$vendor_verification = ( isset( $this->wcfm_module_options['vendor_verification'] ) ) ? $this->wcfm_module_options['vendor_verification'] : 'no';
  	if( $vendor_verification == 'yes' ) $is_pref = false;
  	return $is_pref;
  }
  
}