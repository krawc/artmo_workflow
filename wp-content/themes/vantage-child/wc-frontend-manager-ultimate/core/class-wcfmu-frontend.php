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
		add_action( 'wcfm_products_manage_attributes', array( &$this, 'wcfmu_products_manage_select_attributes' ) );
		add_action( 'wcfm_products_manage_attributes', array( &$this, 'wcfmu_products_manage_text_attributes' ), 20 );
		add_filter( 'wcfm_product_manage_fields_general', array( &$this, 'wcfmu_product_manage_fields_general' ), 10, 3 );
		add_filter( 'wcfm_product_manage_fields_pricing', array( &$this, 'wcfmu_product_manage_fields_pricing' ), 10, 3 );
		add_filter( 'wcfm_product_manage_fields_gallery', array( &$this, 'wcfmu_product_manage_fields_gallery' ), 10, 2 );
		add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfmu_product_manage_fields_variations' ), 10, 4 );
		add_action( 'after_wcfm_products_manage_general', array( &$this, 'after_wcfmu_products_manage_general' ), 10, 2 );
		
		// Vendor Vacation mode
		//add_action( 'wcv_after_vendor_store_header',			array( &$this, 'wcfm_vacation_mode' ) );
		//add_action( 'woocommerce_before_single_product',			array( &$this, 'wcfm_vacation_mode' ) );
		add_action( 'woocommerce_before_main_content',			array( &$this, 'wcfm_vacation_mode' ) );
		add_action( 'woocommerce_after_shop_loop_item',			array( &$this, 'wcfm_vacation_mode' ), 9 );
		add_action( 'woocommerce_single_product_summary',			array( &$this, 'wcfm_vacation_mode' ), 25 );
		
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
		
		?>
		 <?php if( current_user_can( 'edit_published_products' ) ) { ?>
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
		
		require_once( $WCFMu->library->views_path . 'wcfmu-view-reports-sales-by-date.php' );
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
	 * WCFMu Product Select Attributes using WC Taxonomy Attribute
	 */
	function wcfmu_products_manage_select_attributes( $product_id = 0 ) {
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
				if ( 'text' !== $attribute_taxonomy->attribute_type ) {
					$att_taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
					$attributes[$acnt]['term_name'] = $att_taxonomy;
					$attributes[$acnt]['name'] = wc_attribute_label( $att_taxonomy );
					$attributes[$acnt]['attribute_taxonomy'] = $attribute_taxonomy;
					$attributes[$acnt]['tax_name'] = $att_taxonomy;
					$attributes[$acnt]['is_taxonomy'] = 1;
				
					$args = array(
												'orderby'    => 'name',
												'hide_empty' => 0
											);
					$all_terms = get_terms( $att_taxonomy, apply_filters( 'wcfm_product_attribute_terms', $args ) );
					$attributes_option = array();
					if ( $all_terms ) {
						foreach ( $all_terms as $term ) {
							$attributes_option[$term->term_id] = esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) );
						}
					}
					$attributes[$acnt]['option_values']  = $attributes_option;
					$attributes[$acnt]['value']          = wp_get_post_terms( $product_id, $att_taxonomy, array( 'fields' => 'ids' ) );
					$attributes[$acnt]['is_active']      = '';
					$attributes[$acnt]['is_visible']     = '';
					$attributes[$acnt]['is_variation']   = '';
					
					if( $product_id && !empty( $wcfm_attributes ) ) {
						foreach( $wcfm_attributes as $wcfm_attribute ) {
							if ( $wcfm_attribute['is_taxonomy'] ) {
								if( $att_taxonomy == $wcfm_attribute['name'] ) {
									$attributes[$acnt]['is_active'] = 'enable';
									$attributes[$acnt]['is_visible'] = $wcfm_attribute['is_visible'] ? 'enable' : '';
									$attributes[$acnt]['is_variation'] = $wcfm_attribute['is_variation'] ? 'enable' : '';
								}
							}
						}
					}
				}
				
				$acnt++;
			}
			
			$allow_add_term = '';
			if( apply_filters( 'wcfm_is_allow_add_attribute_term', true ) ) {
				$allow_add_term = 'wc_attribute_values allow_add_term';
			} else {
				$allow_add_term = '';
			}
			
			if( !empty( $attributes ) ) {
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_custom_attributes', array(  
																																																"select_attributes" => array( 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'value' => $attributes, 'options' => array(
																																																		"term_name" => array('type' => 'hidden'),
																																																		"is_active" => array('label' => __('Active?', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'custom_attributes' => array( 'tip' => __( 'Check to associate this attribute with the product', 'wc-frontend-manager-ultimate' ) ), 'class' => 'wcfm-checkbox wcfm_ele attribute_ele simple variable external grouped booking text_tip', 'label_class' => 'wcfm_title attribute_ele checkbox_title'),
																																																		"name" => array('label' => __('Name', 'wc-frontend-manager'), 'type' => 'text', 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele attribute_ele simple variable external grouped booking', 'label_class' => 'wcfm_title attribute_ele'),
																																																		"value" => array('label' => __('Value(s):', 'wc-frontend-manager'), 'type' => 'select', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking ' . $allow_add_term, 'label_class' => 'wcfm_title'),
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
				if ( 'text' === $attribute_taxonomy->attribute_type ) {
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
	 * Product Image Gallery Support
	 */
	function wcfmu_product_manage_fields_gallery( $image_fields, $gallery_img_urls ) {
		global $WCFM, $WCFMu;
		
		if( apply_filters( 'wcfm_is_allow_gallery', true ) ) {
			$gallerylimit = apply_filters( 'wcfm_gallerylimit', -1 );
			$image_fields["gallery_img"] = array( 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title', 'custom_attributes' => array( 'limit' => $gallerylimit ), 'value' => $gallery_img_urls, 'options' => array(
																																																	"image" => array( 'type' => 'upload', 'class' => 'wcfm_gallery_upload', 'prwidth' => 75),
																																															));
		}
		return $image_fields;
	}
	
	/**
	 * WCFMu Simple product downloadable option
	 */
	function wcfmu_product_manage_fields_general( $general_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMu;
		
		// Download options
		$is_downloadable = ( get_post_meta( $product_id, '_downloadable', true) == 'yes' ) ? 'enable' : '';
		if( $product_type != 'simple' ) $is_downloadable = '';
		
		$general_fields = array_slice($general_fields, 0, 2, true) +
																	array(
																				"is_downloadable" => array('desc' => __('Downloadable', 'wc-frontend-manager-ultimate') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele wcfm_half_ele_checkbox simple non-variable-subscription non-job_package non-resume_package non-auction non-redq_rental non-appointment non-accommodation-booking', 'desc_class' => 'wcfm_title wcfm_ele downloadable_ele_title checkbox_title simple non-variable-subscription non-job_package non-resume_package non-auction non-redq_rental non-appointment non-accommodation-booking', 'value' => 'enable', 'dfvalue' => $is_downloadable),
																				) +
																	array_slice($general_fields, 2, count($general_fields) - 1, true) ;
		return $general_fields;
	}
	
	/**
	 * WCFMu Simple product Sale Price scheduling
	 */
	function wcfmu_product_manage_fields_pricing( $pricing_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMu;
		
		// Sale Scheduling
		$sale_date_from = ( $date = get_post_meta( $product_id, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		$sale_date_upto = ( $date = get_post_meta( $product_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		
		$dtf_pos = 4;
		if( wcfm_is_booking() ) $dtf_pos = 6;
		$pricing_fields = array_slice($pricing_fields, 0, $dtf_pos, true) +
																	array("sale_date_from" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'From... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele sales_schedule_ele simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking', 'label_class' => 'wcfm_ele wcfm_half_ele_title sales_schedule_ele wcfm_title simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking', 'value' => $sale_date_from),
																				"sale_date_upto" => array('label' => __('Upto', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'To... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele sales_schedule_ele simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking', 'label_class' => 'wcfm_ele wcfm_half_ele_title sales_schedule_ele wcfm_title simple external non-variable-subscription non-auction non-redq_rental non-accommodation-booking', 'value' => $sale_date_upto),
																				) +
																	array_slice($pricing_fields, $dtf_pos, count($pricing_fields) - 1, true) ;
																	
		return $pricing_fields;
	}
	
	/**
	 * After ProductsMaage General
	 */
	function after_wcfmu_products_manage_general( $product_id = 0, $product_type ) {
		global $WCFM, $WCFMu;
		
		$is_downloadable = '';
		$downloadable_files = array();
		$download_limit = '';
		$download_expiry = '';
		
		if( $product_id ) {
			$is_downloadable = ( get_post_meta( $product_id, '_downloadable', true) == 'yes' ) ? 'enable' : '';
			if( $product_type != 'simple' ) $is_downloadable = '';
			if($is_downloadable == 'enable') {
				$downloadable_files = (array) get_post_meta( $product_id, '_downloadable_files', true);
				$download_limit = ( -1 == get_post_meta( $product_id, '_download_limit', true) ) ? '' : get_post_meta( $product_id, '_download_limit', true);
				$download_expiry = ( -1 == get_post_meta( $product_id, '_download_expiry', true) ) ? '' : get_post_meta( $product_id, '_download_expiry', true);
			}
		}
		?>
		
		<?php if( $allow_downloadable = apply_filters( 'wcfmu_is_allow_downloadable', true ) ) { ?>
		<!-- collapsible 1 -->
	  <div class="page_collapsible products_manage_downloadable simple downlodable non-variable-subscription non-auction non-redq_rental non-appointment" id="wcfm_products_manage_form_downloadable_head"><label class="fa fa-cloud-download"></label><?php _e('Downloadable', 'wc-frontend-manager-ultimate'); ?><span></span></div>
		<div class="wcfm-container simple downlodable non-variable-subscription non-auction non-redq_rental non-appointment">
			<div id="wcfm_products_manage_form_downloadable_expander" class="wcfm-content">
			  <?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_fields_downloadable', array(  "downloadable_files" => array('label' => __('Files', 'wc-frontend-manager-ultimate') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele simple downlodable', 'label_class' => 'wcfm_title', 'value' => $downloadable_files, 'options' => array(
																																														"name" => array('label' => __('Name', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele simple downlodable', 'label_class' => 'wcfm_ele wcfm_title simple downlodable'),
																																														"file" => array('label' => __('File', 'wc-frontend-manager-ultimate'), 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'wcfm-text wcfm_ele simple downlodable', 'label_class' => 'wcfm_ele wcfm_title simple downlodable'),
																																														"previous_hash" => array( 'type' => 'hidden', 'name' => 'id' )
																																												)),
																																												"download_limit" => array('label' => __('Download Limit', 'wc-frontend-manager-ultimate'), 'type' => 'number', 'value' => $download_limit, 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele simple external', 'label_class' => 'wcfm_ele wcfm_title simple downlodable', 'attributes' => array( 'min' => '0', 'step' => '1' )),
																																												"download_expiry" => array('label' => __('Download Expiry', 'wc-frontend-manager-ultimate'), 'type' => 'number', 'value' => $download_expiry, 'placeholder' => __('Never', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele simple external', 'label_class' => 'wcfm_ele wcfm_title simple downlodable', 'attributes' => array( 'min' => '0', 'step' => '1' ))
																																							), $product_id, $product_type ) );
			  
			  ?>
		  </div>
		</div>
		<!-- end collapsible -->
		<div class="wcfm_clearfix"></div>
		<?php } ?>
		
		<?php
	}
	
	/**
	 * WCFMu Variation aditional options
	 */
	function wcfmu_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options ) {
		global $WCFM, $WCFMu;
		
		$variation_fileds = array_slice($variation_fileds, 0, 2, true) +
																	array("is_virtual" => array('label' => __('Virtual', 'wc-frontend-manager-ultimate'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele variable variable-subscription variation_is_virtual_ele', 'label_class' => 'wcfm_title checkbox_title'),
																				"is_downloadable" => array('label' => __('Downloadable', 'wc-frontend-manager-ultimate'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele variable variable-subscription variation_is_downloadable_ele', 'label_class' => 'wcfm_title checkbox_title')
																				) +
																	array_slice($variation_fileds, 2, count($variation_fileds) - 1, true) ;
		
		$wcfmu_variation_fields = array(
			                              "downloadable_file" => array('label' => __('File', 'wc-frontend-manager-ultimate'), 'type' => 'upload', 'mime' => 'doc', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_upload_title variation_downloadable_ele'),
																		"downloadable_file_name" => array('label' => __('File Name', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"download_limit" => array('label' => __('Download Limit', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"download_expiry" => array('label' => __('Download Expiry', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'placeholder' => __('Never', 'wc-frontend-manager-ultimate'), 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_downloadable_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_downloadable_ele'),
																		"weight" => array('label' => __('Weight', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_weight_unit', 'kg' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"length" => array('label' => __('Length', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"width" => array('label' => __('Width', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"height" => array('label' => __('Height', 'wc-frontend-manager-ultimate') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')' , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription variation_non_virtual_ele', 'label_class' => 'wcfm_title wcfm_half_ele_title variation_non_virtual_ele'),
																		"shipping_class" => array('label' => __('Shipping class', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'options' => $variation_shipping_option_array, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription', 'label_class' => 'wcfm_title wcfm_half_ele_title'),
																		"tax_class" => array('label' => __('Tax class', 'wc-frontend-manager-ultimate') , 'type' => 'select', 'options' => $variation_tax_classes_options, 'class' => 'wcfm-select wcfm_ele wcfm_half_ele variable variable-subscription', 'label_class' => 'wcfm_title wcfm_half_ele_title'),
																		"description" => array('label' => __('Description', 'wc-frontend-manager-ultimate') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele wcfm_full_ele variable variable-subscription', 'label_class' => 'wcfm_title')
																		);
		$variation_fileds = array_merge( $variation_fileds, $wcfmu_variation_fields );
		
		if( isset( $variation_fileds['sale_price'] ) ) {
			$variation_fileds['sale_price']['desc'] = __( 'schedule', 'wc-frontend-manager' );
			$variation_fileds['sale_price']['desc_class'] = 'wcfm_ele variable variable-subscription var_sales_schedule'; 
		}
		
		return $variation_fileds;
	}
	
	/**
	 * Show Vacation mode Message above vendor store
	 *
	 * @since 2.3.1
	 */
	public function wcfm_vacation_mode(  ) {
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
				if( $is_marketplace == 'wcpvendors' ) {
					$vendor_data = get_term_meta( $vendor_id, 'vendor_data', true );
					$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
					$vendor_id = 0;
				} elseif( $is_marketplace == 'dokan' ) {
					$vendor_data = get_user_meta( $vendor_id, 'dokan_profile_settings', true );
					$vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
					$disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
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
					$vacation_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';
		  	}
		  }
		}

		if ( $vendor_id ) { 
			$vacation_mode 		= ( get_user_meta( $vendor_id, 'wcfm_vacation_mode', true ) ) ? get_user_meta( $vendor_id, 'wcfm_vacation_mode', true ) : 'no';
			$disable_vacation_purchase = ( get_user_meta( $vendor_id, 'wcfm_disable_vacation_purchase', true ) ) ? get_user_meta( $vendor_id, 'wcfm_disable_vacation_purchase', true ) : 'no';
			$vacation_msg 		= ( $vacation_mode ) ? get_user_meta( $vendor_id , 'wcfm_vacation_mode_msg', true ) : ''; 
		}
		
		if( ( $vacation_mode == 'yes' ) && ( $disable_vacation_purchase == 'yes' ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}

		if ( $vacation_mode == 'yes' ) : ?>
			<div class="wcfm_vacation_msg">
				<?php echo $vacation_msg; ?>
			</div>
		<?php 
		endif;

	}
	
	/**
	 * WCFMu Core JS
	 */
	function wcfmu_scripts() {
 		global $WCFMu;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
 		// PopModal Lib
 		//$WCFMu->library->load_popmodal_lib();
 		
 		// Fancybox
 		$WCFMu->library->load_fancybox_lib();
 		
 		
 		// WCFMu Core JS
	  wp_enqueue_script( 'wcfmu_core_js', $WCFMu->library->js_lib_url . 'wcfmu-script-core.js', array( 'jquery', 'wcfm_fancybox_js' ), $WCFMu->version, true );
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