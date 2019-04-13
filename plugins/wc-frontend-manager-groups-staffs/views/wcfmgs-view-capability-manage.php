<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Groups/Users capability Manage View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/view
 * @version   1.0.4
 */
 
global $wp, $WCFM, $WCFMu, $WCFMgs, $wcfmgs_capability_manager_options;

// Product Capabilities
$manage_products = ( isset( $wcfmgs_capability_manager_options['manage_products'] ) ) ? $wcfmgs_capability_manager_options['manage_products'] : 'no';
$add_products = ( isset( $wcfmgs_capability_manager_options['add_products'] ) ) ? $wcfmgs_capability_manager_options['add_products'] : 'no';
$edit_products = ( isset( $wcfmgs_capability_manager_options['edit_products'] ) ) ? $wcfmgs_capability_manager_options['edit_products'] : 'no';
$publish_products = ( isset( $wcfmgs_capability_manager_options['publish_products'] ) ) ? $wcfmgs_capability_manager_options['publish_products'] : 'no';
$publish_live_products = ( isset( $wcfmgs_capability_manager_options['publish_live_products'] ) ) ? $wcfmgs_capability_manager_options['publish_live_products'] : 'no';
$delete_products = ( isset( $wcfmgs_capability_manager_options['delete_products'] ) ) ? $wcfmgs_capability_manager_options['delete_products'] : 'no';

$simple = ( isset( $wcfmgs_capability_manager_options['simple'] ) ) ? $wcfmgs_capability_manager_options['simple'] : 'no';
$variable = ( isset( $wcfmgs_capability_manager_options['variable'] ) ) ? $wcfmgs_capability_manager_options['variable'] : 'no';
$grouped = ( isset( $wcfmgs_capability_manager_options['grouped'] ) ) ? $wcfmgs_capability_manager_options['grouped'] : 'no';
$external = ( isset( $wcfmgs_capability_manager_options['external'] ) ) ? $wcfmgs_capability_manager_options['external'] : 'no';

$inventory = ( isset( $wcfmgs_capability_manager_options['inventory'] ) ) ? $wcfmgs_capability_manager_options['inventory'] : 'no';
$shipping = ( isset( $wcfmgs_capability_manager_options['shipping'] ) ) ? $wcfmgs_capability_manager_options['shipping'] : 'no';
$taxes = ( isset( $wcfmgs_capability_manager_options['taxes'] ) ) ? $wcfmgs_capability_manager_options['taxes'] : 'no';
$linked = ( isset( $wcfmgs_capability_manager_options['linked'] ) ) ? $wcfmgs_capability_manager_options['linked'] : 'no';
$attributes = ( isset( $wcfmgs_capability_manager_options['attributes'] ) ) ? $wcfmgs_capability_manager_options['attributes'] : 'no';
$advanced = ( isset( $wcfmgs_capability_manager_options['advanced'] ) ) ? $wcfmgs_capability_manager_options['advanced'] : 'no';
$catalog = ( isset( $wcfmgs_capability_manager_options['catalog'] ) ) ? $wcfmgs_capability_manager_options['catalog'] : 'no';

$featured_img = ( isset( $wcfmgs_capability_manager_options['featured_img'] ) ) ? $wcfmgs_capability_manager_options['featured_img'] : 'no';
$gallery_img = ( isset( $wcfmgs_capability_manager_options['gallery_img'] ) ) ? $wcfmgs_capability_manager_options['gallery_img'] : 'no';
$category = ( isset( $wcfmgs_capability_manager_options['category'] ) ) ? $wcfmgs_capability_manager_options['category'] : 'no';
$add_category = ( isset( $wcfmgs_capability_manager_options['add_category'] ) ) ? $wcfmgs_capability_manager_options['add_category'] : 'no';
$tags = ( isset( $wcfmgs_capability_manager_options['tags'] ) ) ? $wcfmgs_capability_manager_options['tags'] : 'no';
$addons = ( isset( $wcfmgs_capability_manager_options['addons'] ) ) ? $wcfmgs_capability_manager_options['addons'] : 'no';
$toolset_types = ( isset( $wcfmgs_capability_manager_options['toolset_types'] ) ) ? $wcfmgs_capability_manager_options['toolset_types'] : 'no';
$acf_fields = ( isset( $wcfmgs_capability_manager_options['acf_fields'] ) ) ? $wcfmgs_capability_manager_options['acf_fields'] : 'no';
$mappress = ( isset( $wcfmgs_capability_manager_options['mappress'] ) ) ? $wcfmgs_capability_manager_options['mappress'] : 'no';

$add_attribute = ( isset( $wcfmgs_capability_manager_options['add_attribute'] ) ) ? $wcfmgs_capability_manager_options['add_attribute'] : 'no';
$add_attribute_term = ( isset( $wcfmgs_capability_manager_options['add_attribute_term'] ) ) ? $wcfmgs_capability_manager_options['add_attribute_term'] : 'no';
$rich_editor = ( isset( $wcfmgs_capability_manager_options['rich_editor'] ) ) ? $wcfmgs_capability_manager_options['rich_editor'] : 'no';
$featured_product = ( isset( $wcfmgs_capability_manager_options['featured_product'] ) ) ? $wcfmgs_capability_manager_options['featured_product'] : 'no';
$duplicate_product = ( isset( $wcfmgs_capability_manager_options['duplicate_product'] ) ) ? $wcfmgs_capability_manager_options['duplicate_product'] : 'no';
$product_import = ( isset( $wcfmgs_capability_manager_options['product_import'] ) ) ? $wcfmgs_capability_manager_options['product_import'] : 'no';
$product_export = ( isset( $wcfmgs_capability_manager_options['product_export'] ) ) ? $wcfmgs_capability_manager_options['product_export'] : 'no';
$product_quick_edit = ( isset( $wcfmgs_capability_manager_options['product_quick_edit'] ) ) ? $wcfmgs_capability_manager_options['product_quick_edit'] : 'no';
$product_bulk_edit = ( isset( $wcfmgs_capability_manager_options['product_bulk_edit'] ) ) ? $wcfmgs_capability_manager_options['product_bulk_edit'] : 'no';
$stock_manager = ( isset( $wcfmgs_capability_manager_options['stock_manager'] ) ) ? $wcfmgs_capability_manager_options['stock_manager'] : 'no';

