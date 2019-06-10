<?php

/**
 * WCFMu plugin core
 *
 * WC Subscriptions Support
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   2.2.2
 */
 
class WCFMu_WCSubscriptions {
	
	public function __construct() {
    global $WCFM, $WCFMu;
    
    if( wcfm_is_subscription() ) {
    	
    	// Bookable Product Type
    	add_filter( 'wcfm_product_types', array( &$this, 'wcs_product_types' ), 40 );
    	
    	
    	// Subscriptions Product options
    	add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcs_product_manage_fields_general' ), 40, 2 );
    	add_filter( 'wcfm_product_manage_fields_shipping', array( &$this, 'wcs_product_manage_fields_shipping' ), 40, 2 );
    	add_filter( 'wcfm_product_manage_fields_advanced', array( &$this, 'wcs_product_manage_fields_advanced' ), 40, 2 );
    	add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcs_product_manage_fields_variations' ), 40, 4 );
    	
    	// Subscriptions Product Meta Data Save
    	add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcs_wcfm_product_meta_save' ), 40, 2 );
    	add_action( 'after_wcfm_product_variation_meta_save', array( &$this, 'wcs_product_variation_save' ), 40, 4 );
    	
    	// Subscription Product Date Edit
    	add_filter( 'wcfm_variation_edit_data', array( &$this, 'wcs_product_data_variations' ), 40, 3 );
    }
    
  }
  
  /**
   * WC Subscriptions Product Type
   */
  function wcs_product_types( $pro_types ) {
  	global $WCFM, $WCFMu;
  	
  	$pro_types['variable-subscription'] = __( 'Variable subscription', 'woocommerce-subscriptions' );
  	
  	return $pro_types;
  }
  
