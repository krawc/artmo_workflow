<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Per Product Shipping Products Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/thirdparty
 * @version   2.5.0
 */


add_filter( 'wcfm_product_manage_fields_shipping', 'wcfm_product_manage_fields_shipping_per_product', 10, 2 );

function wcfm_product_manage_fields_shipping_per_product( $shipping_fields, $product_id ) {
	global $wp, $WCFM, $WCFMu, $wpdb;
	
	$per_product_shipping_table = 'wcpv_per_product_shipping_rules';
	if( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
		$per_product_shipping_table = 'woocommerce_per_product_shipping_rules';
	}
	
	
	$_per_product_shipping = 'no';
	$_per_product_shipping_add_to_all = 'no';
	$per_product_shipping_rules = array();

	if( $product_id ) {
		if( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
			$_per_product_shipping = get_post_meta( $product_id, '_per_product_shipping', true ) ? get_post_meta( $product_id, '_per_product_shipping', true ) : 'no';
			$_per_product_shipping_add_to_all = get_post_meta( $product_id, '_per_product_shipping_add_to_all', true ) ? get_post_meta( $product_id, '_per_product_shipping_add_to_all', true ) : 'no';
		}
		
		$rules = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$per_product_shipping_table} WHERE product_id = %d ORDER BY rule_order;", $product_id ) );

		if( !empty($rules) ) {
			foreach ( $rules as $rule ) {
				$per_product_shipping_rules[] = array( 'country'   => esc_attr( $rule->rule_country ), 
																							 'state'     => esc_attr( $rule->rule_state ),
																							 'postcode'  => esc_attr( $rule->rule_postcode ),
																							 'cost'      => esc_attr( $rule->rule_cost ),
																							 'item_cost' => esc_attr( $rule->rule_item_cost ),
																							 'item_id'   => $rule->rule_id,
																							 );
			}
		}
	}
	$per_product_shipping_fields = array( 
		"_per_product_shipping"            => array( 'label' => __( 'Per-product shipping', 'woocommerce-shipping-per-product' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $_per_product_shipping, 'hints' => __( 'Enable per-product shipping cost', 'woocommerce-shipping-per-product' ) ),
		//"_per_product_shipping_add_to_all" => array( 'label' => __( 'Adjust Shipping Costs', 'woocommerce-shipping-per-product' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $_per_product_shipping_add_to_all, 'hints' => __( 'Add per-product shipping cost to all shipping method rates?', 'woocommerce-shipping-per-product' ) ),
		"_per_product_shipping_rules"      => array('label' => __('Shipping Rules', 'wc-frontend-manager-ultimate') , 'type' => 'multiinput', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $per_product_shipping_rules, 'options' => array(
																								"country" => array('label' => __('Country Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'A 2 digit country code, e.g. US. Leave blank to apply to all.', 'woocommerce-shipping-per-product' ) ),
																								"state" => array('label' => __('State/County Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'A state code, e.g. AL. Leave blank to apply to all.', 'woocommerce-shipping-per-product' ) ),
																								"postcode" => array('label' => __('Zip/Postal Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Postcode for this rule. Wildcards (*) can be used. Leave blank to apply to all areas.', 'woocommerce-shipping-per-product' ) ),
																								"cost" => array('label' => __('Line Cost (Excl. Tax)', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '0.00', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Decimal cost for the line as a whole.', 'woocommerce-shipping-per-product' ) ),
																								"item_cost" => array('label' => __('Item Cost (Excl. Tax)', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '0.00', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Decimal cost for the item (multiplied by qty).', 'woocommerce-shipping-per-product' ) ),
																								"item_id" => array( 'type' => 'hidden' )
																								)	)								
		);
	
	if( !WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
		unset( $per_product_shipping_fields['_per_product_shipping'] );
		//unset( $per_product_shipping_fields['_per_product_shipping_add_to_all'] );
	}
	
	$shipping_fields = array_merge( $shipping_fields, $per_product_shipping_fields );
	
	return $shipping_fields;
}

// WC Per Product Shipping Variaton Date Edit
add_filter( 'wcfm_variation_edit_data', 'wcfmu_thirdparty_per_product_shipping_data_variations', 10, 3 );

function wcfmu_thirdparty_per_product_shipping_data_variations( $variations, $variation_id, $variation_id_key ) {
	global $wp, $WCFM, $WCFMu, $wpdb;
	
	$per_product_shipping_table = 'wcpv_per_product_shipping_rules';
	if( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
		$per_product_shipping_table = 'woocommerce_per_product_shipping_rules';
	}
	
	if( $variation_id  ) {
		if( WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
			$variations[$variation_id_key]['_per_product_shipping'] = get_post_meta( $variation_id, '_per_product_shipping', true ) ? get_post_meta( $variation_id, '_per_product_shipping', true ) : 'no';
			//$variations[$variation_id_key]['_per_product_shipping_add_to_all'] = get_post_meta( $variation_id, '_per_product_shipping_add_to_all', true ) ? get_post_meta( $variation_id, '_per_product_shipping_add_to_all', true ) : 'no';
		}
		
		$rules = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$per_product_shipping_table} WHERE product_id = %d ORDER BY rule_order LIMIT 1;", $variation_id ) );

		if( !empty($rules) ) {
			foreach ( $rules as $rule ) {
				$variations[$variation_id_key]['_per_product_shipping_country']   = esc_attr( $rule->rule_country ); 
				$variations[$variation_id_key]['_per_product_shipping_state']     = esc_attr( $rule->rule_state );
				$variations[$variation_id_key]['_per_product_shipping_postcode']  = esc_attr( $rule->rule_postcode );
				$variations[$variation_id_key]['_per_product_shipping_cost']      = esc_attr( $rule->rule_cost );
				$variations[$variation_id_key]['_per_product_shipping_item_cost'] = esc_attr( $rule->rule_item_cost );
				$variations[$variation_id_key]['_per_product_shipping_item_id']   = $rule->rule_id;
			}
		}
	}
	return $variations;
}

// WC Per Product Shipping Variation View
add_filter( 'wcfm_product_manage_fields_variations', 'wcfmu_shipping_per_product_shipping_manage_fields_variations', 150, 4 );

function wcfmu_shipping_per_product_shipping_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
	global $wp, $WCFM, $WCFMu, $wpdb;
	
	$per_product_shipping_fields = array( 
																			"_per_product_shipping"            => array( 'label' => __( 'Per-product shipping', 'woocommerce-shipping-per-product' ) , 'type' => 'checkbox wcfm_ele variable', 'class' => 'wcfm-checkbox wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable checkbox_title', 'value' => 'yes', 'hints' => __( 'Enable per-product shipping cost', 'woocommerce-shipping-per-product' ) ),
																			//"_per_product_shipping_add_to_all" => array( 'label' => __( 'Adjust Shipping Costs', 'woocommerce-shipping-per-product' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'hints' => __( 'Add per-product shipping cost to all shipping method rates?', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_country" => array('label' => __('Country Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'A 2 digit country code, e.g. US. Leave blank to apply to all.', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_state" => array('label' => __('State/County Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'A state code, e.g. AL. Leave blank to apply to all.', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_postcode" => array('label' => __('Zip/Postal Code', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '*', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'Postcode for this rule. Wildcards (*) can be used. Leave blank to apply to all areas.', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_cost" => array('label' => __('Line Cost (Excl. Tax)', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '0.00', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'Decimal cost for the line as a whole.', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_item_cost" => array('label' => __('Item Cost (Excl. Tax)', 'woocommerce-shipping-per-product'), 'type' => 'text', 'placeholder' => '0.00', 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title wcfm_ele variable', 'hints' => __( 'Decimal cost for the item (multiplied by qty).', 'woocommerce-shipping-per-product' ) ),
																			"_per_product_shipping_item_id" => array( 'type' => 'hidden' )
																		);
	
	if( !WCFMu_Dependencies::wcfm_wc_per_peroduct_shipping_active_check() ) {
		unset( $per_product_shipping_fields['_per_product_shipping'] );
		//unset( $per_product_shipping_fields['_per_product_shipping_add_to_all'] );
	}
	
	$variation_fileds = array_merge( $variation_fileds, $per_product_shipping_fields );
	
	return $variation_fileds;
}
?>