$manage_sku = ( isset( $wcfmgs_capability_manager_options['manage_sku'] ) ) ? $wcfmgs_capability_manager_options['manage_sku'] : 'no';
$manage_price = ( isset( $wcfmgs_capability_manager_options['manage_price'] ) ) ? $wcfmgs_capability_manager_options['manage_price'] : 'no';
$manage_sales_price = ( isset( $wcfmgs_capability_manager_options['manage_sales_price'] ) ) ? $wcfmgs_capability_manager_options['manage_sales_price'] : 'no';
$manage_sales_scheduling = ( isset( $wcfmgs_capability_manager_options['manage_sales_scheduling'] ) ) ? $wcfmgs_capability_manager_options['manage_sales_scheduling'] : 'no';
$manage_excerpt = ( isset( $wcfmgs_capability_manager_options['manage_excerpt'] ) ) ? $wcfmgs_capability_manager_options['manage_excerpt'] : 'no';
$manage_description = ( isset( $wcfmgs_capability_manager_options['manage_description'] ) ) ? $wcfmgs_capability_manager_options['manage_description'] : 'no';

$spacelimit = ( !empty( $wcfmgs_capability_manager_options['spacelimit'] ) ) ? $wcfmgs_capability_manager_options['spacelimit'] : '';
$articlelimit = ( !empty( $wcfmgs_capability_manager_options['articlelimit'] ) ) ? $wcfmgs_capability_manager_options['articlelimit'] : '';
$productlimit = ( !empty( $wcfmgs_capability_manager_options['productlimit'] ) ) ? $wcfmgs_capability_manager_options['productlimit'] : '';
$featured_product_limit = ( !empty( $wcfmgs_capability_manager_options['featured_product_limit'] ) ) ? $wcfmgs_capability_manager_options['featured_product_limit'] : '';
$gallerylimit = ( !empty( $wcfmgs_capability_manager_options['gallerylimit'] ) ) ? $wcfmgs_capability_manager_options['gallerylimit'] : '';
$catlimit = ( !empty( $wcfmgs_capability_manager_options['catlimit'] ) ) ? $wcfmgs_capability_manager_options['catlimit'] : '';
$allowed_article_category    = ( !empty( $wcfmgs_capability_manager_options['allowed_article_category'] ) ) ? $wcfmgs_capability_manager_options['allowed_article_category'] : array();
$allowed_categories    = ( !empty( $wcfmgs_capability_manager_options['allowed_categories'] ) ) ? $wcfmgs_capability_manager_options['allowed_categories'] : array();

// Marketplace Capabilities
$vendor_email = ( isset( $wcfmgs_capability_manager_options['vendor_email'] ) ) ? $wcfmgs_capability_manager_options['vendor_email'] : 'no';
$vendor_phone = ( isset( $wcfmgs_capability_manager_options['vendor_phone'] ) ) ? $wcfmgs_capability_manager_options['vendor_phone'] : 'no';
$vendor_address = ( isset( $wcfmgs_capability_manager_options['vendor_address'] ) ) ? $wcfmgs_capability_manager_options['vendor_address'] : 'no';
$vendor_map = ( isset( $wcfmgs_capability_manager_options['vendor_map'] ) ) ? $wcfmgs_capability_manager_options['vendor_map'] : 'no';
$vendor_social = ( isset( $wcfmgs_capability_manager_options['vendor_social'] ) ) ? $wcfmgs_capability_manager_options['vendor_social'] : 'no';
$vendor_follower = ( isset( $wcfmgs_capability_manager_options['vendor_follower'] ) ) ? $wcfmgs_capability_manager_options['vendor_follower'] : 'no';
$vendor_policy = ( isset( $wcfmgs_capability_manager_options['vendor_policy'] ) ) ? $wcfmgs_capability_manager_options['vendor_policy'] : 'no';
$customer_support = ( isset( $wcfmgs_capability_manager_options['customer_support'] ) ) ? $wcfmgs_capability_manager_options['customer_support'] : 'no';
$refund_requests = ( isset( $wcfmgs_capability_manager_options['refund_requests'] ) ) ? $wcfmgs_capability_manager_options['refund_requests'] : 'no';
$review_manage = ( isset( $wcfmgs_capability_manager_options['review_manage'] ) ) ? $wcfmgs_capability_manager_options['review_manage'] : 'no';
$ledger_book = ( isset( $wcfmgs_capability_manager_options['ledger_book'] ) ) ? $wcfmgs_capability_manager_options['ledger_book'] : 'no';

// Withdrawal Capabilities
$vendor_withdrwal = ( !empty( $wcfmgs_capability_manager_options['vendor_withdrwal'] ) ) ? $wcfmgs_capability_manager_options['vendor_withdrwal'] : '';
$vendor_transactions = ( !empty( $wcfmgs_capability_manager_options['vendor_transactions'] ) ) ? $wcfmgs_capability_manager_options['vendor_transactions'] : '';
$vendor_transaction_details = ( !empty( $wcfmgs_capability_manager_options['vendor_transaction_details'] ) ) ? $wcfmgs_capability_manager_options['vendor_transaction_details'] : '';

// Miscellaneous Capabilities
$associate_listings = ( isset( $wcfmgs_capability_manager_options['associate_listings'] ) ) ? $wcfmgs_capability_manager_options['associate_listings'] : 'no';

// Article Capabilities
$submit_articles = ( isset( $wcfmgs_capability_manager_options['submit_articles'] ) ) ? $wcfmgs_capability_manager_options['submit_articles'] : 'no';
$add_articles = ( isset( $wcfmgs_capability_manager_options['add_articles'] ) ) ? $wcfmgs_capability_manager_options['add_articles'] : 'no';
$publish_articles = ( isset( $wcfmgs_capability_manager_options['publish_articles'] ) ) ? $wcfmgs_capability_manager_options['publish_articles'] : 'no';
$edit_live_articles = ( isset( $wcfmgs_capability_manager_options['edit_live_articles'] ) ) ? $wcfmgs_capability_manager_options['edit_live_articles'] : 'no';
$publish_live_articles = ( isset( $wcfmgs_capability_manager_options['publish_live_articles'] ) ) ? $wcfmgs_capability_manager_options['publish_live_articles'] : 'no';
$delete_articles = ( isset( $wcfmgs_capability_manager_options['delete_articles'] ) ) ? $wcfmgs_capability_manager_options['delete_articles'] : 'no';

