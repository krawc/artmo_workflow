<?php

/**
 * WCfM Product Types plugin core
 *
 * WC Product Addons Support
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   2.4.1
 */
 
class WCFMu_WCAddons {
	
	public function __construct() {
    global $WCFM, $WCFMu;
    
    if( WCFMu_Dependencies::wcfm_wc_addons_active_check() || WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
    	// WC Product Addons Product Manage View
			add_action( 'end_wcfm_products_manage', array( &$this, 'wcaddons_wcfm_products_manage' ), 200 );
			
			// WC Product Addons Load WCFMu Scripts
			add_action( 'wcfm_load_scripts', array( &$this, 'wcaddons_load_scripts' ), 90 );
			add_action( 'after_wcfm_load_scripts', array( &$this, 'wcaddons_load_scripts' ), 90 );
			
			// WC Product Addons Product Manage View
			add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcaddons_wcfm_products_manage_meta_save' ), 200, 2 );
		}
	}
	
	/**
   * WC Product Addons load views
   */
  function wcaddons_wcfm_products_manage( $product_id ) {
		global $WCFM, $WCFMu;
	  
	  $_product_addons = array();
	  $_product_addons_exclude_global = 0;

		if( $product_id ) {
			$_product_addons = (array) get_post_meta( $product_id, '_product_addons', true );
			$_product_addons_exclude_global = get_post_meta( $product_id, '_product_addons_exclude_global', true ) ? get_post_meta( $product_id, '_product_addons_exclude_global', true ) : 0;
		}
		
		$group_types = array( 'custom_price'               => __( 'Additional custom price input', 'woocommerce-product-addons' ),
													'input_multiplier'           => __( 'Additional price multiplier', 'woocommerce-product-addons' ),
													'checkbox'                   => __( 'Checkboxes', 'woocommerce-product-addons' ),
													'custom_textarea'            => __( 'Custom input (textarea)', 'woocommerce-product-addons' ),
													'custom'                     => __( 'Any text', 'woocommerce-product-addons' ),
													'custom_email'               => __( 'Email address', 'woocommerce-product-addons' ),
													'custom_letters_only'        => __( 'Only letters', 'woocommerce-product-addons' ),
													'custom_letters_or_digits'   => __( 'Only letters and numbers', 'woocommerce-product-addons' ),
													'custom_digits_only'         => __( 'Only numbers', 'woocommerce-product-addons' ),
													'file_upload'                => __( 'File upload', 'woocommerce-product-addons' ),
													'radiobutton'                => __( 'Radio buttons', 'woocommerce-product-addons' ),
													'select'                     => __( 'Select box', 'woocommerce-product-addons' )
												);
		
		?>
		
		<div class="page_collapsible products_manage_wcaddons wcaddons" id="wcfm_products_manage_form_wcaddons_head"><label class="fa fa-diamond"></label><?php _e('Add-ons', 'woocommerce-product-addons'); ?><span></span></div>
		<div class="wcfm-container wcaddons">
			<div id="wcfm_products_manage_form_wcaddons_expander" class="wcfm-content">
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_wcaddons', array( 
					"_product_addons" =>     array('label' => __('Add-ons', 'woocommerce-product-addons') , 'type' => 'multiinput', 'class' => 'wcfm_ele wcaddons', 'label_class' => 'wcfm_title wcaddons', 'value' => $_product_addons, 'options' => array(
												"type" => array('label' => __('Group', 'woocommerce-product-addons'), 'type' => 'select', 'options' => $group_types, 'class' => 'wcfm-select addon_fields_option wcaddons', 'label_class' => 'wcfm_title wcaddons' ),
												"name" => array('label' => __('Name', 'woocommerce-product-addons'), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title' ),
												"position" => array( 'type' => 'hidden' ),
												"description" => array('label' => __('Description', 'woocommerce-product-addons'), 'type' => 'textarea', 'class' => 'wcfm-textarea', 'label_class' => 'wcfm_title' ),
												"required" => array('label' => __('Required fields?', 'woocommerce-product-addons'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 1 ),
												"options" =>     array('label' => __('Options', 'wc-frontend-manager-ultimate') . '<span class="fields_collapser fa fa-arrow-circle-o-down"></span>', 'type' => 'multiinput', 'class' => 'wcfm_ele wcaddons', 'label_class' => 'wcfm_title wcaddons', 'options' => array(
														"label" => array('label' => __('Label', 'woocommerce-product-addons'), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title' ),
														"price" => array('label' => __('Price', 'woocommerce-product-addons'), 'type' => 'text', 'class' => 'wcfm-text addon_fields addon_price', 'label_class' => 'wcfm_title addon_fields addon_price' ),
														"min" => array('label' => __('Min', 'woocommerce-product-addons'), 'type' => 'number', 'class' => 'wcfm-text addon_fields addon_minmax', 'label_class' => 'wcfm_title addon_fields addon_minmax' ),
														"max" => array('label' => __('Max', 'woocommerce-product-addons'), 'type' => 'number', 'class' => 'wcfm-text addon_fields addon_minmax', 'label_class' => 'wcfm_title addon_fields addon_minmax' ),
													) )
												)	),
											 "_product_addons_exclude_global" => array('label' => __('Global Addon Exclusion', 'woocommerce-product-addons'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 1, 'dfvalue' => $_product_addons_exclude_global, 'hints' => __( 'Check this to exclude this product from all Global Addons', 'woocommerce-product-addons' ) )
					) ) );
				?>
			</div>
		</div>
		<!-- end collapsible -->
		<div class="wcfm_clearfix"></div>
		<?php
	}
	
	/**
	* WC Product Addons Scripts
	*/
  public function wcaddons_load_scripts( $end_point ) {
	  global $WCFM, $WCFMu;
    
	  switch( $end_point ) {
	  	case 'wcfm-products-manage':
				wp_enqueue_script( 'wcfmu_wcaddons_products_manage_js', $WCFMu->library->js_lib_url . 'wcfmu-script-wcaddons-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMu->version, true );
			break;
		}
	}
	
	/**
	 * WC Product Addons Product Meta data save
	 */
	function wcaddons_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $WCFM, $WCFMu;
		
		$_product_addons = array();
		
		if( isset( $wcfm_products_manage_form_data['_product_addons'] ) && !empty( $wcfm_products_manage_form_data['_product_addons'] ) ) {
		  $_product_addons = $wcfm_products_manage_form_data['_product_addons'];
		  
		  if( !empty( $_product_addons ) ) {
		  	$loop_index = 0;
		  	foreach( $_product_addons as $_product_addon_index => $_product_addon ) {
		  		$_product_addons[$_product_addon_index]['position'] = $loop_index;
		  		if( isset( $_product_addon['required'] ) ) $_product_addons[$_product_addon_index]['required'] = 1;
		  		else $_product_addons[$_product_addon_index]['required'] = 0;
		  		$loop_index++;
		  	}
		  }
		  update_post_meta( $new_product_id, '_product_addons', $_product_addons );
		}
		
		if( isset( $wcfm_products_manage_form_data['_product_addons_exclude_global'] ) && !empty( $wcfm_products_manage_form_data['_product_addons_exclude_global'] ) ) {
			update_post_meta( $new_product_id, '_product_addons_exclude_global', 1 );
		} else {
			update_post_meta( $new_product_id, '_product_addons_exclude_global', 0 );
		}
	}
	
}