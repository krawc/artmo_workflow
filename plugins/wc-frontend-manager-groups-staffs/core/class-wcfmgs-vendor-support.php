<?php
/**
 * WCFM plugin core
 *
 * Plugin Vendor Support Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/core
 * @version   2.0.0
 */
 
class WCFMgs_Vendor_Support {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM, $WCFMgs;
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', (array) get_option( 'wcfm_capability_options' ) );
		
		// Filter Staff available capabilities by Logged in Vendor allowed capabilities
		if( wcfm_is_vendor() ) {
			add_filter( 'wcfm_capability_settings_fields_products', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_types', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_panels', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_sections', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_insights', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_fields', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_product_limits', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_access', array( &$this, 'wcfmgs_force_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_listings', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_articles', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_coupons', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_bookings', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_appointments', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_subscriptions', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_orders', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_invoice', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_customers', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_withdrwal', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_reports', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_shipping_tracking', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_support_ticket', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_header_panel', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_profile', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_settings', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_settings_inside', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_capability_groups', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_capability_analytics', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
			add_filter( 'wcfm_capability_settings_fields_wcfmmarkerplace', array( &$this, 'wcfmgs_filtered_staff_capabilities' ), 200 );
		}
		
		add_filter( 'wcfm_profile_field_vendor_manage', array( &$this, 'wcfmgs_user_custom_capability_option' ), 200, 3 );
		add_filter( 'after_wcfm_vendors_manage_form', array( &$this, 'wcfmgs_user_custom_capability_block' ), 200, 2 );
		add_action( 'wcfm_vendor_manage_profile_update', array( &$this, 'wcfmgs_user_custom_capability_update' ), 200, 2 );
		
	}
	
	function wcfmgs_filtered_staff_capabilities( $capability_fields ) {
		if( !empty( $capability_fields ) && is_array( $this->wcfm_capability_options ) && !empty( $this->wcfm_capability_options ) ) {
			foreach( $capability_fields as $capability_field => $capability_field_details ) {
				if( isset( $this->wcfm_capability_options[$capability_field] ) ) {
					$capability_fields[$capability_field]['dfvalue'] = 'yes';
					$capability_fields[$capability_field]['class'] = 'wcfm_capability_manager_option_hide';
					$capability_fields[$capability_field]['label_class'] = 'wcfm_capability_manager_option_hide';
					$capability_fields[$capability_field]['wrapper_class'] = 'wcfm_capability_manager_option_hide';
				}
			}
		}
		
		return $capability_fields;
	}
	
	function wcfmgs_force_filtered_staff_capabilities( $capability_fields ) {
		if( !empty( $capability_fields ) ) {
			foreach( $capability_fields as $capability_field => $capability_field_details ) {
				$capability_fields[$capability_field]['dfvalue'] = 'yes';
				$capability_fields[$capability_field]['class'] = 'wcfm_capability_manager_option_hide';
				$capability_fields[$capability_field]['label_class'] = 'wcfm_capability_manager_option_hide';
				$capability_fields[$capability_field]['wrapper_class'] = 'wcfm_capability_manager_option_hide';
			}
		}
		
		return $capability_fields;
	}
	
	function wcfmgs_user_custom_capability_option( $profile_fields, $vendor_admin_id, $vendor_id ) {
		global $WCFM, $WCFMgs;
		$has_custom_capability = get_user_meta( $vendor_admin_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $vendor_admin_id, '_wcfm_user_has_custom_capability', true ) : 'no';
		$profile_fields['has_custom_capability'] = array( 'label' => __('Custom Capability', 'wc-frontend-manager-groups-staffs') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $has_custom_capability);
		return $profile_fields;
	}
	
	function wcfmgs_user_custom_capability_block( $vendor_admin_id, $vendor_id ) {
		$vendor_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options' ), $vendor_admin_id );
		?>
		<div class="user_custom_capability" style="display: none;">
			<?php do_action( 'wcfmgs_capability_manager', $vendor_capability_options ); ?>
		</div>
		<?php
	}
	
	function wcfmgs_user_custom_capability_update( $vendor_id, $wcfm_vendor_manage_profile_form_data ) {
		// Update User capability
		if( isset( $wcfm_vendor_manage_profile_form_data['has_custom_capability'] ) ) {
			update_user_meta( $vendor_id, '_wcfm_user_has_custom_capability', 'yes' );
			
			if( isset( $wcfm_vendor_manage_profile_form_data['wcfmgs_capability_manager_options'] ) ) {
				update_user_meta( $vendor_id, '_wcfm_user_capability_options', $wcfm_vendor_manage_profile_form_data['wcfmgs_capability_manager_options'] );
			} else {
				delete_user_meta( $vendor_id, '_wcfm_user_capability_options' );
			}
		} else {
			update_user_meta( $vendor_id, '_wcfm_user_has_custom_capability', 'no' );
			delete_user_meta( $vendor_id, '_wcfm_user_capability_options' );
		}
	}
	
}