// Coupon Capabilities
$manage_coupons = ( isset( $wcfmgs_capability_manager_options['manage_coupons'] ) ) ? $wcfmgs_capability_manager_options['manage_coupons'] : 'no';
$add_coupons = ( isset( $wcfmgs_capability_manager_options['add_coupons'] ) ) ? $wcfmgs_capability_manager_options['add_coupons'] : 'no';
$publish_coupons = ( isset( $wcfmgs_capability_manager_options['publish_coupons'] ) ) ? $wcfmgs_capability_manager_options['publish_coupons'] : 'no';
$edit_live_coupons = ( isset( $wcfmgs_capability_manager_options['edit_live_coupons'] ) ) ? $wcfmgs_capability_manager_options['edit_live_coupons'] : 'no';
$publish_live_coupons = ( isset( $wcfmgs_capability_manager_options['publish_live_coupons'] ) ) ? $wcfmgs_capability_manager_options['publish_live_coupons'] : 'no';
$delete_coupons = ( isset( $wcfmgs_capability_manager_options['delete_coupons'] ) ) ? $wcfmgs_capability_manager_options['delete_coupons'] : 'no';
$free_shipping_coupons = ( isset( $wcfmgs_capability_manager_options['free_shipping_coupons'] ) ) ? $wcfmgs_capability_manager_options['free_shipping_coupons'] : 'no';

// Bookings Capabilities
$manual_booking = ( isset( $wcfmgs_capability_manager_options['manual_booking'] ) ) ? $wcfmgs_capability_manager_options['manual_booking'] : 'no';
$manage_resource = ( isset( $wcfmgs_capability_manager_options['manage_resource'] ) ) ? $wcfmgs_capability_manager_options['manage_resource'] : 'no';
$booking_list = ( isset( $wcfmgs_capability_manager_options['booking_list'] ) ) ? $wcfmgs_capability_manager_options['booking_list'] : 'no';
$booking_calendar = ( isset( $wcfmgs_capability_manager_options['booking_calendar'] ) ) ? $wcfmgs_capability_manager_options['booking_calendar'] : 'no';

// Appointment Capabilities
$manual_appointment = ( isset( $wcfmgs_capability_manager_options['manual_appointment'] ) ) ? $wcfmgs_capability_manager_options['manual_appointment'] : 'no';
$manage_appointment_staff = ( isset( $wcfmgs_capability_manager_options['manage_appointment_staff'] ) ) ? $wcfmgs_capability_manager_options['manage_appointment_staff'] : 'no';
$appointment_list = ( isset( $wcfmgs_capability_manager_options['appointment_list'] ) ) ? $wcfmgs_capability_manager_options['appointment_list'] : 'no';
$appointment_calendar = ( isset( $wcfmgs_capability_manager_options['appointment_calendar'] ) ) ? $wcfmgs_capability_manager_options['appointment_calendar'] : 'no';

// Subscription Capabilities
$subscription_list = ( isset( $wcfmgs_capability_manager_options['subscription_list'] ) ) ? $wcfmgs_capability_manager_options['subscription_list'] : 'no';
$subscription_details = ( isset( $wcfmgs_capability_manager_options['subscription_details'] ) ) ? $wcfmgs_capability_manager_options['subscription_details'] : 'no';
$subscription_status_update = ( isset( $wcfmgs_capability_manager_options['subscription_status_update'] ) ) ? $wcfmgs_capability_manager_options['subscription_status_update'] : 'no';
$subscription_schedule_update = ( isset( $wcfmgs_capability_manager_options['subscription_schedule_update'] ) ) ? $wcfmgs_capability_manager_options['subscription_schedule_update'] : 'no';

// Orders Capabilities
$view_orders  = ( isset( $wcfmgs_capability_manager_options['view_orders'] ) ) ? $wcfmgs_capability_manager_options['view_orders'] : 'no';
$order_status_update  = ( isset( $wcfmgs_capability_manager_options['order_status_update'] ) ) ? $wcfmgs_capability_manager_options['order_status_update'] : 'no';
$view_order_details = ( isset( $wcfmgs_capability_manager_options['view_order_details'] ) ) ? $wcfmgs_capability_manager_options['view_order_details'] : 'no';
$view_comments  = ( isset( $wcfmgs_capability_manager_options['view_comments'] ) ) ? $wcfmgs_capability_manager_options['view_comments'] : 'no';
$submit_comments  = ( isset( $wcfmgs_capability_manager_options['submit_comments'] ) ) ? $wcfmgs_capability_manager_options['submit_comments'] : 'no';
$export_csv  = ( isset( $wcfmgs_capability_manager_options['export_csv'] ) ) ? $wcfmgs_capability_manager_options['export_csv'] : 'no';

$store_invoice = ( isset( $wcfmgs_capability_manager_options['store_invoice'] ) ) ? $wcfmgs_capability_manager_options['store_invoice'] : 'no';
$pdf_invoice = ( isset( $wcfmgs_capability_manager_options['pdf_invoice'] ) ) ? $wcfmgs_capability_manager_options['pdf_invoice'] : 'no';
$pdf_packing_slip = ( isset( $wcfmgs_capability_manager_options['pdf_packing_slip'] ) ) ? $wcfmgs_capability_manager_options['pdf_packing_slip'] : 'no';

// Customer Capabilities
$manage_customers = ( isset( $wcfmgs_capability_manager_options['manage_customers'] ) ) ? $wcfmgs_capability_manager_options['manage_customers'] : 'no';
$add_customers = ( isset( $wcfmgs_capability_manager_options['add_customers'] ) ) ? $wcfmgs_capability_manager_options['add_customers'] : 'no';
$edit_customers = ( isset( $wcfmgs_capability_manager_options['edit_customers'] ) ) ? $wcfmgs_capability_manager_options['edit_customers'] : 'no';
$view_customers = ( isset( $wcfmgs_capability_manager_options['view_customers'] ) ) ? $wcfmgs_capability_manager_options['view_customers'] : 'no';
$view_customers_orders = ( isset( $wcfmgs_capability_manager_options['view_customers_orders'] ) ) ? $wcfmgs_capability_manager_options['view_customers_orders'] : 'no';
$view_customers_name  = ( isset( $wcfmgs_capability_manager_options['view_name'] ) ) ? $wcfmgs_capability_manager_options['view_name'] : 'no';
$view_customers_email  = ( isset( $wcfmgs_capability_manager_options['view_email'] ) ) ? $wcfmgs_capability_manager_options['view_email'] : 'no';
$view_billing_details = ( isset( $wcfmgs_capability_manager_options['view_billing_details'] ) ) ? $wcfmgs_capability_manager_options['view_billing_details'] : 'no';
$view_shipping_details =  ( isset( $wcfmgs_capability_manager_options['view_shipping_details'] ) ) ? $wcfmgs_capability_manager_options['view_shipping_details'] : 'no';
$customerlimit = ( !empty( $wcfmgs_capability_manager_options['customerlimit'] ) ) ? $wcfmgs_capability_manager_options['customerlimit'] : '';

