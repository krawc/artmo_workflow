<?php
/**
 * WCFMu plugin Views
 *
 * Plugin Capability View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views
 * @version   2.5.0
 */
?>

<?php

/**
 * WCFM advanced capability
 *
 * @since 2.3.1
 */
add_action( 'wcfm_capability_settings_product', 'wcfmu_capability_settings_product_advanced' );

function wcfmu_capability_settings_product_advanced( $wcfm_capability_options ) {
	global $WCFM, $WCFMu;
	
	$featured_img = ( isset( $wcfm_capability_options['featured_img'] ) ) ? $wcfm_capability_options['featured_img'] : 'no';
	$gallery_img = ( isset( $wcfm_capability_options['gallery_img'] ) ) ? $wcfm_capability_options['gallery_img'] : 'no';
	$category = ( isset( $wcfm_capability_options['category'] ) ) ? $wcfm_capability_options['category'] : 'no';
	$add_category = ( isset( $wcfm_capability_options['add_category'] ) ) ? $wcfm_capability_options['add_category'] : 'no';
	$tags = ( isset( $wcfm_capability_options['tags'] ) ) ? $wcfm_capability_options['tags'] : 'no';
	$addons = ( isset( $wcfm_capability_options['addons'] ) ) ? $wcfm_capability_options['addons'] : 'no';
	$toolset_types = ( isset( $wcfm_capability_options['toolset_types'] ) ) ? $wcfm_capability_options['toolset_types'] : 'no';
	$acf_fields = ( isset( $wcfm_capability_options['acf_fields'] ) ) ? $wcfm_capability_options['acf_fields'] : 'no';
	$mappress = ( isset( $wcfm_capability_options['mappress'] ) ) ? $wcfm_capability_options['mappress'] : 'no';
	
	$add_attribute = ( isset( $wcfm_capability_options['add_attribute'] ) ) ? $wcfm_capability_options['add_attribute'] : 'no';
	$add_attribute_term = ( isset( $wcfm_capability_options['add_attribute_term'] ) ) ? $wcfm_capability_options['add_attribute_term'] : 'no';
	$delete_media = ( isset( $wcfm_capability_options['delete_media'] ) ) ? $wcfm_capability_options['delete_media'] : 'no';
	$rich_editor = ( isset( $wcfm_capability_options['rich_editor'] ) ) ? $wcfm_capability_options['rich_editor'] : 'no';
	$featured_product = ( isset( $wcfm_capability_options['featured_product'] ) ) ? $wcfm_capability_options['featured_product'] : 'no';
	$duplicate_product = ( isset( $wcfm_capability_options['duplicate_product'] ) ) ? $wcfm_capability_options['duplicate_product'] : 'no';
	$product_import = ( isset( $wcfm_capability_options['product_import'] ) ) ? $wcfm_capability_options['product_import'] : 'no';
	$product_export = ( isset( $wcfm_capability_options['product_export'] ) ) ? $wcfm_capability_options['product_export'] : 'no';
	$product_quick_edit = ( isset( $wcfm_capability_options['product_quick_edit'] ) ) ? $wcfm_capability_options['product_quick_edit'] : 'no';
	$product_bulk_edit = ( isset( $wcfm_capability_options['product_bulk_edit'] ) ) ? $wcfm_capability_options['product_bulk_edit'] : 'no';
	$stock_manager = ( isset( $wcfm_capability_options['stock_manager'] ) ) ? $wcfm_capability_options['stock_manager'] : 'no';
	
	$productlimit = ( !empty( $wcfm_capability_options['productlimit'] ) ) ? $wcfm_capability_options['productlimit'] : '';
	$gallerylimit = ( !empty( $wcfm_capability_options['gallerylimit'] ) ) ? $wcfm_capability_options['gallerylimit'] : '';
	$catlimit = ( !empty( $wcfm_capability_options['catlimit'] ) ) ? $wcfm_capability_options['catlimit'] : '';
	$allowed_categories    = ( !empty( $wcfm_capability_options['allowed_categories'] ) ) ? $wcfm_capability_options['allowed_categories'] : array();
	
	// remove WPML term filters - 3.4.1
	if ( function_exists('icl_object_id') ) {
		global $sitepress;
		remove_filter('get_terms_args', array( $sitepress, 'get_terms_args_filter'));
		remove_filter('get_term', array($sitepress,'get_term_adjust_id'));
		remove_filter('terms_clauses', array($sitepress,'terms_clauses'));
		
		$product_categories = array();
		$product_category_lists = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0, 'fields' => 'id=>name' ) );
		if( !empty( $product_category_lists ) ) {
			foreach( $product_category_lists as $product_category_id => $product_category_name ) {
				$product_category_list = get_term( $product_category_id );
				$product_category_list->term_id = $product_category_id;
				$product_category_list->name = $product_category_name;
				$product_categories[$product_category_id] = $product_category_list;
			}
		}
	} else {
		$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );
	}
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Sections', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
	$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_product_sections', array(
																																																									 "featured_img" => array('label' => __('Featured Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[featured_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_img),
																																																									 "gallery_img" => array('label' => __('Gallery Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[gallery_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $gallery_img),
																																																									 "category" => array('label' => __('Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $category),
																																																									 "add_category" => array('label' => __('Add Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[add_category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_category),
																																																									 "tags" => array('label' => __('Tags', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[tags]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $tags),
																																																									 "addons" => array('label' => __('Add-ons', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[addons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $addons),
																																																									 "toolset_types" => array('label' => __('Toolset Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[toolset_types]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $toolset_types),
																																																									 "acf_fields" => array('label' => __('ACF Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[acf_fields]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $acf_fields),
																																																									 "mappress" => array('label' => __('MapPress', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[mappress]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $mappress),
																						) ) );
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Insights', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
	$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_product_insights', array(
																																																									 "add_attribute" => array('label' => __('Add Attribute', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[add_attribute]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute),
																																																									 "add_attribute_term" => array('label' => __('Add Attribute Term', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[add_attribute_term]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute_term),
																																																									 "delete_media" => array('label' => __('Delete Media', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[delete_media]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_media),
																																																									 "rich_editor" => array('label' => __('Rich Editor', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[rich_editor]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $rich_editor),
																																																									 "featured_product" => array('label' => __('Featured Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[featured_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_product),
																																																									 "duplicate_product" => array('label' => __('Duplicate Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[duplicate_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $duplicate_product),
																																																									 "product_import" => array('label' => __('Import', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[product_import]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_import),
																																																									 "product_export" => array('label' => __('Export', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[product_export]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_export),
																																																									 "product_quick_edit" => array('label' => __('Quick Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[product_quick_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_quick_edit),
																																																									 "product_bulk_edit" => array('label' => __('Bulk Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[product_bulk_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_bulk_edit),
																																																									 "stock_manager" => array('label' => __('Stock Manager', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[stock_manager]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $stock_manager),
																						) ) );
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Limits', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
	$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_product_limits', array(
																																																									 "productlimit" => array( 'label' => __('Product Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_capability_options[productlimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $productlimit),
																																																									 "gallerylimit" => array( 'label' => __('Gallery Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_capability_options[gallerylimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $gallerylimit),
																																																									 "catlimit" => array( 'label' => __('Category Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_capability_options[catlimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele catlimit_ele', 'label_class' => 'wcfm_title catlimit_title', 'value' => $catlimit),
																					) ) );
	?>
	<p class="wcfm_title catlimit_title"><strong><?php _e( 'Categories', 'wc-frontend-manager-ultimate' ); ?></strong></p><label class="screen-reader-text" for="vendor_product_cats"><?php _e( 'Allowed Categories', 'wc-frontend-manager-ultimate' ); ?></label>
	<select id="vendor_allowed_categories" name="wcfm_capability_options[allowed_categories][]" class="wcfm-select wcfm_ele" multiple="multiple" data-catlimit="-1" style="width: 44%; margin-bottom: 10px;">
		<?php
			if ( $product_categories ) {
				$WCFM->library->generateTaxonomyHTML( 'product_cat', $product_categories, $allowed_categories, '', false, false, true );
			}
		?>
	</select>
	
	<?php
	$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
	if( !empty( $product_taxonomies ) ) {
		foreach( $product_taxonomies as $product_taxonomy ) {
			if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
				if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
					// Fetching Saved Values
					$allowed_custom_taxonomies    = ( !empty( $wcfm_capability_options['allowed_' . $product_taxonomy->name] ) ) ? $wcfm_capability_options['allowed_' . $product_taxonomy->name] : array();
					?>
					<p class="wcfm_title catlimit_title"><strong><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e( 'Allowed ', 'wc-frontend-manager-ultimate' ); ?><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></label>
					<select id="vendor_allowed_<?php echo $product_taxonomy->name; ?>" name="wcfm_capability_options[allowed_<?php echo $product_taxonomy->name; ?>][]" class="wcfm-select wcfm_ele vendor_allowed_custom_taxonomies" multiple="multiple" style="width: 44%; margin-bottom: 10px;">
						<?php
							$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
							if ( $product_taxonomy_terms ) {
								$WCFM->library->generateTaxonomyHTML( $product_taxonomy->name, $product_taxonomy_terms, $allowed_custom_taxonomies, '', false, false, true );
							}
						?>
					</select>
					<?php
				}
			}
		}
	}
	
	// restore WPML term filters
	if ( function_exists('icl_object_id') ) {
		global $sitepress;
		add_filter('terms_clauses', array($sitepress,'terms_clauses'));
		add_filter('get_term', array($sitepress,'get_term_adjust_id'));
		add_filter('get_terms_args', array($sitepress, 'get_terms_args_filter'));
	}
}

add_action( 'wcfm_capability_settings_miscellaneous', 'wcfmu_capability_settings_miscellaneous_advanced' );

function wcfmu_capability_settings_miscellaneous_advanced( $wcfm_capability_options ) {
	global $WCFM, $WCFMu;
	
	$shipping_tracking = ( isset( $wcfm_capability_options['shipping_tracking'] ) ) ? $wcfm_capability_options['shipping_tracking'] : 'no';
	
	$address = ( isset( $wcfm_capability_options['address'] ) ) ? $wcfm_capability_options['address'] : 'no';
	$social = ( isset( $wcfm_capability_options['social'] ) ) ? $wcfm_capability_options['social'] : 'no';
	
	$vacation = ( isset( $wcfm_capability_options['vacation'] ) ) ? $wcfm_capability_options['vacation'] : 'no';
	$brand = ( isset( $wcfm_capability_options['brand'] ) ) ? $wcfm_capability_options['brand'] : 'no';
	$vshipping = ( isset( $wcfm_capability_options['vshipping'] ) ) ? $wcfm_capability_options['vshipping'] : 'no';
	$billing = ( isset( $wcfm_capability_options['billing'] ) ) ? $wcfm_capability_options['billing'] : 'no';
	
	$knowledgebase = ( isset( $wcfm_capability_options['knowledgebase'] ) ) ? $wcfm_capability_options['knowledgebase'] : 'no';
	$notice = ( isset( $wcfm_capability_options['notice'] ) ) ? $wcfm_capability_options['notice'] : 'no';
	$notice_reply = ( isset( $wcfm_capability_options['notice_reply'] ) ) ? $wcfm_capability_options['notice_reply'] : 'no';
	$notification = ( isset( $wcfm_capability_options['notification'] ) ) ? $wcfm_capability_options['notification'] : 'no';
	$direct_message = ( isset( $wcfm_capability_options['direct_message'] ) ) ? $wcfm_capability_options['direct_message'] : 'no';
	$enquiry = ( isset( $wcfm_capability_options['enquiry'] ) ) ? $wcfm_capability_options['enquiry'] : 'no';
	$profile = ( isset( $wcfm_capability_options['profile'] ) ) ? $wcfm_capability_options['profile'] : 'no';
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Shipping Tracking', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_shipping_tracking', array(  
																																 "shipping_tracking" => array('label' => __('Allow', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[shipping_tracking]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $shipping_tracking),
																									) ) );
	?>
	
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Header Panels', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_header_panel', array(  
																																 "knowledgebase" => array('label' => __('Knowledgebase', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[knowledgebase]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $knowledgebase),
																																 "notice" => array('label' => __('Notice', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[notice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice),
																																 "notice_reply" => array('label' => __('Topic Reply', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[notice_reply]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice_reply),
																																 "notification" => array('label' => __('Notification', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[notification]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notification),
																																 "direct_message" => array('label' => __('Direct Message', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[direct_message]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $direct_message),
																																 "enquiry" => array('label' => __('Enquiry', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[enquiry]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $enquiry),
																																 "profile" => array('label' => __('Profile', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[profile]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $profile),
																									) ) );
	?>
	
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Profile', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_profile', array(  
																																 "address" => array('label' => __('Address', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[address]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $address),
																																 "social" => array('label' => __('Social', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[social]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $social),
																									) ) );
		
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Settings', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_vendor_settings', array(  
																																 "vacation" => array('label' => __('Vacation', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[vacation]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vacation),
																																 "brand" => array('label' => __('Brand', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[brand]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $brand),
																																 "vshipping" => array('label' => __('Shipping', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[vshipping]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vshipping),
																																 "billing" => array('label' => __('Billing', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[billing]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $billing),
																									) ) );
		
}
?>