  /**
	 * WC Subscriptions Product General options
	 */
	function wcs_product_manage_fields_general( $general_fields, $product_id ) {
		global $WCFM, $WCFMu;
		
		$sign_up_fee         = '';
		$chosen_trial_length = 0;
		$chosen_trial_period = '';
		
		if( $product_id ) {
			$sign_up_fee         = get_post_meta( $product_id, '_subscription_sign_up_fee', true );
			$chosen_trial_length = WC_Subscriptions_Product::get_trial_length( $product_id );
			$chosen_trial_period = WC_Subscriptions_Product::get_trial_period( $product_id );
		}
		
		$general_fields = array_slice($general_fields, 0, 12, true) +
																	array( 
																				"_subscription_sign_up_fee" => array('label' => sprintf( esc_html__( 'Sign-up fee (%s)', 'woocommerce-subscriptions' ), esc_html( get_woocommerce_currency_symbol() ) ), 'type' => 'text', 'placeholder' => 'e.g. 9.90', 'class' => 'wcfm-text wcfm_ele subscription', 'label_class' => 'wcfm_title wcfm_ele subscription', 'hints' => __( 'Optionally include an amount to be charged at the outset of the subscription. The sign-up fee will be charged immediately, even if the product has a free trial or the payment dates are synced.', 'woocommerce-subscriptions' ), 'value' => $sign_up_fee ),
																				"_subscription_trial_length" => array( 'label' => esc_html__( 'Free Trial', 'woocommerce-subscriptions' ), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele subscription_price_ele subscription', 'label_class' => 'wcfm_title wcfm_ele subscription', 'hints' => __( 'An optional period of time to wait before charging the first recurring payment. Any sign up fee will still be charged at the outset of the subscription.', 'woocommerce-subscriptions' ), 'value' => $chosen_trial_length ),
																				"_subscription_trial_period" => array( 'type' => 'select', 'options' => wcs_get_available_time_periods(), 'class' => 'wcfm-select wcfm_ele subscription_price_ele subscription', 'label_class' => 'wcfm_title wcfm_ele subscription', 'value' => $chosen_trial_period ),
																				) +
																	array_slice($general_fields, 12, count($general_fields) - 1, true) ;
		return $general_fields;
	}
	
	/**
	 * WC Subscriptions Product Shipping options
	 */
	function wcs_product_manage_fields_shipping( $shipping_fields, $product_id ) {
		global $WCFM, $WCFMu;
		
		$one_time_shipping           = 'no';
		
		if( $product_id ) {
			$one_time_shipping         = get_post_meta( $product_id, '_subscription_one_time_shipping', true ) ? get_post_meta( $product_id, '_subscription_one_time_shipping', true ) : 'no';
		}
		
		$shipping_fields = array_slice( $shipping_fields, 0, 5, true) +
																	array( 
																				"_subscription_one_time_shipping" => array( 'label' => esc_html__( 'One time shipping', 'woocommerce-subscriptions' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele subscription variable-subscription', 'label_class' => 'wcfm_title wcfm_ele subscription variable-subscription', 'hints' => __( 'Shipping for subscription products is normally charged on the initial order and all renewal orders. Enable this to only charge shipping once on the initial order. Note: for this setting to be enabled the subscription must not have a free trial or a synced renewal date.', 'woocommerce-subscriptions' ), 'value' => 'yes', 'dfvalue' => $one_time_shipping )
																				) +
																	array_slice( $shipping_fields, 5, count($shipping_fields) - 1, true) ;
		return $shipping_fields;
		
	}
	
	/**
	 * WC Subscriptions Product Advanced options
	 */
	function wcs_product_manage_fields_advanced( $advanced_fields, $product_id ) {
		global $WCFM, $WCFMu;
		
		$subscription_limit           = '';
		
		if( $product_id ) {
			$subscription_limit         = get_post_meta( $product_id, '_subscription_limit', true );
		}
		
		$advanced_fields = array_slice( $advanced_fields, 0, 3, true) +
																	array( 
																				"_subscription_limit" => array( 'label' => esc_html__( 'Limit subscription', 'woocommerce-subscriptions' ), 'type' => 'select', 'options' => array( 'no' => __( 'Do not limit', 'woocommerce-subscriptions' ), 'active' => __( 'Limit to one active subscription', 'woocommerce-subscriptions' ), 'any' => __( 'Limit to one of any status', 'woocommerce-subscriptions' ) ), 'class' => 'wcfm-select wcfm_ele subscription variable-subscription', 'label_class' => 'wcfm_title wcfm_ele subscription variable-subscription', 'hints' => __( 'Only allow a customer to have one subscription to this product.', 'woocommerce-subscriptions' ), 'value' => $subscription_limit )
																				) +
																	array_slice( $advanced_fields, 3, count($advanced_fields) - 1, true) ;
		return $advanced_fields;
		
	}
	
	/**
	 * WC Subscriptions Variation aditional options
	 */
	function wcs_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
		global $WCFM, $WCFMu;
		
		$variation_fileds = array_slice($variation_fileds, 0, 6, true) +
																	array(  "_subscription_price" => array('label' => sprintf( esc_html__( 'Subscription price (%s)', 'woocommerce-subscriptions' ), esc_html( get_woocommerce_currency_symbol() ) ), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele subscription_price_ele variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription', 'hints' => __( 'Choose the subscription price, billing interval and period.', 'woocommerce-subscriptions' ) ),
																					"_subscription_period_interval" => array( 'type' => 'select', 'options' => wcs_get_subscription_period_interval_strings(), 'class' => 'wcfm-select wcfm_ele subscription_price_ele variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription' ),
																					"_subscription_period" => array( 'type' => 'select', 'options' => wcs_get_subscription_period_strings(), 'class' => 'wcfm-select wcfm_ele subscription_price_ele variable-subscription_period variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription' ),
																					"_subscription_length_day" => array( 'label' => __('Subscription length', 'woocommerce-subscriptions' ), 'type' => 'select', 'options' => wcs_get_subscription_ranges( 'day' ), 'class' => 'wcfm-select wcfm_ele variable-subscription_length_ele variable-subscription_length_day variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription_length_ele variable-subscription_length_day variable-subscription', 'hints' => __( 'Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'woocommerce-subscriptions' ) ),
																					"_subscription_length_week" => array( 'label' => __('Subscription length', 'woocommerce-subscriptions' ), 'type' => 'select', 'options' => wcs_get_subscription_ranges( 'week' ), 'class' => 'wcfm-select wcfm_ele variable-subscription_length_ele variable-subscription_length_week variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription_length_ele variable-subscription_length_week variable-subscription', 'hints' => __( 'Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'woocommerce-subscriptions' ) ),
																					"_subscription_length_month" => array( 'label' => __('Subscription length', 'woocommerce-subscriptions' ), 'type' => 'select', 'options' => wcs_get_subscription_ranges( 'month' ), 'class' => 'wcfm-select wcfm_ele variable-subscription_length_ele variable-subscription_length_month variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription_length_ele variable-subscription_length_month variable-subscription', 'hints' => __( 'Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'woocommerce-subscriptions' ) ),
																					"_subscription_length_year" => array( 'label' => __('Subscription length', 'woocommerce-subscriptions' ), 'type' => 'select', 'options' => wcs_get_subscription_ranges( 'year' ), 'class' => 'wcfm-select wcfm_ele variable-subscription_length_ele variable-subscription_length_year variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription_length_ele variable-subscription_length_year variable-subscription', 'hints' => __( 'Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'woocommerce-subscriptions' ) ),
																					"_subscription_sign_up_fee" => array('label' => sprintf( esc_html__( 'Sign-up fee (%s)', 'woocommerce-subscriptions' ), esc_html( get_woocommerce_currency_symbol() ) ), 'type' => 'text', 'placeholder' => 'e.g. 9.90', 'class' => 'wcfm-text wcfm_ele variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription', 'hints' => __( 'Optionally include an amount to be charged at the outset of the subscription. The sign-up fee will be charged immediately, even if the product has a free trial or the payment dates are synced.', 'woocommerce-subscriptions' ) ),
																					"_subscription_trial_length" => array( 'label' => esc_html__( 'Free Trial', 'woocommerce-subscriptions' ), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele subscription_trial_ele variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription', 'hints' => __( 'An optional period of time to wait before charging the first recurring payment. Any sign up fee will still be charged at the outset of the subscription.', 'woocommerce-subscriptions' ) ),
																					"_subscription_trial_period" => array( 'type' => 'select', 'options' => wcs_get_available_time_periods(), 'class' => 'wcfm-select wcfm_ele subscription_trial_ele variable-subscription', 'label_class' => 'wcfm_title wcfm_ele variable-subscription' ),
																				) +
																	array_slice($variation_fileds, 6, count($variation_fileds) - 1, true) ;
																	
	  return $variation_fileds;									
	}
	
	/**
	 * WC Subscriptions Product Meta data save
	 */
	function wcs_wcfm_product_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMu, $_POST;
		
		if( $wcfm_products_manage_form_data['product_type'] == 'subscription' ) {
			// Make sure trial period is within allowable range
			$subscription_ranges = wcs_get_subscription_ranges();
	
			$max_trial_length = count( $subscription_ranges[ $wcfm_products_manage_form_data['_subscription_trial_period'] ] ) - 1;
	
			$wcfm_products_manage_form_data['_subscription_trial_length'] = absint( $wcfm_products_manage_form_data['_subscription_trial_length'] );
	
			if ( $wcfm_products_manage_form_data['_subscription_trial_length'] > $max_trial_length ) {
				$wcfm_products_manage_form_data['_subscription_trial_length'] = $max_trial_length;
			}
	
			update_post_meta( $new_product_id, '_subscription_trial_length', $wcfm_products_manage_form_data['_subscription_trial_length'] );
	
			$wcfm_products_manage_form_data['_subscription_sign_up_fee']       = wc_format_decimal( $wcfm_products_manage_form_data['_subscription_sign_up_fee'] );
			$wcfm_products_manage_form_data['_subscription_one_time_shipping'] = isset( $wcfm_products_manage_form_data['_subscription_one_time_shipping'] ) ? 'yes' : 'no';
	
			$subscription_fields = array(
				'_subscription_sign_up_fee',
				'_subscription_trial_period',
				'_subscription_limit',
				'_subscription_one_time_shipping',
			);
	
			foreach ( $subscription_fields as $field_name ) {
				if ( isset( $wcfm_products_manage_form_data[ $field_name ] ) ) {
					update_post_meta( $new_product_id, $field_name, stripslashes( $wcfm_products_manage_form_data[ $field_name ] ) );
				}
			}
		}
	}
	
	/**
	 * WC Subscriptions Variation Data Save
	 */
	function wcs_product_variation_save( $new_product_id, $variation_id, $variations, $wcfm_products_manage_form_data ) {
	 	global $wpdb, $WCFM, $WCFMu;
	 	
	 	if ( WC_Subscriptions_Product::is_subscription( $new_product_id ) ) {
	 	  
			$subscription_price = isset( $variations['_subscription_price'] ) ? wc_format_decimal( $variations['_subscription_price'] ) : '';
			update_post_meta( $variation_id, '_subscription_price', $subscription_price );
			update_post_meta( $variation_id, '_regular_price', $subscription_price );
			update_post_meta( $new_product_id, '_price', $subscription_price );
			update_post_meta( $variation_id, '_price', $subscription_price );
	
			$subscription_fields = array(
				'_subscription_period',
				'_subscription_period_interval',
				'_subscription_sign_up_fee',
				'_subscription_trial_period',
				'_subscription_trial_length'
			);
	
			foreach ( $subscription_fields as $field_name ) {
				if ( isset( $variations[ $field_name ] ) ) {
					update_post_meta( $variation_id, $field_name, stripslashes( $variations[ $field_name ] ) );
				}
			}
			
			update_post_meta( $variation_id, '_subscription_length', stripslashes( $variations[ '_subscription_length_' . $variations[ '_subscription_period' ]  ] ) );
			
			if ( WC_Subscriptions::is_woocommerce_pre( '3.0' ) ) {
				$variable_subscription = wc_get_product( $new_product_id );
				$variable_subscription->variable_product_sync();
			} else {
				WC_Product_Variable::sync( $new_product_id );
			}
		}
	}
	
	/**
	 * WC Subscriptions Variaton edit data
	 */
	function wcs_product_data_variations( $variations, $variation_id, $variation_id_key ) {
		global $WCFM, $WCFMu;
		
		if( $variation_id  ) {
			$variations[$variation_id_key]['_subscription_price'] = get_post_meta( $variation_id, '_subscription_price', true );
			$variations[$variation_id_key]['_subscription_period'] = get_post_meta( $variation_id, '_subscription_period', true);
			$variations[$variation_id_key]['_subscription_period_interval'] = get_post_meta( $variation_id, '_subscription_period_interval', true);
			$variations[$variation_id_key]['_subscription_sign_up_fee'] = get_post_meta( $variation_id, '_subscription_sign_up_fee', true);
			$variations[$variation_id_key]['_subscription_trial_period'] = get_post_meta( $variation_id, '_subscription_trial_period', true);
			$variations[$variation_id_key]['_subscription_trial_length'] = get_post_meta( $variation_id, '_subscription_trial_length', true);
			$variations[$variation_id_key]['_subscription_length_day'] = get_post_meta( $variation_id, '_subscription_length', true);
			$variations[$variation_id_key]['_subscription_length_week'] = get_post_meta( $variation_id, '_subscription_length', true);
			$variations[$variation_id_key]['_subscription_length_month'] = get_post_meta( $variation_id, '_subscription_length', true);
			$variations[$variation_id_key]['_subscription_length_year'] = get_post_meta( $variation_id, '_subscription_length', true);
		}
		
		return $variations;
	}
}