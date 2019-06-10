<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Toolset Types Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers/thirdparty
 * @version   2.5.0
 */

class WCFMu_Toolset_Types_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM;
		
		// Third Party Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfm_toolset_types_products_manage_meta_save' ), 150, 2 );
	}
	
	/**
	 * Toolset Field Product Meta data save
	 */
	function wcfm_toolset_types_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM;
		
		if( isset( $wcfm_products_manage_form_data['wpcf'] ) && ! empty( $wcfm_products_manage_form_data['wpcf'] ) ) {
			foreach( $wcfm_products_manage_form_data['wpcf'] as $toolset_types_filed_key => $toolset_types_filed_value ) {
				update_post_meta( $new_product_id, $toolset_types_filed_key, $toolset_types_filed_value );
				if( is_array( $toolset_types_filed_value ) ) {
					delete_post_meta( $new_product_id, $toolset_types_filed_key );
					foreach( $toolset_types_filed_value as $toolset_types_filed_value_field ) {
						if( isset( $toolset_types_filed_value_field['field'] ) && !empty( $toolset_types_filed_value_field['field'] ) ) {
							add_post_meta( $new_product_id, $toolset_types_filed_key, $toolset_types_filed_value_field['field'] );
						}
					}
				}
			}
		}
	}
}