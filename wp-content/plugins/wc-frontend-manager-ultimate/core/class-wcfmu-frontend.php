<?php
/**
 * WCFMu plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   1.0.0
 */
 
class WCFMu_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		// WCFM Order Details Status Update
		add_filter( 'wcfm_order_status_modify', array( &$this, 'wcfm_order_status_modify' ), 10, 2 );
		
		// WCFM Product Manage Archive
		add_action( 'wcfm_product_manage', array( &$this, 'wcfm_product_manage_ultimate' ), 10, 2 );
		
		// WCFMu Report Menu
		add_filter( 'wcfm_reports_menus', array( &$this, 'wcfmu_reports_menus' ) );
		
		// WCFMu Sales by Date Filters
		add_action( 'wcfm_report_sales_by_date_filters', array( &$this, 'wcfmu_report_sales_by_date_filters' ) );
		
		// WCFMu Reports URL
		add_filter( 'sales_by_product_report_url', array( &$this, 'sales_by_product_report_url' ), 10, 2 );
		add_filter( 'low_in_stock_report_url', array( &$this, 'low_in_stock_report_url' ) );
		
		// WCFMu Product Additional Options
		add_action( 'wcfm_product_manager_gallery_fields_end', array( &$this, 'wcfmu_products_manage_visibility' ), 20 );
		//add_action( 'wcfm_products_manage_attributes', array( &$this, 'wcfmu_products_manage_text_attributes' ), 20 );
		add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfmu_product_manage_fields_variations' ), 10, 4 );
		
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'wcfmu_scripts'), 20);
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'wcfmu_styles'), 20);
	}
	
	/**
	 * WCFM Order Details Status Update
	 */
	public function wcfm_order_status_modify( $order_status, $order ) {
		global $WCFM, $WCFMu;
		
		
		
		return $order_status;
	}
	
	/**
	 * WCFM Product Manage
	 */
	function wcfm_product_manage_ultimate( $pro_id, $_product ) {
		global $WCFM, $WCFMu;
		
		if( !apply_filters( 'wcfm_is_allow_quick_edit_product', true ) ) return;
		
		?>
		 <?php if( current_user_can( 'edit_published_products' ) && apply_filters( 'wcfm_is_allow_edit_products', true ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $pro_id ) ) { ?>
			 <a class="wcfm_button wcfmu_product_quick_edit" href="#" data-product="<?php echo $pro_id; ?>"><span class="fa fa-link text_tip" data-tip="<?php echo esc_attr__( 'Quick Edit', 'wc-frontend-manager-ultimate' ); ?>"></span></a>		
			 <span class="wcfm_button_separator">|</span>
		 <?php } ?>
	  <?php
	}
	
	/**
	 * WCFMu Reports Menu
	 */
	function wcfmu_reports_menus( $reports_menus ) {
		global $WCFM, $WCFMu;
		
		unset($reports_menus['out-of-stock']);
		$reports_menus['sales-by-product'] = __( 'Sales by product', 'wc-frontend-manager-ultimate');
		$reports_menus['coupons-by-date'] = __( 'Coupons by date', 'wc-frontend-manager-ultimate');
		$reports_menus['low-in-stock'] = __( 'Low in stock', 'wc-frontend-manager-ultimate');
		$reports_menus['out-of-stock'] = __( 'Out of stock', 'wc-frontend-manager-ultimate');
		
		return $reports_menus;
	}
	
	/**
	 * WCFMu Sales by Date Reports Custom Filter
	 */
	function wcfmu_report_sales_by_date_filters() {
		global $WCFM, $WCFMu;
		
		//$WCFMu->template->get_template( 'reports/wcfmu-view-reports-sales-by-date.php' );
	}
	
	/**
	 * WCFMu Reports URL
	 */
	function low_in_stock_report_url( $reports_url ) {
		$reports_url = get_wcfm_reports_url( '', 'wcfm-reports-low-in-stock' );
		return $reports_url;
	}
	
	function sales_by_product_report_url( $reports_url, $top_seller = '' ) {
		$reports_url = get_wcfm_reports_url( '', 'wcfm-reports-sales-by-product' );
		if($top_seller) $reports_url = add_query_arg( 'product_ids', $top_seller, $reports_url );
		return $reports_url;
	}
	
	/**
	 * WCFMu Product Visibility
	 */
	function wcfmu_products_manage_visibility( $product_id ) {
		global $WCFM, $WCFMu;
		
		$product_object     = $product_id ? wc_get_product( $product_id ) : new WC_Product();
		$current_visibility = $product_object->get_catalog_visibility();
		$visibility_options = wc_get_product_visibility_options();
		
		$advanced_class = '';
		if( !apply_filters( 'wcfm_is_allow_products_manage_visibility', true ) ) $advanced_class = ' wcfm_custom_hide';
		
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_visibility', array(
																																																		"catalog_visibility" => array('label' => __('Catalog visibility:', 'woocommerce'), 'type' => 'select', 'options' => $visibility_options, 'class' => 'wcfm-select wcfm_ele wcfm_full_ele catalog_visibility_ele simple variable external grouped booking' . $advanced_class, 'label_class' => 'wcfm_title wcfm_full_ele catalog_visibility_ele' . $advanced_class, 'value' => $current_visibility ),
																																											)) );
	}
	
	/**
	 * WCFMu Product Text Attributes using WC Taxonomy Attribute
	 */
	function wcfmu_products_manage_text_attributes( $product_id = 0 ) {
		global $WCFM, $WCFMu, $wc_product_attributes;
		
		$wcfm_attributes = array();
		if( $product_id ) {
			$wcfm_attributes = get_post_meta( $product_id, '_product_attributes', true );
		}
		
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$attributes = array();
		$acnt = 0;
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
				if ( ( 'text' === $attribute_taxonomy->attribute_type ) && $attribute_taxonomy->attribute_name ) {
					$att_taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
					$attributes[$acnt]['term_name'] = $att_taxonomy;
					$attributes[$acnt]['name'] = wc_attribute_label( $att_taxonomy );
					$attributes[$acnt]['attribute_taxonomy'] = $attribute_taxonomy;
					$attributes[$acnt]['tax_name'] = $att_taxonomy;
					$attributes[$acnt]['is_taxonomy'] = 1;
				
					$attributes[$acnt]['value']          = esc_attr( implode( ' ' . WC_DELIMITER . ' ', wp_get_post_terms( $product_id, $att_taxonomy, array( 'fields' => 'names' ) ) ) );
					$attributes[$acnt]['is_active']      = '';
					$attributes[$acnt]['is_visible']     = '';
					$attributes[$acnt]['is_variation']   = '';
					
					if( $product_id && !empty( $wcfm_attributes ) ) {
						foreach( $wcfm_attributes as $wcfm_attribute ) {
							if ( $wcfm_attribute['is_taxonomy'] ) {
								if( $att_taxonomy == $wcfm_attribute['name'] ) {
									unset( $attributes[$acnt] );
									$acnt--;
								}
							}
						}
					}
				}
				
				$acnt++;
			}
			
			if( !empty( $attributes ) ) {
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_custom_text_attributes', array(  
																																																"text_attributes" => array( 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $attributes, 'options' => array(
																																																		"term_name" => array('type' => 'hidden'),
																																																		"is_active" => array('label' => __('Active?', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'custom_attributes' => array( 'tip' => __( 'Check to associate this attribute with the product', 'wc-frontend-manager-ultimate' ) ), 'class' => 'wcfm-checkbox wcfm_ele attribute_ele simple variable external grouped booking text_tip', 'label_class' => 'wcfm_title attribute_ele checkbox_title'),
																																																		"name" => array('label' => __('Name', 'wc-frontend-manager'), 'type' => 'text', 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele attribute_ele simple variable external grouped booking', 'label_class' => 'wcfm_title attribute_ele'),
																																																		"value" => array('label' => __('Value(s):', 'wc-frontend-manager'), 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title' ),
																																																		"is_visible" => array('label' => __('Visible on the product page', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title checkbox_title'),
																																																		"is_variation" => array('label' => __('Use as Variation', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele variable variable-subscription', 'label_class' => 'wcfm_title checkbox_title wcfm_ele variable variable-subscription'),
																																																		"tax_name" => array('type' => 'hidden'),
																																																		"is_taxonomy" => array('type' => 'hidden')
																																																))
																																											)) );
			}
				
		}
	}
	
	/**
	 * WCFMu Variation aditional options
	 */
	function wcfmu_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
		global $WCFM, $WCFMu;
		
		$variation_fileds = array_slice($variation_fileds, 0, 2, true) +
																	array(
																				"is_downloadable" => array('label' => __('Downloadable', 'wc-frontend-manager-ultimate'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele variable variable-subscription variation_is_downloadable_ele', 'label_class' => 'wcfm_title checkbox_title')
																				) +
																	array_slice($variation_fileds, 2, count($variation_fileds) - 1, true) ;
		
		$wcfmu_variation_fields = array(
																		"weight" => array('label' => __('Weight', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_weight_unit', 'kg' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"length" => array('label' => __('Length', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"width" => array('label' => __('Width', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"height" => array('label' => __('Height', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"shipping_class" => array('label' => __('Shipping class', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'options' => $variation_shipping_option_array, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription', 'label_class' => 'wcfm_title wcfm_half_ele_title'),
																		"tax_class" => array('label' => __('Tax class', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'options' => $variation_tax_classes_options, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription', 'label_class' => 'wcfm_title wcfm_half_ele_title'),
																		"wcfm_element_breaker_variation_3" => array( 'type' => 'html', 'value' => '<div class="wcfm-cearfix"></div>'),
																		"description" => array('label' => __('Description', 'wc-frontend-manager-ultimate') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele variable variable-subscription', 'label_class' => 'wcfm_title'),
																		"downloadable_file_name" => array('label' => __('File Name', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"downloadable_file" => array('label' => __('File', 'wc-frontend-manager-ultimate'), 'type' => 'upload', 'mime' => 'doc', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"download_limit" => array('label' => __('Download Limit', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"download_expiry" => array('label' => __('Download Expiry', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'placeholder' => __('Never', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		);
		$variation_fileds = array_merge( $variation_fileds, $wcfmu_variation_fields );
		
		if( isset( $variation_fileds['sale_price'] ) ) {
			$variation_fileds['sale_price']['desc'] = __( 'schedule', 'wc-frontend-manager' );
			$variation_fileds['sale_price']['desc_class'] = 'wcfm_ele variable variable-subscription var_sales_schedule'; 
		}
		
		return $variation_fileds;
	}
	
	/**
	 * WCFMu Core JS
	 */
	function wcfmu_scripts() {
 		global $WCFMu;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
 		// WCFMu Core JS
	  wp_enqueue_script( 'wcfmu_core_js', $WCFMu->library->js_lib_url . 'wcfmu-script-core.js', array( 'jquery' ), $WCFMu->version, true );
	  
	  // Localize Script
	  $wcfm_messages = get_wcfm_products_manager_messages();
		wp_localize_script( 'wcfmu_core_js', 'wcfmu_products_manage_messages', $wcfm_messages );
 	}
 	
 	/**
 	 * WCFMu Core CSS
 	 */
 	function wcfmu_styles() {
 		global $WCFMu;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
	  // WCFMu Core CSS
	  wp_enqueue_style( 'wcfmu_core_css',  $WCFMu->library->css_lib_url . 'wcfmu-style-core.css', array(), $WCFMu->version );
 	}
}