<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Advanced Custom Fields(ACF) Pro Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers/thirdparty
 * @version   3.3.7
 */

class WCFMu_ACF_Pro_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM;
		
		// Third Party Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfm_acf_products_manage_meta_save' ), 160, 2 );
	}
	
	/**
	 * ACF Field Product Meta data save
	 */
	function wcfm_acf_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM;
		
		if( isset( $wcfm_products_manage_form_data['acf'] ) && ! empty( $wcfm_products_manage_form_data['acf'] ) ) {
			foreach( $wcfm_products_manage_form_data['acf'] as $acf_filed_key => $acf_filed_value ) {
				update_post_meta( $new_product_id, $acf_filed_key, $acf_filed_value );
			}
			
			// For saving only Image & File fields - 3.3.7
			$field_groups = acf_get_field_groups();
			if( !empty( $field_groups )) {
				foreach( $field_groups as $field_group_index => $field_group ) {
					
					if( !$field_group['active'] ) continue;
					if( empty($field_group['location']) ) continue;
					
					$is_visible = false;
					foreach( $field_group['location'] as $group_id => $group ) {
						if( empty($group) ) continue;
						foreach( $group as $rule_id => $rule ) {
							switch($rule['param']) {
								case 'post_type' :
									if( ( $rule['operator'] == '==' ) && ( $rule['value'] == 'product' ) ) {
										$is_visible = true;
									}
								break;
							}
						}
					}
					if( !$is_visible ) continue;
					
					$wcfm_is_allowed_acf_field_group = apply_filters( 'wcfm_is_allowed_acf_field_group', true );
					if( !$wcfm_is_allowed_acf_field_group ) continue;
					$field_group_fields = acf_get_fields( $field_group );
					//print_r($field_group_fields);
					if ( !empty( $field_group_fields ) ) {
						if ( !empty( $field_group_fields ) ) {
							foreach( $field_group_fields as $field_group_field ) {
								if( $field_group_field['type'] == 'image' || $field_group_field['type'] == 'file' ) {
									if( isset( $wcfm_products_manage_form_data['acf'][$field_group_field['name']] ) && ! empty( $wcfm_products_manage_form_data['acf'][$field_group_field['name']] ) ) {
										$uploaded_file_id = $WCFM->wcfm_get_attachment_id( $wcfm_products_manage_form_data['acf'][$field_group_field['name']] );
										update_post_meta( $new_product_id, $field_group_field['name'], $uploaded_file_id );
									}
								} elseif( $field_group_field['type'] == 'checkbox' || $field_group_field['type'] == 'true_false' || $field_group_field['type'] == 'radio' ) {
									if( !isset( $wcfm_products_manage_form_data['acf'][$field_group_field['name']] ) && empty( $wcfm_products_manage_form_data['acf'][$field_group_field['name']] ) ) {
										update_post_meta( $new_product_id, $field_group_field['name'], '' );
									}
								}
							}
						}
					}
				}
			}
		}
	}
}