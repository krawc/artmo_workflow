<?php
/**
 * WCFMu plugin core
 *
 * Plugin Vendor Vacation Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   4.1.8
 */
 
class WCFMu_Vendor_Vacation {
	
	public function __construct() {
		
		//add_action( 'wcv_after_vendor_store_header',			array( &$this, 'wcfm_vacation_mode' ) );
		//add_action( 'woocommerce_before_single_product',			array( &$this, 'wcfm_vacation_mode' ) );
		if( apply_filters( 'wcfm_is_allow_vacation_message_before_main_content', false ) ) {
			add_action( 'woocommerce_before_main_content',			array( &$this, 'wcfm_vacation_mode' ) );
		}
		if( apply_filters( 'wcfm_is_allow_vacation_message_after_shop_loop_item', true ) ) {
			add_action( 'woocommerce_after_shop_loop_item',			array( &$this, 'wcfm_vacation_mode' ), 9 );
		}
		if( apply_filters( 'wcfm_is_allow_vacation_message_single_product_summary', true ) ) {
			add_action( 'woocommerce_single_product_summary',			array( &$this, 'wcfm_vacation_mode' ), 25 );
		}
		
		add_action( 'wcfmmp_before_store_product', array( &$this, 'wcfm_vacation_mode' ), 25 );
	}
	
	/**
	 * Show Vacation mode Message above vendor store
	 *
	 * @since 2.3.1
	 */
	public function wcfm_vacation_mode() {
		global $WCFM, $WCFMu;
		
		$vendor_id   		= 0;
		$vacation_mode = 'no';
		$disable_vacation_purchase = 'no'; 
		$vacation_msg = '';
		$is_marketplace = wcfm_is_marketplace();
		if( !$is_marketplace ) return;
		
		if ( is_product() || is_shop() || is_product_category() ) {
			global $product, $post; 
			if ( is_object( $product ) ) { 
				$vendor_id   		= $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product->get_id() ); 
			} else if ( is_product() ) {
				$vendor_id   		= $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );
			}
			if( $vendor_id ) {
				$vendor_has_vacation = $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $vendor_id, 'vacation' );
				if( !$vendor_has_vacation ) return;
				
