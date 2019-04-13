<?php
/**
 * WCFMu plugin core
 *
 * Plugin Vendor Support Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   2.0.5
 */
 
class WCFMu_Vendor_Support {

	public function __construct() {
		global $WCFM, $WCFMu;
		
		add_filter( 'wcfm_settings_fields_product_types', array( &$this, 'wcfmcap_product_types' ), 10, 3 );
		
		// WCFMu WCMp Endpoint Edit - 2.5.3
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcmarketplace_wcfmu_endpoints_slug' ) );
			
		if( $is_allow_commission_manage = apply_filters( 'wcfm_is_allow_commission_manage', true ) ) {
			if( $WCFMu->is_marketplace == 'wcvendors' ) {
				if( wcfm_is_wcvpro() ) {
					if( !wcfm_is_vendor() ) {
						add_action( 'end_wcfm_products_manage', array( &$this, 'wcvendorspro_product_commission' ), 499 );
						
						// Commision Save
						add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcvendorspro_product_commission_save' ), 500, 2 );
					}
				}
			}
		}
		
		// Vendor Order Shippment Tracking
		if( $WCFMu->is_marketplace ) {
			add_filter( 'woocommerce_order_item_display_meta_key', array( &$this, 'wcfm_tracking_url_display_label' ) );
			add_action( 'woocommerce_order_item_meta_end', array( &$this, 'wcfm_order_racking_response' ), 20, 3 );
		}
	}
	
	
	/**
	 * WCFM Capability Product Types
	 */
	function wcfmcap_product_types( $product_types, $handler = 'wcfm_capability_options', $wcfm_capability_options = array() ) {
		global $WCFM, $WCFMu;
		
		$virtual = ( isset( $wcfm_capability_options['virtual'] ) ) ? $wcfm_capability_options['virtual'] : 'no';
		$downloadable = ( isset( $wcfm_capability_options['downloadable'] ) ) ? $wcfm_capability_options['downloadable'] : 'no';
		
		$product_types["virtual"]      = array('label' => __('Virtual', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[virtual]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $virtual);
		$product_types["downloadable"] = array('label' => __('Downloadable', 'wc-frontend-manager-ultimate') , 'name' => $handler . '[downloadable]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $downloadable);
		
		return $product_types;
	}
	
	/**
	 * WCMp Endpoiint Edit
	 */
	function wcmarketplace_wcfmu_endpoints_slug( $endpoints ) {
		global $WCFM, $WCFMu;
		
		if( $WCFMu->is_marketplace == 'wcmarketplace' ) {
			$wcmp_endpoints = array(
														'wcfm-payments'  		   => 'wcfm-payments',
														'wcfm-withdrawal'  		 => 'wcfm-withdrawal',
														);
			$endpoints = array_merge( $endpoints, $wcmp_endpoints );
		}
		
		return $endpoints;
	}
	
	// WCV Pro Vendor Product Commission
	function wcvendorspro_product_commission( $product_id ) {
		global $WCFM, $WCFMu;
		
		remove_action( 'end_wcfm_products_manage', array( &$WCFM->wcfm_vendor_support, 'wcvendors_product_commission' ), 500 );
		
		$wcv_commission_type = '';
		$wcv_commission_amount = '';
		$wcv_commission_percent = '';
		$wcv_commission_fee = '';
		if( $product_id  ) {
			$wcv_commission_type = get_post_meta( $product_id, 'wcv_commission_type', true);
			$wcv_commission_amount = get_post_meta( $product_id, 'wcv_commission_amount', true);
			$wcv_commission_percent = get_post_meta( $product_id, 'wcv_commission_percent', true);
			$wcv_commission_fee = get_post_meta( $product_id, 'wcv_commission_fee', true);
		}
		?>
		<!-- collapsible 12 - WCMp Commission Support -->
		<div class="page_collapsible products_manage_commission simple variable grouped external booking" id="wcfm_products_manage_form_commission_head"><label class="fa fa-percent"></label><?php _e('Commission', 'wc-frontend-manager-ultimate'); ?><span></span></div>
		<div class="wcfm-container simple variable external grouped booking">
			<div id="wcfm_products_manage_form_commission_expander" class="wcfm-content">
				<?php
				$commission_types = WCVendors_Pro_Commission_Controller::commission_types();
				$empty = array( '' => '&nbsp;&nbsp;' );
				$commission_types = array_merge( $empty, $commission_types );
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_manage_fields_commission', array(  
																																														"wcv_commission_type" => array('label' => __('Commission Type', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'options' => $commission_types,'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => $wcv_commission_type ),
																																														"wcv_commission_percent" => array('label' => __('Commission(%)', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'attributes' => array( 'min' => '', 'steps' => 1 ), 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => $wcv_commission_percent ),
																																														"wcv_commission_amount" => array('label' => __('Commission Fixed', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'attributes' => array( 'min' => '', 'steps' => 1 ), 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => $wcv_commission_amount ),
																																														"wcv_commission_fee" => array('label' => __('Commission Fee', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'attributes' => array( 'min' => '', 'steps' => 1 ), 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => $wcv_commission_fee )
																																									)) );
				?>
			</div>
		</div>
		<!-- end collapsible -->
		<div class="wcfm_clearfix"></div>
		<?php
	}
	
	// WCV Pro Vendor Product Commision Save
	function wcvendorspro_product_commission_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM, $WCFMu;
		
		if( isset( $wcfm_products_manage_form_data['wcv_commission_type'] ) && !empty( $wcfm_products_manage_form_data['wcv_commission_type'] ) ) {
			update_post_meta( $new_product_id, 'wcv_commission_type', $wcfm_products_manage_form_data['wcv_commission_type'] );
		} else {
			delete_post_meta( $new_product_id, 'wcv_commission_type' );
		}
		
		if( isset( $wcfm_products_manage_form_data['wcv_commission_percent'] ) && !empty( $wcfm_products_manage_form_data['wcv_commission_percent'] ) ) {
			update_post_meta( $new_product_id, 'wcv_commission_percent', $wcfm_products_manage_form_data['wcv_commission_percent'] );
		}
		if( isset( $wcfm_products_manage_form_data['wcv_commission_amount'] ) && !empty( $wcfm_products_manage_form_data['wcv_commission_amount'] ) ) {
			update_post_meta( $new_product_id, 'wcv_commission_amount', $wcfm_products_manage_form_data['wcv_commission_amount'] );
		}
		if( isset( $wcfm_products_manage_form_data['wcv_commission_fee'] ) && !empty( $wcfm_products_manage_form_data['wcv_commission_fee'] ) ) {
			update_post_meta( $new_product_id, 'wcv_commission_fee', $wcfm_products_manage_form_data['wcv_commission_fee'] );
		}
	}
	
	// Order item meta Tracking URL label
	function wcfm_tracking_url_display_label( $display_key ) {
		global $WCFM, $WCFMu;
		
		if( $display_key == 'wcfm_tracking_code' ) {
			$display_key = __( 'Tracking Code', 'wc-frontend-manager-ultimate' );
		}
		
		if( $display_key == 'wcfm_tracking_url' ) {
			$display_key = __( 'Tracking URL', 'wc-frontend-manager-ultimate' );
		}
		
		if( $display_key == 'wcfm_mark_as_recived' ) {
			$display_key = __( 'Item(s) Received', 'wc-frontend-manager-ultimate' );
		}
		
		return $display_key;
	}
	
	// Order Tracking reponse at View Order by Customer
	function wcfm_order_racking_response( $item_id, $item, $order ) {
		global $WCFM, $WCFMu;
		
		if( !$WCFMu->is_marketplace ) return;
		
		// See if product needs shipping 
		$product = $item->get_product(); 
		$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product ); 
		
		if( $WCFMu->is_marketplace == 'wcvendors' ) {
			if( !WC_Vendors::$pv_options->get_option( 'give_shipping' ) ) $needs_shipping = false;
		} elseif( $WCFMu->is_marketplace == 'wcmarketplace' ) {
			global $WCMp;
			if( !$WCMp->vendor_caps->vendor_payment_settings('give_shipping') ) $needs_shipping = false;
		}
		
		if( $needs_shipping ) {
			$traking_added = false;
			$package_received = false;
			foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
				if( $meta->key == 'wcfm_tracking_url' ) {
					$traking_added = true;
				}
				if( $meta->key == 'wcfm_mark_as_recived' ) {
					$package_received = true;
				}
			}
			
			_e( 'Shipment Tracking: ', 'wc-frontend-manager-ultimate' );
			if( $package_received ) {
				_e( 'Item(s) already received.', 'wc-frontend-manager-ultimate' );
			} elseif( $traking_added ) {
				?>
				<a href="#" class="wcfm_mark_as_recived" data-orderitemid="<?php echo $item_id; ?>" data-orderid="<?php echo $order->get_id(); ?>" data-productid="<?php echo $item->get_product_id(); ?>"><?php _e( 'Mark as Received', 'wc-frontend-manager-ultimate' ); ?></a>
				<?php
			} else {
				_e( 'Item(s) will be shipped soon.', 'wc-frontend-manager-ultimate' );
			}
		}
	}
}