$shipping_tracking = ( isset( $wcfmgs_capability_manager_options['shipping_tracking'] ) ) ? $wcfmgs_capability_manager_options['shipping_tracking'] : 'no';

$support_ticket = ( isset( $wcfmgs_capability_manager_options['support_ticket'] ) ) ? $wcfmgs_capability_manager_options['support_ticket'] : 'no';
$support_ticket_manage = ( isset( $wcfmgs_capability_manager_options['support_ticket_manage'] ) ) ? $wcfmgs_capability_manager_options['support_ticket_manage'] : 'no';

$view_reports  = ( isset( $wcfmgs_capability_manager_options['view_reports'] ) ) ? $wcfmgs_capability_manager_options['view_reports'] : 'no';

$knowledgebase = ( isset( $wcfmgs_capability_manager_options['knowledgebase'] ) ) ? $wcfmgs_capability_manager_options['knowledgebase'] : 'no';
$notice = ( isset( $wcfmgs_capability_manager_options['notice'] ) ) ? $wcfmgs_capability_manager_options['notice'] : 'no';
$notice_reply = ( isset( $wcfmgs_capability_manager_options['notice_reply'] ) ) ? $wcfmgs_capability_manager_options['notice_reply'] : 'no';
$notification = ( isset( $wcfmgs_capability_manager_options['notification'] ) ) ? $wcfmgs_capability_manager_options['notification'] : 'no';
$direct_message = ( isset( $wcfmgs_capability_manager_options['direct_message'] ) ) ? $wcfmgs_capability_manager_options['direct_message'] : 'no';
$enquiry = ( isset( $wcfmgs_capability_manager_options['enquiry'] ) ) ? $wcfmgs_capability_manager_options['enquiry'] : 'no';
$profile = ( isset( $wcfmgs_capability_manager_options['profile'] ) ) ? $wcfmgs_capability_manager_options['profile'] : 'no';

$address = ( isset( $wcfmgs_capability_manager_options['address'] ) ) ? $wcfmgs_capability_manager_options['address'] : 'no';
$social = ( isset( $wcfmgs_capability_manager_options['social'] ) ) ? $wcfmgs_capability_manager_options['social'] : 'no';

$brand = ( isset( $wcfmgs_capability_manager_options['brand'] ) ) ? $wcfmgs_capability_manager_options['brand'] : 'no';
$vshipping = ( isset( $wcfmgs_capability_manager_options['vshipping'] ) ) ? $wcfmgs_capability_manager_options['vshipping'] : 'no';
$billing = ( isset( $wcfmgs_capability_manager_options['billing'] ) ) ? $wcfmgs_capability_manager_options['billing'] : 'no';
$store_seo = ( isset( $wcfmgs_capability_manager_options['store_seo'] ) ) ? $wcfmgs_capability_manager_options['store_seo'] : 'no';
$policy = ( isset( $wcfmgs_capability_manager_options['policy'] ) ) ? $wcfmgs_capability_manager_options['policy'] : 'no';
$vacation = ( isset( $wcfmgs_capability_manager_options['vacation'] ) ) ? $wcfmgs_capability_manager_options['vacation'] : 'no';

$store_logo = ( isset( $wcfmgs_capability_manager_options['store_logo'] ) ) ? $wcfmgs_capability_manager_options['store_logo'] : 'no';
$store_banner = ( isset( $wcfmgs_capability_manager_options['store_banner'] ) ) ? $wcfmgs_capability_manager_options['store_banner'] : 'no';
$store_name = ( isset( $wcfmgs_capability_manager_options['store_name'] ) ) ? $wcfmgs_capability_manager_options['store_name'] : 'no';
$store_description = ( isset( $wcfmgs_capability_manager_options['store_description'] ) ) ? $wcfmgs_capability_manager_options['store_description'] : 'no';
$store_phone = ( isset( $wcfmgs_capability_manager_options['store_phone'] ) ) ? $wcfmgs_capability_manager_options['store_phone'] : 'no';
$store_address = ( isset( $wcfmgs_capability_manager_options['store_address'] ) ) ? $wcfmgs_capability_manager_options['store_address'] : 'no';

$vnd_wpadmin = ( isset( $wcfmgs_capability_manager_options['vnd_wpadmin'] ) ) ? $wcfmgs_capability_manager_options['vnd_wpadmin'] : 'no';

$manage_settings = ( isset( $wcfmgs_capability_manager_options['manage_settings'] ) ) ? $wcfmgs_capability_manager_options['manage_settings'] : 'no';
$capability_controller = ( isset( $wcfmgs_capability_manager_options['capability_controller'] ) ) ? $wcfmgs_capability_manager_options['capability_controller'] : 'no';

$manage_groups = ( isset( $wcfmgs_capability_manager_options['manage_groups'] ) ) ? $wcfmgs_capability_manager_options['manage_groups'] : 'no';
$manage_managers = ( isset( $wcfmgs_capability_manager_options['manage_managers'] ) ) ? $wcfmgs_capability_manager_options['manage_managers'] : 'no';
$manage_staffs = ( isset( $wcfmgs_capability_manager_options['manage_staffs'] ) ) ? $wcfmgs_capability_manager_options['manage_staffs'] : 'no';
$stafflimit = ( !empty( $wcfmgs_capability_manager_options['stafflimit'] ) ) ? $wcfmgs_capability_manager_options['stafflimit'] : '';