				if( $is_marketplace == 'wcpvendors' ) {
					$vendor_data = get_term_meta( $vendor_id, 'vendor_data', true );
					$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
					$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
					$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
					$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
					$vendor_id = 0;
				} elseif( $is_marketplace == 'dokan' ) {
					$vendor_data = get_user_meta( $vendor_id, 'dokan_profile_settings', true );
					$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
					$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
					$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
					$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
					$vendor_id = 0;
				} elseif( $is_marketplace == 'wcfmmarketplace' ) {
					$vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
					$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
					$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
					$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
					$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
					$vendor_id = 0;
				}
			}
		} else { 
			if( $is_marketplace == 'wcvendors' ) {
				if ( WCV_Vendors::is_vendor_page() ) {
					$vendor_shop 		= urldecode( get_query_var( 'vendor_shop' ) );
					$vendor_id   		= WCV_Vendors::get_vendor_id( $vendor_shop ); 
				}
			} elseif( $is_marketplace == 'wcmarketplace' ) {
		  	if (is_tax('dc_vendor_shop')) {
		  		$vendor = get_wcmp_vendor_by_term(get_queried_object()->term_id);
		  		$vendor_id   		= $vendor->id;
		  	}
		  } elseif( $is_marketplace == 'wcpvendors' ) {
		  	if (is_tax('wcpv_product_vendors')) {
		  		$vendor_shop = get_queried_object()->term_id;
		  		$vendor_data = get_term_meta( $vendor_shop, 'vendor_data', true );
		  		$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
		  		$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
		  		$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
		  		$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
		  		$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
		  		$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
		  	}
		  } elseif( $is_marketplace == 'dokan' ) {
		  	if( dokan_is_store_page() ) {
		  		$custom_store_url = dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );
		  		$store_name = get_query_var( $custom_store_url );
		  		$vendor_id  = 0;
		  		if ( !empty( $store_name ) ) {
            $store_user = get_user_by( 'slug', $store_name );
          }
		  		$vendor_data = get_user_meta( $store_user->ID, 'dokan_profile_settings', true );
		  		$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
					$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
					$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
					$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
		  	}
		  } elseif( $is_marketplace == 'wcfmmarketplace' ) {
		  	if( wcfm_is_store_page() ) {
		  		$custom_store_url = get_option( 'wcfm_store_url', 'store' );
		  		$store_name = get_query_var( $custom_store_url );
		  		$vendor_id  = 0;
		  		if ( !empty( $store_name ) ) {
            $store_user = get_user_by( 'slug', $store_name );
          }
          if( $store_user ) {
						$vendor_data = get_user_meta( $store_user->ID, 'wcfmmp_profile_settings', true );
						$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
						$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
						$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
						$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
						$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
						$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
					}
		  	}
		  }
		}

		if ( $vendor_id ) {
			$vendor_has_vacation = $WCFM->wcfm_vendor_support->wcfm_vendor_has_capability( $vendor_id, 'vacation' );
			if( !$vendor_has_vacation ) return;
			
			$vacation_mode 		= ( get_user_meta( $vendor_id, 'wcfm_vacation_mode', true ) ) ? get_user_meta( $vendor_id, 'wcfm_vacation_mode', true ) : 'no';
			$disable_vacation_purchase = ( get_user_meta( $vendor_id, 'wcfm_disable_vacation_purchase', true ) ) ? get_user_meta( $vendor_id, 'wcfm_disable_vacation_purchase', true ) : 'no';
			$wcfm_vacation_mode_type = ( get_user_meta( $vendor_id, 'wcfm_vacation_mode_type', true ) ) ? get_user_meta( $vendor_id, 'wcfm_vacation_mode_type', true ) : 'instant';
			$wcfm_vacation_start_date = ( get_user_meta( $vendor_id, 'wcfm_vacation_start_date', true ) ) ? get_user_meta( $vendor_id, 'wcfm_vacation_start_date', true ) : '';
			$wcfm_vacation_end_date = ( get_user_meta( $vendor_id, 'wcfm_vacation_end_date', true ) ) ? get_user_meta( $vendor_id, 'wcfm_vacation_end_date', true ) : '';
			$vacation_msg 		= ( $vacation_mode ) ? get_user_meta( $vendor_id , 'wcfm_vacation_mode_msg', true ) : ''; 
		}
		
		$disable_vacation_purchase = apply_filters( 'wcfm_disable_vacation_purchase', $disable_vacation_purchase );
		
		if( ( $vacation_mode == 'yes' ) && ( $disable_vacation_purchase == 'yes' ) ) {
			if( $wcfm_vacation_mode_type == 'instant' ) {
				add_filter( 'woocommerce_is_purchasable', '__return_false' );
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			} elseif( $wcfm_vacation_start_date && $wcfm_vacation_end_date ) {
				$current_time = strtotime( 'midnight', current_time( 'timestamp' ) );
				$start_time = strtotime( $wcfm_vacation_start_date );
				$end_time = strtotime( $wcfm_vacation_end_date );
				if( ($current_time >= $start_time) && ($current_time <= $end_time) ) {
					add_filter( 'woocommerce_is_purchasable', '__return_false' );
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				}
			}
		} else {
			if( apply_filters( 'wcfm_is_allow_add_to_cart_restore', false ) ) {
				add_filter( 'woocommerce_is_purchasable', '__return_true' );
				add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}
		}
		
		$vacation_msg = apply_filters( 'wcfm_vacation_message_text', $vacation_msg );

		if ( $vacation_mode == 'yes' ) {
			if( $wcfm_vacation_mode_type == 'instant' ) {
			?>
			<div class="wcfm_vacation_msg">
				<?php echo $vacation_msg; ?>
			</div>
		<?php 
			} elseif( $wcfm_vacation_start_date && $wcfm_vacation_end_date ) {
				$current_time = strtotime( 'midnight', current_time( 'timestamp' ) );
				$start_time = strtotime( $wcfm_vacation_start_date );
				$end_time = strtotime( $wcfm_vacation_end_date );
				if( ($current_time >= $start_time) && ($current_time <= $end_time) ) {
					?>
						<div class="wcfm_vacation_msg">
							<?php echo $vacation_msg; ?>
						</div>
					<?php 
				}
			}
		}

	}
}