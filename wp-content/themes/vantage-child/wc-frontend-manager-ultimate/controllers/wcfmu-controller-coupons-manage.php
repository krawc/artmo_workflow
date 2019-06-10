<?php
/**
 * WCFMu plugin controllers
 *
 * Plugin Coupons Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers
 * @version   1.0.0
 */

class WCFMu_Coupons_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		add_filter( 'wcfm_coupon_data_factory', array( &$this, 'wcfmu_coupon_data_factory' ), 10, 3);
		
	}
	
	public function wcfmu_coupon_data_factory( $wcfm_coupon_data, $new_coupon_id, $wcfm_coupon_manager_form_data ) {
		global $WCFM, $WCFMu;
		
		$product_categories         = isset( $wcfm_coupon_manager_form_data['product_categories'] ) ? (array) $wcfm_coupon_manager_form_data['product_categories'] : array();
		$exclude_product_categories = isset( $wcfm_coupon_manager_form_data['exclude_product_categories'] ) ? (array) $wcfm_coupon_manager_form_data['exclude_product_categories'] : array();
		
		$wcfmu_coupon_data = array(
															'individual_use'              => isset( $wcfm_coupon_manager_form_data['individual_use'] ),
															'product_ids'                 => isset( $wcfm_coupon_manager_form_data['product_ids'] ) ? array_filter( array_map( 'intval', (array) $wcfm_coupon_manager_form_data['product_ids'] ) ) : array(),
															'excluded_product_ids'        => isset( $wcfm_coupon_manager_form_data['exclude_product_ids'] ) ? array_filter( array_map( 'intval', (array) $wcfm_coupon_manager_form_data['exclude_product_ids'] ) ) : array(),
															'usage_limit'                 => absint( $wcfm_coupon_manager_form_data['usage_limit'] ),
															'usage_limit_per_user'        => absint( $wcfm_coupon_manager_form_data['usage_limit_per_user'] ),
															'limit_usage_to_x_items'      => absint( $wcfm_coupon_manager_form_data['limit_usage_to_x_items'] ),
															'product_categories'          => array_filter( array_map( 'intval', $product_categories ) ),
															'excluded_product_categories' => array_filter( array_map( 'intval', $exclude_product_categories ) ),
															'exclude_sale_items'          => isset( $wcfm_coupon_manager_form_data['exclude_sale_items'] ),
															'minimum_amount'              => wc_format_decimal( $wcfm_coupon_manager_form_data['minimum_amount'] ),
															'maximum_amount'              => wc_format_decimal( $wcfm_coupon_manager_form_data['maximum_amount'] ),
															'email_restrictions'          => array_filter( array_map( 'trim', explode( ',', wc_clean( $wcfm_coupon_manager_form_data['customer_email'] ) ) ) ),
														);
		$wcfm_coupon_data = array_merge( $wcfm_coupon_data, $wcfmu_coupon_data );
		
		return $wcfm_coupon_data;
	}
	
}