$analytics = ( isset( $wcfmgs_capability_manager_options['analytics'] ) ) ? $wcfmgs_capability_manager_options['analytics'] : 'no';

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
	
	$article_categories = array();
	$article_category_lists = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'parent' => 0, 'fields' => 'id=>name' ) );
	if( !empty( $article_category_lists ) ) {
		foreach( $article_category_lists as $article_category_id => $article_category_name ) {
			$article_category_list = get_term( $article_category_id );
			$article_category_list->term_id = $article_category_id;
			$article_category_list->name = $article_category_name;
			$article_categories[$article_category_id] = $article_category_list;
		}
	}
} else {
	$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );
	$article_categories   = get_terms( 'category', 'orderby=name&hide_empty=0&parent=0' );
}

$is_marketplace = wcfm_is_marketplace();
?>

			<!-- collapsible -->
			<div class="page_collapsible" id="groups_manage_capability_head">
				<label class="fa fa-user-times"></label>
				<?php _e('Capabilities', 'wc-frontend-manager-groups-staffs'); ?>
			</div> 
			<div class="wcfm-container">
				<div id="groups_manage_capability_expander" class="wcfm-content">
					<div class="capability_head_message"><?php _e( "Configure what to hide from User(s)", 'wc-frontend-manager' ); ?></div>
				
					<div class="vendor_capability">
						
						<div class="vendor_product_capability">
							<div class="vendor_capability_heading"><h3><?php _e( 'Products', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_products', array(
																																																									"manage_products" => array('label' => __('Manage Products', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[manage_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_products),
																																																									"add_products" => array('label' => __('Add Products', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[add_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_products),
																																																									"publish_products" => array('label' => __('Publish Products', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[publish_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_products),
																																																									"edit_products" => array('label' => __('Edit Live Products', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[edit_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_products),
																																																									"publish_live_products" => array('label' => __('Auto Publish Live Products', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[publish_live_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_products),
																																																									"delete_products" => array('label' => __('Delete Products', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[delete_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_products),
																												) ) );
							?>
								
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Types', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_types', array( 
																																																												"simple" => array('label' => __('Simple', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[simple]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $simple),
																																																												"variable" => array('label' => __('Variable', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[variable]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $variable),
																																																												"grouped" => array('label' => __('Grouped', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[grouped]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $grouped),
																																																												"external" => array('label' => __('External / Affiliate', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[external]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $external),
																												), 'wcfmgs_capability_manager_options', $wcfmgs_capability_manager_options ) );
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Panels', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_panels', array(
																																																															 "inventory" => array('label' => __('Inventory', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[inventory]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $inventory),
																																																															 "shipping" => array('label' => __('Shipping', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[shipping]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $shipping),
																																																															 "taxes" => array('label' => __('Taxes', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[taxes]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $taxes),
																																																															 "linked" => array('label' => __('Linked', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[linked]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $linked),
																																																															 "attributes" => array('label' => __('Attributes', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[attributes]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $attributes),
																																																															 "advanced" => array('label' => __('Advanced', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[advanced]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $advanced),
																																																															 "catalog" => array('label' => __('Catalog', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[catalog]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $catalog),
																												) ) );
							
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Sections', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_sections', array(
																																																															 "featured_img" => array('label' => __('Featured Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[featured_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_img),
																																																															 "gallery_img" => array('label' => __('Gallery Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[gallery_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $gallery_img),
																																																															 "category" => array('label' => __('Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $category),
																																																															 "add_category" => array('label' => __('Add Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[add_category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_category),
																																																															 "tags" => array('label' => __('Tags', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[tags]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $tags),
																																																															 "addons" => array('label' => __('Add-ons', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[addons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $addons),
																																																															 "toolset_types" => array('label' => __('Toolset Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[toolset_types]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $toolset_types),
																																																															 "acf_fields" => array('label' => __('ACF Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[acf_fields]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $acf_fields),
																																																															 "mappress" => array('label' => __('Location', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[mappress]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $mappress),
																												) ) );
							?>
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Insights', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_insights', array(
								                                                                                                               "add_attribute" => array('label' => __('Add Attribute', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[add_attribute]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute),
								                                                                                                               "add_attribute_term" => array('label' => __('Add Attribute Term', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[add_attribute_term]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute_term),
																																																															 "rich_editor" => array('label' => __('Rich Editor', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[rich_editor]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $rich_editor),
																																																															 "featured_product" => array('label' => __('Featured Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[featured_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_product),
																																																															 "duplicate_product" => array('label' => __('Duplicate Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[duplicate_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $duplicate_product),
																																																															 "product_import" => array('label' => __('Import', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[product_import]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_import),
																																																															 "product_export" => array('label' => __('Export', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[product_export]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_export),
																																																															 "product_quick_edit" => array('label' => __('Quick Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[product_quick_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_quick_edit),
																																																															 "product_bulk_edit" => array('label' => __('Bulk Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[product_bulk_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_bulk_edit),
																																																															 "stock_manager" => array('label' => __('Stock Manager', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[stock_manager]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $stock_manager),
																												) ) );
							?>  
							
							<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Fields', 'wc-frontend-manager-ultimate' ); ?></h3></div>
								
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_fields', array(
																																																																 "manage_sku" => array('label' => __('SKU', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_sku]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sku),
																																																																 "manage_price" => array('label' => __('Price', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_price]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_price),
																																																																 "manage_sales_price" => array('label' => __('Sales Price', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_sales_price]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sales_price),
																																																																 "manage_sales_scheduling" => array('label' => __('Sales Schedule', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[manage_sales_scheduling]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sales_scheduling),
																																																																 "manage_excerpt" => array('label' => __('Short Description', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_excerpt]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_excerpt),
																																																																 "manage_description" => array('label' => __('Description', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_description]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_description),
																													) ) );
								?>
							<?php } ?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Limits', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_limits', array(
																																																															 "spacelimit"   => array( 'label' => __('Space Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[spacelimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $spacelimit, 'hints' => __( 'Total disk space allow to use by an user. Disk space unit is in MB. e.g. set 100 to allocate 100 MB space for a vendor. Only attachments are considered in space calculation. ', 'wc-frontend-manager-ultimate' ) ),
																																																															 "articlelimit" => array( 'label' => __('Article Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[articlelimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $articlelimit, 'hints' => __( 'No. of Articles allow to add by an user.', 'wc-frontend-manager-groups-staffs' ) . ' ' . __( 'Set `-1` if you want to restrict limit at `0`.', 'wc-frontend-manager-groups-staffs' ) ),
																																																															 "productlimit" => array( 'label' => __('Product Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[productlimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $productlimit, 'hints' => __( 'No. of Products allow to add by an user.', 'wc-frontend-manager-groups-staffs' ) . ' ' . __( 'Set `-1` if you want to restrict limit at `0`.', 'wc-frontend-manager-groups-staffs' ) ),
																																																															 "featured_product_limit" => array( 'label' => __('Featured Product Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[featured_product_limit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $featured_product_limit),
																																																															 "gallerylimit" => array( 'label' => __('Gallery Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[gallerylimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $gallerylimit),
																																																															 "catlimit" => array( 'label' => __('Category Limit', 'wc-frontend-manager-ultimate'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[catlimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele catlimit_ele', 'label_class' => 'wcfm_title catlimit_title', 'value' => $catlimit),
																											) ) );
							?>
							
							<p class="wcfm_title catlimit_title"><strong><?php _e( 'Article Categories', 'wc-frontend-manager-ultimate' ); ?></strong></p><label class="screen-reader-text" for="vendor_product_cats"><?php _e( 'Allowed Article Cats', 'wc-frontend-manager-ultimate' ); ?></label>
							<select id="vendor_allowed_article_category" name="wcfmgs_capability_manager_options[allowed_article_category][]" class="wcfm-select wcfm_ele" multiple="multiple" data-catlimit="-1" style="width: 44%; margin-bottom: 10px;">
								<?php
									if ( $product_categories ) {
										$WCFM->library->generateTaxonomyHTML( 'category', $article_categories, $allowed_article_category, '', false, false, false );
									}
								?>
							</select>
	
							<p class="wcfm_title catlimit_title"><strong><?php _e( 'Categories', 'wc-frontend-manager-ultimate' ); ?></strong></p><label class="screen-reader-text" for="vendor_product_cats"><?php _e( 'Allowed Categories', 'wc-frontend-manager-ultimate' ); ?></label>
							<select id="group_allowed_categories" name="wcfmgs_capability_manager_options[allowed_categories][]" class="wcfm-select wcfm_ele" multiple="multiple" data-catlimit="-1" style="width: 44%; margin-bottom: 10px;">
								<?php
									if ( $product_categories ) {
										$WCFM->library->generateTaxonomyHTML( 'product_cat', $product_categories, $allowed_categories, '', false, false, false );
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
											$allowed_custom_taxonomies    = ( !empty( $wcfmgs_capability_manager_options['allowed_' . $product_taxonomy->name] ) ) ? $wcfmgs_capability_manager_options['allowed_' . $product_taxonomy->name] : array();
											?>
											<p class="wcfm_title catlimit_title"><strong><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e( 'Allowed ', 'wc-frontend-manager-ultimate' ); ?><?php _e( $product_taxonomy->label, 'wc-frontend-manager' ); ?></label>
											<select id="group_allowed_<?php echo $product_taxonomy->name; ?>" name="wcfmgs_capability_manager_options[allowed_<?php echo $product_taxonomy->name; ?>][]" class="wcfm-select wcfm_ele group_allowed_custom_taxonomies" multiple="multiple" style="width: 44%; margin-bottom: 10px;">
												<?php
													$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
													if ( $product_taxonomy_terms ) {
														$WCFM->library->generateTaxonomyHTML( $product_taxonomy->name, $product_taxonomy_terms, $allowed_custom_taxonomies, '', false, false, false );
													}
												?>
											</select>
											<?php
										}
									}
								}
							}
							
							do_action( 'wcfm_capability_manager_left_panel', $wcfmgs_capability_manager_options );
							?>
						</div>
						
						<div class="vendor_other_capability">
						
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_heading"><h3><?php _e( 'Access', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_access', array(  
																																					 "vnd_wpadmin" => array('label' => __('Backend Access', 'wc-frontend-manager') . ' (wp-admin)', 'name' => 'wcfmgs_capability_manager_options[vnd_wpadmin]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vnd_wpadmin),
																														) ) );
							?>
							
							<?php if( $WCFM->is_marketplace == 'wcfmmarketplace' ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Marketplace', 'wc-frontend-manager' ); ?></h3></div>
								
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_wcfmmarkerplace',
																																							array( 
																																										 "vendor_email" => array('label' => __('Show Email', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_email]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_email),
																																										 "vendor_phone" => array('label' => __('Show Phone', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_phone]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_phone),
																																										 "vendor_address" => array('label' => __('Show Address', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_address]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_address),
																																										 "vendor_map" => array('label' => __('Show Map', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_map]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_map),
																																										 "vendor_social" => array('label' => __('Show Social', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_social]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_social),
																																										 "vendor_follower" => array('label' => __('Show Follower', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_follower]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_follower),
																																										 "vendor_policy" => array('label' => __('Show Policy', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_policy]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_policy),
																																										 "customer_support" => array('label' => __('Customer Support', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[customer_support]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $customer_support),
																																										 "refund_requests"     => array('label' => __('Refund Requests', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[refund_requests]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $refund_requests),
																																										 "review_manage"       => array('label' => __('Reviews Manage', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[review_manage]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $review_manage),
																																										 "ledger_book"       => array('label' => __('Ledger Book', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[ledger_book]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $ledger_book),
																													) ) );
								?>
									
							<?php } ?>
							
							<?php
							if( apply_filters( 'wcfm_is_pref_withdrawal', true ) ) {
								if( $is_marketplace && in_array( $is_marketplace, array( 'dokan', 'wcmarketplace', 'wcfmmarketplace' ) ) ) {
									?>
									<div class="wcfm_clearfix"></div>
									<div class="vendor_capability_sub_heading"><h3><?php _e( 'Withdrawal', 'wc-frontend-manager' ); ?></h3></div>
									
									<?php
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_withdrwal', array(
																																	"vendor_withdrwal" => array('label' => __('Withdrwal Request', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_withdrwal]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_withdrwal),
																																	"vendor_transactions" => array('label' => __('Transactions', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_transactions]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_transactions),
																																	"vendor_transaction_details" => array('label' => __('Transaction Details', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[vendor_transaction_details]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vendor_transaction_details),
																															 ) ) );
									
									?>
									<?php
								}
							}	
							?>
						
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Integrations', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							if( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_listings', array(  "associate_listings" => array('label' => __('Associate Listings', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[associate_listings]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'desc' => __( 'by WP Job Manager.', 'wc-frontend-manager' ), 'dfvalue' => $associate_listings),
																														) ) );
							}
							?>
							
							<?php if( apply_filters( 'wcfm_is_pref_article', true ) ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Articles', 'wc-frontend-manager' ); ?></h3></div>
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_articles', array(
																																																													 "submit_articles" => array('label' => __('Manage Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[submit_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_articles),
																																																													 "add_articles" => array('label' => __('Add Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[add_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_articles),
																																																													 "publish_articles" => array('label' => __('Publish Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[publish_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_articles),
																																																													 "edit_live_articles" => array('label' => __('Edit Live Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[edit_live_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_live_articles),
																																																													 "publish_live_articles" => array('label' => __('Auto Publish Live Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[publish_live_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_articles),
																																																													 "delete_articles" => array('label' => __('Delete Articles', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[delete_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_articles)
																													) ) );
								?>
							<?php } ?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Coupons', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_coupons', array( 
																																																												 "manage_coupons" => array('label' => __('Manage Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_coupons),
																																																												 "add_coupons" => array('label' => __('Add Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[add_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_coupons),
																																																												 "publish_coupons" => array('label' => __('Publish Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[publish_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_coupons),
																																																												 "edit_live_coupons" => array('label' => __('Edit Live Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[edit_live_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_live_coupons),
																																																												 "publish_live_coupons" => array('label' => __('Auto Publish Live Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[publish_live_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_coupons),
																																																												 "delete_coupons" => array('label' => __('Delete Coupons', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[delete_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_coupons),
																																																												 "free_shipping_coupons" => array('label' => __('Allow Free Shipping', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[free_shipping_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $free_shipping_coupons)
																												) ) );
							?>
							
							<?php
							if( wcfm_is_booking() && WCFM_Dependencies::wcfmu_plugin_active_check() ) {
								?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Bookings', 'wc-frontend-manager' ); ?></h3></div>
								<?php
							  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_bookings', array(
																																																								 "manual_booking" => array('label' => __('Manual Booking', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[manual_booking]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manual_booking),
																																																								 "manage_resource" => array('label' => __('Manage Resource', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[manage_resource]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_resource),
																																																								 "booking_list" => array('label' => __('Bookings List', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[booking_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $booking_list),
																																																								 "booking_calendar" => array('label' => __('Bookings Calendar', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[booking_calendar]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $booking_calendar),
																											) ) );
							}
							?>
							
							<?php
							if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
								if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
									?>
									<div class="wcfm_clearfix"></div>
									<div class="vendor_capability_sub_heading"><h3><?php _e( 'Appointments', 'wc-frontend-manager' ); ?></h3></div>
									<?php
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_appointments', array(
																																																								 "manual_appointment" => array('label' => __('Manual Appointment', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[manual_appointment]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manual_appointment),
																																																								 "manage_appointment_staff" => array('label' => __('Manage Staff', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[manage_appointment_staff]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_appointment_staff),
																																																								 "appointment_list" => array('label' => __('Appointments List', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[appointment_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $appointment_list),
																																																								 "appointment_calendar" => array('label' => __('Appointments Calendar', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfmgs_capability_manager_options[appointment_calendar]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $appointment_calendar),
																											) ) );
								}
							}
							?>
							
							<?php
							if( wcfm_is_subscription() && WCFM_Dependencies::wcfmu_plugin_active_check() ) {
								?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Subscriptions', 'wc-frontend-manager' ); ?></h3></div>
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_subscriptions', array(
																																																								 "subscription_list" => array('label' => __('Subscriptions List', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[subscription_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_list),
																																																								 "subscription_details" => array('label' => __('Subscription Details', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[subscription_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_details),
																																																								 "subscription_status_update" => array('label' => __('Subscription Status Update', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[subscription_status_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_status_update),
																																																								 "subscription_schedule_update" => array('label' => __('Subscription Schedule Update', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[subscription_schedule_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_schedule_update),
																											) ) );
							}
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Orders', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_orders', array(  
																																																												 "view_orders" => array('label' => __('View Orders', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_orders]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_orders),
																																																												 "order_status_update" => array('label' => __('Status Update', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[order_status_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $order_status_update),
																																																												 "view_order_details" => array('label' => __('View Details', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_order_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_order_details),
																																																												 "view_comments" => array('label' => __('View Comments', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_comments]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_comments),
																																																												 "submit_comments" => array('label' => __('Submit Comments', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[submit_comments]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_comments),
																																																												 "export_csv" => array('label' => __('Export CSV', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[export_csv]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $export_csv),
																													 ) ) );
							?>
							
							<?php if( apply_filters( 'wcfm_is_pref_vendor_invoice', true ) && WCFM_Dependencies::wcfmu_plugin_active_check() && WCFM_Dependencies::wcfm_wc_pdf_invoices_packing_slips_plugin_active_check() ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'PDF Invoice', 'wc-frontend-manager' ); ?></h3></div>
								
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_invoice', array(  
									                                                           "store_invoice" => array('label' => __('Store Invoice', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[store_invoice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_invoice, 'hints' => __( 'Send out vendor store specific invoice to customer.', 'wc-frontend-manager-ultimate' ) ),
																																						 "pdf_invoice" => array('label' => __('Commission Invoice', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[pdf_invoice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $pdf_invoice),
																																						 "pdf_packing_slip" => array('label' => __('PDF Packing Slip', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[pdf_packing_slip]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $pdf_packing_slip),
																															) ) );
							  ?>
							<?php } ?>
							
							<?php if( apply_filters( 'wcfm_is_pref_customer', true ) ) { ?>
								<div class="wcfm_clearfix"></div>
								<div class="vendor_capability_sub_heading"><h3><?php _e( 'Customers', 'wc-frontend-manager' ); ?></h3></div>
								
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_customers', array("manage_customers" => array('label' => __('Manage Customers', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[manage_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_customers),
																																																										 "add_customers" => array('label' => __('Add Customer', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[add_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_customers),
																																																										 "edit_customers" => array('label' => __('Edit Customer', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[edit_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_customers),
																																																										 "view_customers" => array('label' => __('View Customer', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers),
																																																										 "view_customers_orders" => array('label' => __('View Customer Orders', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_customers_orders]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_orders),
																																																										 "view_name" => array('label' => __('View Customer Name', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_name]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_name),
																																																										 "view_email" => array('label' => __('View Customer Email', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_email]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_email),
																																																										 "view_billing_details" => array('label' => __('Billing Address', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_billing_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_billing_details),
																																																										 "view_shipping_details" => array('label' => __('Shipping Address', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_shipping_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_shipping_details),
																													) ) );
								if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array(
																																		"customerlimit" => array( 'label' => __('Customer Limit', 'wc-frontend-manager'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[customerlimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $customerlimit, 'hints' => __( 'No. of Customers allow to add by an user.', 'wc-frontend-manager-groups-staffs' ) . ' ' . __( 'Set `-1` if you want to restrict limit at `0`.', 'wc-frontend-manager-groups-staffs' ) )
																																		) );
								}	
								?>
							<?php } ?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Reports', 'wc-frontend-manager' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_reports', array("view_reports" => array('label' => __('View Reports', 'wc-frontend-manager') , 'name' => 'wcfmgs_capability_manager_options[view_reports]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_reports),
																													 ) ) );
							?>
							
							<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
								<?php if( apply_filters( 'wcfm_is_pref_shipment_tracking', true ) ) { ?>
									<div class="wcfm_clearfix"></div>
									<div class="vendor_capability_sub_heading"><h3><?php _e( 'Shipping Tracking', 'wc-frontend-manager-ultimate' ); ?></h3></div>
									
									<?php
										$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shipping_tracking', array(  
																																								 "shipping_tracking" => array('label' => __('Allow', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[shipping_tracking]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $shipping_tracking),
																																	) ) );
									?>
								<?php } ?>
								
								<?php if( apply_filters( 'wcfm_is_pref_support', true ) ) { ?>
									<div class="wcfm_clearfix"></div>
									<div class="vendor_capability_sub_heading"><h3><?php _e( 'Support Ticket', 'wc-frontend-manager-ultimate' ); ?></h3></div>
									
									<?php
										$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_support_ticket', array(  
																																								 "support_ticket" => array('label' => __('View / Manage', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[support_ticket]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $support_ticket),
																																								 "support_ticket_manage" => array('label' => __('Allow Reply', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[support_ticket_manage]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $support_ticket_manage),
																																	) ) );
									?>
								<?php } ?>
							<?php } ?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Header Panels', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_header_panel', array(  
																																						 "knowledgebase" => array('label' => __('Knowledgebase', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[knowledgebase]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $knowledgebase),
																																						 "notice" => array('label' => __('Notice', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[notice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice),
																																						 "notice_reply" => array('label' => __('Topic Reply', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[notice_reply]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice_reply),
																																						 "notification" => array('label' => __('Notification', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[notification]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notification),
																																						 "direct_message" => array('label' => __('Direct Message', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[direct_message]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $direct_message),
																																						 "enquiry" => array('label' => __('Enquiry', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[enquiry]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $enquiry),
																																						 "profile" => array('label' => __('Profile', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[profile]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $profile),
																															) ) );
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Profile', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_profile', array(  
																																						 "address" => array('label' => __('Address', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[address]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $address),
																																						 "social" => array('label' => __('Social', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[social]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $social),
																															) ) );
								
							?>
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Settings', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_settings', array(  
																																						 "manage_settings" => array('label' => __('Store Settings', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[manage_settings]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_settings),
																																						 "capability_controller" => array('label' => __('Capability Controller', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[capability_controller]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $capability_controller),
																																						 "brand" => array('label' => __('Brand', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[brand]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $brand),
																																						 "vshipping" => array('label' => __('Shipping', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[vshipping]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vshipping),
																																						 "billing" => array('label' => __('Billing', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[billing]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $billing),
																																						 "store_seo" => array('label' => __('SEO', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_seo]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_seo),
																																						 //"policy" => array('label' => __('Policies', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[policy]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $policy),
																																						 "vacation" => array('label' => __('Vacation', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[vacation]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $vacation),
																															) ) );
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Settings Inside', 'wc-frontend-manager-groups-staffs' ); ?></h3></div>
							
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_settings_inside', array(  
																																						 "store_logo" => array('label' => __('Logo', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_logo]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_logo),
																																						 "store_banner" => array('label' => __('Banner', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_banner]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_banner),
																																						 "store_name" => array('label' => __('Name', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_name]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_name),
																																						 "store_description" => array('label' => __('Description', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_description]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_description),
																																						 "store_phone" => array('label' => __('Phone', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_phone]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_phone),
																																						 "store_address" => array('label' => __('Address', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[store_address]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $store_address),
																															) ) );
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Groups & Staffs', 'wc-frontend-manager-ultimate' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_capability_groups', array(  
																																					 "manage_groups" => array('label' => __('Manage Groups', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[manage_groups]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_groups),
																																					 "manage_managers" => array('label' => __('Manage Managers', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[manage_managers]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_managers),
																																					 "manage_staffs" => array('label' => __('Manage Staffs', 'wc-frontend-manager-ultimate') , 'name' => 'wcfmgs_capability_manager_options[manage_staffs]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_staffs),
																																					 "stafflimit" => array( 'label' => __('Staff Limit', 'wc-frontend-manager-groups-staffs'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfmgs_capability_manager_options[stafflimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $stafflimit, 'hints' => __( 'No. of Staffs allow to add by an user.', 'wc-frontend-manager-groups-staffs' ) . ' ' . __( 'Set `-1` if you want to restrict limit at `0`.', 'wc-frontend-manager-groups-staffs' ) )
																														) ) );
							?>
							
							<div class="wcfm_clearfix"></div>
							<div class="vendor_capability_sub_heading"><h3><?php _e( 'Analytics', 'wc-frontend-manager-analytics' ); ?></h3></div>
							
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_capability_analytics', array(
																																				 "analytics" => array('label' => __('Analytics', 'wc-frontend-manager-analytics') , 'name' => 'wcfmgs_capability_manager_options[analytics]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $analytics),
																													) ) );
							
							do_action( 'wcfm_capability_manager_right_panel', $wcfmgs_capability_manager_options );
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="wcfm_clearfix"></div>
			<!-- end collapsible -->
			
			<script>
			jQuery(document).ready(function() {
				//jQuery('input.wcfm_capability_manager_option_hide').parent().addClass('wcfm_capability_manager_option_hide');
			});
			</script>
<?php
// restore WPML term filters
if ( function_exists('icl_object_id') ) {
	global $sitepress;
	add_filter('terms_clauses', array($sitepress,'terms_clauses'), 10, 3);
	add_filter('get_term', array($sitepress,'get_term_adjust_id'));
	add_filter('get_terms_args', array($sitepress, 'get_terms_args_filter'), 10, 2);
}
?>