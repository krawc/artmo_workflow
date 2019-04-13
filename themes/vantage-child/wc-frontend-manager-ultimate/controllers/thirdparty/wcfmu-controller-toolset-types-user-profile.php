<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Toolset Types User Profile Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers/thirdparty
 * @version   3.0.1
 */

class WCFMu_Toolset_Types_User_Profile_Controller {
	
	public function __construct() {
		global $WCFM;
		
		// Third Party Product Meta Data Save
    add_action( 'wcfm_profile_update', array( &$this, 'wcfm_toolset_types_user_profile_meta_save' ), 150, 2 );
    add_action( 'wcfm_customers_manage', array( &$this, 'wcfm_toolset_types_user_profile_meta_save' ), 150, 2 );
	}
	
	/**
	 * Toolset Field User Meta data save
	 */
	function wcfm_toolset_types_user_profile_meta_save( $user_id, $wcfm_profile_form ) {
		global $WCFM;
		
		if( isset( $wcfm_profile_form['wpcf'] ) && ! empty( $wcfm_profile_form['wpcf'] ) ) {
			foreach( $wcfm_profile_form['wpcf'] as $toolset_types_filed_key => $toolset_types_filed_value ) {
				update_user_meta( $user_id, $toolset_types_filed_key, $toolset_types_filed_value );
				if( is_array( $toolset_types_filed_value ) ) {
					delete_user_meta( $user_id, $toolset_types_filed_key );
					foreach( $toolset_types_filed_value as $toolset_types_filed_value_field ) {
						if( isset( $toolset_types_filed_value_field['field'] ) && !empty( $toolset_types_filed_value_field['field'] ) ) {
							add_user_meta( $user_id, $toolset_types_filed_key, $toolset_types_filed_value_field['field'] );
						}
					}
				}
			}
		}
	}
}