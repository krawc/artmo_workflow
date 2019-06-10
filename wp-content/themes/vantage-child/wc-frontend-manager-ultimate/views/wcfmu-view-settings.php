<?php
/**
 * WCFMu plugin Views
 *
 * Plugin Settings View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views
 * @version   2.2.6
 */
?>

<?php

/**
 * WCFM enpoints edit
 */
add_action( 'wcfm_settings_endpoints', 'wcfmu_settings_endpoints' );

function wcfmu_settings_endpoints() {
	global $WCFM, $WCFMu;
	
	$wcfm_endpoints = apply_filters( 'wcfm_endpoints_slug', array( 
													'wcfm-products'            => 'wcfm-products', //get_option( 'wcfm_products_endpoint', 'wc-products' ),
													'wcfm-products-manage'     => 'wcfm-products-manage', //get_option( 'wcfm_products_simple_endpoint', 'wc-products-simple' ),
													'wcfm-stock-manage'        => 'wcfm-stock-manage',
													
													// Import/Export
													'wcfm-products-import'     => 'wcfm-products-import',
													'wcfm-products-export'     => 'wcfm-products-export',
													
													// Coupon
													'wcfm-coupons'             => 'wcfm-coupons',
													'wcfm-coupons-manage'      => 'wcfm-coupons-manage',
													
													// Order
													'wcfm-orders'              => 'wcfm-orders',
													'wcfm-orders-details'      => 'wcfm-orders-details',
													
													// Reports
													'wcfm-reports-sales-by-date'   => 'wcfm-reports-sales-by-date',
													'wcfm-reports-out-of-stock'    => 'wcfm-reports-out-of-stock',
													
													// WCFMu Reports
													'wcfm-reports-sales-by-product' => 'wcfm-reports-sales-by-product',
													'wcfm-reports-coupons-by-date'  => 'wcfm-reports-coupons-by-date',
													'wcfm-reports-low-in-stock'     => 'wcfm-reports-low-in-stock',
													
													// Profile
													'wcfm-profile'  => 'wcfm-profile',
													
													// Settings
													'wcfm-settings'   => 'wcfm-settings',
													'wcfm-capability' => 'wcfm-capability',
													
													// Knowledgebase
													'wcfm-knowledgebase'         => 'wcfm-knowledgebase',
													'wcfm-knowledgebase-manage'  => 'wcfm-knowledgebase-manage',
													
													// Notices
													'wcfm-notices'        => 'wcfm-notices',
													'wcfm-notice-manage'  => 'wcfm-notice-manage',
													'wcfm-notice-view'    => 'wcfm-notice-view',
													
													// Messages
													'wcfm-messages'  => 'wcfm-messages',
												) );
	
	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
	
	$wcfm_endpoints_edit_fileds = array();
	
	foreach( $wcfm_endpoints as $wcfm_endpoint_key => $wcfm_endpoint_val ) {
		$wcfm_endpoints_edit_fileds[$wcfm_endpoint_key] = array( 'label' => $wcfm_endpoint_key, 'name' => 'wcfm_endpoints[' . $wcfm_endpoint_key . ']','type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'placeholder' => $wcfm_endpoint_val, 'value' => ! empty( $wcfm_modified_endpoints[$wcfm_endpoint_key] ) ? $wcfm_modified_endpoints[$wcfm_endpoint_key] : '', 'label_class' => 'wcfm_title' );
	}
	$WCFM->wcfm_fields->wcfm_generate_form_field( $wcfm_endpoints_edit_fileds );
}