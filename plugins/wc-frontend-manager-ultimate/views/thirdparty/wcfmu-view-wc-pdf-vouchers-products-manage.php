<?php
/**
 * WCFM plugin view
 *
 * WCFM WC PDF Vouchers Product Manage View
 *
 * @author 		WC Lovers
 * @package 	wcfmu/views/thirdparty
 * @version   4.0.0
 */
 
global $wp, $WCFM, $WCFMu, $woo_vou_voucher;

if( !apply_filters( 'wcfm_is_allow_wc_pdf_vouchers', true ) ) {
	return;
}

$product_id = '';
$_woo_vou_enable = '';
$_woo_vou_enable_recipient_name = '';
$_woo_vou_recipient_name_label = '';
$_woo_vou_recipient_name_max_length = '';
$_woo_vou_recipient_name_is_required = '';
$_woo_vou_enable_recipient_email = '';
$_woo_vou_recipient_email_label = '';
$_woo_vou_recipient_email_is_required = '';
$_woo_vou_enable_recipient_message = '';
$_woo_vou_recipient_message_label = '';
$_woo_vou_recipient_message_max_length = '';
$_woo_vou_recipient_message_is_required = '';
$_woo_vou_enable_recipient_giftdate = '';
$_woo_vou_recipient_giftdate_label = '';
$_woo_vou_recipient_giftdate_is_required = '';

$_woo_vou_product_start_date = '';
$_woo_vou_product_exp_date = '';

$_woo_vou_enable_pdf_template_selection = '';
$_woo_vou_pdf_template_selection_label = '';
$_woo_vou_pdf_template_selection = array();
$_woo_vou_pdf_template = '';

$_woo_vou_vendor_user = '';
$_woo_vou_sec_vendor_users = array();

$_woo_vou_voucher_delivery = '';
$_woo_vou_using_type = '';
$_woo_vou_codes = '';

$_woo_vou_exp_type = 'specific_date';
$_woo_vou_start_date = '';
$_woo_vou_exp_date = '';
$_woo_vou_days_diff = '';
$_woo_vou_custom_days = '';

$_woo_vou_disable_redeem_day = array();
$_woo_vou_logo = '';
$_woo_vou_address_phone = '';
$_woo_vou_website = '';
$_woo_vou_how_to_use = '';

$avail_locations = array();

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
	if( $product_id ) {
		$_woo_vou_enable = get_post_meta( $product_id, '_woo_vou_enable', true );
		$_woo_vou_enable_recipient_name = get_post_meta( $product_id, '_woo_vou_enable_recipient_name', true );
		$_woo_vou_recipient_name_label = get_post_meta( $product_id, '_woo_vou_recipient_name_label', true );
		$_woo_vou_recipient_name_max_length = get_post_meta( $product_id, '_woo_vou_recipient_name_max_length', true );
		$_woo_vou_recipient_name_is_required = get_post_meta( $product_id, '_woo_vou_recipient_name_is_required', true );
		$_woo_vou_enable_recipient_email = get_post_meta( $product_id, '_woo_vou_enable_recipient_email', true );
		$_woo_vou_recipient_email_label = get_post_meta( $product_id, '_woo_vou_enable_recipient_email', true );
		$_woo_vou_recipient_email_is_required = get_post_meta( $product_id, '_woo_vou_recipient_email_is_required', true );
		$_woo_vou_enable_recipient_message = get_post_meta( $product_id, '_woo_vou_enable_recipient_message', true );
		$_woo_vou_recipient_message_label = get_post_meta( $product_id, '_woo_vou_recipient_message_label', true );
		$_woo_vou_recipient_message_max_length = get_post_meta( $product_id, '_woo_vou_recipient_message_max_length', true );
		$_woo_vou_recipient_message_is_required = get_post_meta( $product_id, '_woo_vou_recipient_message_is_required', true );
		$_woo_vou_enable_recipient_giftdate = get_post_meta( $product_id, '_woo_vou_enable_recipient_giftdate', true );
		$_woo_vou_recipient_giftdate_label = get_post_meta( $product_id, '_woo_vou_recipient_giftdate_label', true );
		$_woo_vou_recipient_giftdate_is_required = get_post_meta( $product_id, '_woo_vou_recipient_giftdate_is_required', true );
		
		$_woo_vou_product_start_date = get_post_meta( $product_id, '_woo_vou_product_start_date', true );
		if(isset($_woo_vou_product_start_date) && !empty($_woo_vou_product_start_date) && !is_array($_woo_vou_product_start_date)) {
			$_woo_vou_product_start_date = date('d-m-Y h:i a',strtotime($_woo_vou_product_start_date));
		} else {
			$_woo_vou_product_start_date = '';
		}
		$_woo_vou_product_exp_date = get_post_meta( $product_id, '_woo_vou_product_exp_date', true );
		if(isset($_woo_vou_product_exp_date) && !empty($_woo_vou_product_exp_date) && !is_array($_woo_vou_product_exp_date)) {
			$_woo_vou_product_exp_date = date('d-m-Y h:i a',strtotime($_woo_vou_product_exp_date));
		} else {
			$_woo_vou_product_exp_date = '';
		}
		
		$_woo_vou_enable_pdf_template_selection = get_post_meta( $product_id, '_woo_vou_enable_pdf_template_selection', true );
		$_woo_vou_pdf_template_selection_label = get_post_meta( $product_id, '_woo_vou_pdf_template_selection_label', true );
		$_woo_vou_pdf_template_selection = get_post_meta( $product_id, '_woo_vou_pdf_template_selection', true );
		if( !$_woo_vou_pdf_template_selection ) $_woo_vou_pdf_template_selection = array();
		$_woo_vou_pdf_template = get_post_meta( $product_id, '_woo_vou_pdf_template', true );
		
		$_woo_vou_vendor_user = get_post_meta( $product_id, '_woo_vou_vendor_user', true );
		$_woo_vou_sec_vendor_users = get_post_meta( $product_id, '_woo_vou_sec_vendor_users', true );
		if( !$_woo_vou_sec_vendor_users ) $_woo_vou_sec_vendor_users = array();
		
		$_woo_vou_voucher_delivery = get_post_meta( $product_id, '_woo_vou_voucher_delivery', true );
		$_woo_vou_using_type = get_post_meta( $product_id, '_woo_vou_using_type', true );
		$_woo_vou_codes = get_post_meta( $product_id, '_woo_vou_codes', true );
		
		$_woo_vou_exp_type = get_post_meta( $product_id, '_woo_vou_exp_type', true );
		$_woo_vou_start_date = get_post_meta( $product_id, '_woo_vou_start_date', true );
		if(isset($_woo_vou_start_date) && !empty($_woo_vou_start_date) && !is_array($_woo_vou_start_date)) {
			$_woo_vou_start_date = date('d-m-Y h:i a',strtotime($_woo_vou_start_date));
		} else {
			$_woo_vou_start_date = '';
		}
		$_woo_vou_exp_date = get_post_meta( $product_id, '_woo_vou_exp_date', true );
		if(isset($_woo_vou_exp_date) && !empty($_woo_vou_exp_date) && !is_array($_woo_vou_exp_date)) {
			$_woo_vou_exp_date = date('d-m-Y h:i a',strtotime($_woo_vou_exp_date));
		} else {
			$_woo_vou_exp_date = '';
		}
		$_woo_vou_days_diff = get_post_meta( $product_id, '_woo_vou_days_diff', true );
		$_woo_vou_custom_days = get_post_meta( $product_id, '_woo_vou_custom_days', true );
		
		$_woo_vou_disable_redeem_day = get_post_meta( $product_id, '_woo_vou_disable_redeem_day', true );
		if( !$_woo_vou_disable_redeem_day ) $_woo_vou_disable_redeem_day = array();
		$_woo_vou_logo = get_post_meta( $product_id, '_woo_vou_logo', true );
		$_woo_vou_address_phone = get_post_meta( $product_id, '_woo_vou_address_phone', true );
		$_woo_vou_website = get_post_meta( $product_id, '_woo_vou_website', true );
		$_woo_vou_how_to_use = get_post_meta( $product_id, '_woo_vou_how_to_use', true );
		
		$avail_locations = get_post_meta( $product_id, '_woo_vou_avail_locations', true );
		if( !$avail_locations ) $avail_locations = array();
	}
}

$based_on_purchase_opt  = array(
																'7' 		=> '7 Days',
																'15' 		=> '15 Days',
																'30' 		=> '1 Month (30 Days)',
																'90' 		=> '3 Months (90 Days)',
																'180' 		=> '6 Months (180 Days)',
																'365' 		=> '1 Year (365 Days)',
																'cust'		=> 'Custom',
															);
		
$using_type_opt 		= array(
														'' 	=> __( 'Default', 'woovoucher' ), 
														'0' => __( 'One time only', 'woovoucher' ), 
														'1' => __( 'Unlimited', 'woovoucher' )
													);					

$voucher_delivery_opt 	= array(
																'default' 	=> __( 'Default', 'woovoucher' ), 
																'email' 	=> __( 'Email', 'woovoucher' ), 
																'offline' 	=> __( 'Offline', 'woovoucher' )
															);
$redeem_days = array( 
				'Monday' => __( 'Monday', 'woovoucher' ), 
				'Tuesday' => __( 'Tuesday', 'woovoucher' ), 
				'Wednesday' => __( 'Wednesday', 'woovoucher' ),
				'Thursday' => __( 'Thursday', 'woovoucher' ), 
				'Friday' => __( 'Friday', 'woovoucher' ),
				'Saturday' => __( 'Saturday', 'woovoucher' ),
				'Sunday' => __( 'Sunday', 'woovoucher' )
			);

$expdate_types = apply_filters('woo_vou_exp_date_types', array( 'default' => __( 'Default', 'woovoucher' ), 'specific_date' => __( 'Specific Time', 'woovoucher' ), 'based_on_purchase' => __( 'Based on Purchase', 'woovoucher' ) ));

$voucher_options 	= array( '' => __( 'Please Select', 'woovoucher' ) );
$multiple_voucher_options = array();
$voucher_data 		= $woo_vou_voucher->woo_vou_get_vouchers();
foreach ( $voucher_data as $voucher ) {
	if( isset( $voucher['ID'] ) && !empty( $voucher['ID'] ) ) { // Check voucher id is not empty
		$voucher_options[$voucher['ID']] = $voucher['post_title'];
		$multiple_voucher_options[$voucher['ID']] = $voucher['post_title'];
	}
}

$vendor_options = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list();
$vendor_user_ele_class = '';
if( wcfm_is_vendor() ) {
	$_woo_vou_vendor_user = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
	$vendor_user_ele_class = 'wcfm_ele_hide';
}

$woo_vou_pro_start_end_date_format = apply_filters( 'woo_vou_pro_start_end_date_format', 'dd-mm-yy' );
$woo_vou_vou_start_end_date_format = apply_filters( 'woo_vou_vou_start_end_date_format', 'dd-mm-yy' );
?>

<div class="page_collapsible products_manage_wc_pdf_vouchers simple variable downlodable" id="wcfm_products_manage_form_wc_pdf_vouchers_head"><label class="fa fa-paw"></label><?php _e('PDF Vouchers', 'wc-frontend-manager-ultimate'); ?><span></span></div>
<div class="wcfm-container simple variable downlodable">
	<div id="wcfm_products_manage_form_wc_pdf_vouchers_expander" class="wcfm-content">
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_fields_wc_pdf_vouchers', array(  
																																												"_woo_vou_enable" => array( 'label' => __( 'Enable Voucher Codes:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the voucher for this product check the "Enable Voucher Codes" check box.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable, 'value' => 'yes' ),
																																												"_woo_vou_enable_recipient_name" => array( 'label' => __( 'Enable Recipient Name:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the recipient name on the product page.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable_recipient_name, 'value' => 'yes' ),
																																												"_woo_vou_recipient_name_label" => array( 'label' => '&nbsp;&nbsp;', 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_name_ele', 'label_class' => 'wcfm_title _woo_vou_enable_recipient_name_ele', 'placeholder' => __( 'Label:', 'woovoucher' ), 'value' => $_woo_vou_recipient_name_label ),
																																												"_woo_vou_recipient_name_max_length" => array( 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_name_ele', 'label_class' => 'wcfm_title checkbox_title', 'placeholder' => __( 'Max Length:', 'woovoucher' ), 'value' => $_woo_vou_recipient_name_max_length ),
																																												"_woo_vou_recipient_name_is_required" => array( 'type' => 'checkbox', 'class' => 'wcfm-checkbox _woo_vou_enable_recipient_name_ele', 'label_class' => 'wcfm_title', 'dfvalue' => $_woo_vou_recipient_name_is_required, 'value' => 'yes' ),
																																												"_woo_vou_enable_recipient_email" => array( 'label' => __( 'Enable Recipient Email:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the recipient email on the product page.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable_recipient_email, 'value' => 'yes' ),
																																												"_woo_vou_recipient_email_label" => array( 'label' => '&nbsp;&nbsp;', 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_email_ele', 'label_class' => 'wcfm_title _woo_vou_enable_recipient_email_ele', 'placeholder' => __( 'Label:', 'woovoucher' ), 'value' => $_woo_vou_recipient_email_label ),
																																												"_woo_vou_recipient_email_is_required" => array( 'type' => 'checkbox', 'class' => 'wcfm-checkbox _woo_vou_enable_recipient_email_ele', 'label_class' => 'wcfm_title', 'dfvalue' => $_woo_vou_recipient_email_is_required, 'value' => 'yes' ),
																																												"_woo_vou_enable_recipient_message" => array( 'label' => __( 'Enable Recipient Message:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the recipient message on the product page.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable_recipient_message, 'value' => 'yes' ),
																																												"_woo_vou_recipient_message_label" => array( 'label' => '&nbsp;&nbsp;', 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_message_ele', 'label_class' => 'wcfm_title _woo_vou_enable_recipient_message_ele', 'placeholder' => __( 'Label:', 'woovoucher' ), 'value' => $_woo_vou_recipient_message_label ),
																																												"_woo_vou_recipient_message_max_length" => array( 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_message_ele', 'label_class' => 'wcfm_title checkbox_title', 'placeholder' => __( 'Max Length:', 'woovoucher' ), 'value' => $_woo_vou_recipient_message_max_length ),
																																												"_woo_vou_recipient_message_is_required" => array( 'type' => 'checkbox', 'class' => 'wcfm-checkbox _woo_vou_enable_recipient_message_ele', 'label_class' => 'wcfm_title', 'dfvalue' => $_woo_vou_recipient_message_is_required, 'value' => 'yes' ),
																																												"_woo_vou_enable_recipient_giftdate" => array( 'label' => __( 'Enable Recipient Gift Date:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the recipient\'s gift date selection on the product page.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable_recipient_giftdate, 'value' => 'yes' ),
																																												"_woo_vou_recipient_giftdate_label" => array( 'label' => '&nbsp;&nbsp;', 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_recipient_giftdate_ele', 'label_class' => 'wcfm_title _woo_vou_enable_recipient_giftdate_ele', 'placeholder' => __( 'Label:', 'woovoucher' ), 'value' => $_woo_vou_recipient_giftdate_label ),
																																												"_woo_vou_recipient_giftdate_is_required" => array( 'type' => 'checkbox', 'class' => 'wcfm-checkbox _woo_vou_enable_recipient_giftdate_ele', 'label_class' => 'wcfm_title', 'dfvalue' => $_woo_vou_recipient_giftdate_is_required, 'value' => 'yes' ),
																																												
																																												"_woo_vou_product_start_date" => array( 'label' => __( 'Product Start Date:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'If you want to make the product valid for a specific time only, you can enter an start date here.', 'woovoucher' ), 'custom_attributes' => array( 'date_formate' => $woo_vou_pro_start_end_date_format ), 'value' => $_woo_vou_product_start_date ),
																																												"_woo_vou_product_exp_date" => array( 'label' => __( 'Product End Date:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'If you want to make the product valid for a specific time only, you can enter an end date here.', 'woovoucher' ), 'custom_attributes' => array( 'date_formate' => $woo_vou_pro_start_end_date_format ), 'value' => $_woo_vou_product_exp_date ),
																																												
																																												"_woo_vou_enable_pdf_template_selection" => array( 'label' => __( 'Enable Template Selection:', 'woovoucher' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'hints' => __( 'To enable the PDF template selection on the product page.', 'woovoucher' ), 'dfvalue' => $_woo_vou_enable_pdf_template_selection, 'value' => 'yes' ),
																																												"_woo_vou_pdf_template_selection_label" => array( 'label' => '&nbsp;&nbsp;', 'type' => 'text', 'class' => 'wcfm-text _woo_vou_enable_pdf_template_selection_ele', 'label_class' => 'wcfm_title _woo_vou_enable_pdf_template_selection_ele', 'placeholder' => __( 'Label:', 'woovoucher' ), 'value' => $_woo_vou_pdf_template_selection_label ),
																																												"_woo_vou_pdf_template_selection" => array( 'label' => __( 'Select PDF Template:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select _woo_vou_enable_pdf_template_selection_ele', 'label_class' => 'wcfm_title _woo_vou_enable_pdf_template_selection_ele', 'attributes' => array( 'multiple' => true ), 'options' => $multiple_voucher_options, 'value' => $_woo_vou_pdf_template_selection ),
																																												"_break_ele1" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_pdf_template" => array( 'label' => __( 'PDF Template:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select _woo_vou_enable_pdf_template_selection_non_ele', 'label_class' => 'wcfm_title _woo_vou_enable_pdf_template_selection_non_ele', 'hints' => __( 'Select a PDF template. This setting modifies the global PDF template setting and overrides vendor\'s PDF template value. Leave it empty to use the global/vendor settings.', 'woovoucher' ), 'options' => $voucher_options, 'value' => $_woo_vou_pdf_template ),
																																												
																																												"_woo_vou_vendor_user" => array( 'label' => __( 'Primary Vendor User:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select ' . $vendor_user_ele_class, 'label_class' => 'wcfm_title ' . $vendor_user_ele_class, 'hints' => __( 'Please select the primary vendor user.', 'woovoucher' ), 'options' => $vendor_options, 'value' => $_woo_vou_vendor_user ),
																																												"_woo_vou_sec_vendor_users" => array( 'label' => __( 'Secondary Vendor Users:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select ' . $vendor_user_ele_class, 'label_class' => 'wcfm_title ' . $vendor_user_ele_class, 'attributes' => array( 'multiple' => true ), 'hints' => __( 'Please select the secondary vendor users. You can select multiple users as secondary vendor users.', 'woovoucher' ), 'options' => $vendor_options, 'value' => $_woo_vou_sec_vendor_users ),
																																												
																																												"_break_ele2" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_voucher_delivery" => array( 'label' => __( 'Voucher Delivery:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select ', 'label_class' => 'wcfm_title ', 'desc_class' => 'wc_pdf_vouchers_desc', 'desc' => sprintf( __( 'Choose how your customer receives the "PDF Voucher" %sEmail%s - Customer receives "PDF Voucher" through email. %sOffline%s - You will have to send voucher through physical mode, via post or on-shop. %sThis setting modifies the global voucher delivery setting and overrides voucher\'s delivery value. Set delivery "%sDefault%s" to use the global/voucher settings.', 'woovoucher' ), '<br /><b>', '</b>', '<br /><b>', '</b>', '<br />', '<b>', '</b>' ), 'options' => $voucher_delivery_opt, 'value' => $_woo_vou_voucher_delivery ),
																																												"_break_ele3" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_using_type" => array( 'label' => __( 'Usability:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select ', 'label_class' => 'wcfm_title ', 'desc_class' => 'wc_pdf_vouchers_desc', 'desc' => sprintf( __( 'Choose how you wanted to use vouchers codes. %sIf you set usability "%sOne time only%s" then it will automatically set product quantity equal to a number of voucher codes entered and it will automatically decrease quantity  by 1 when it gets purchased. If you set usability "%sUnlimited%s" then the plugin will automatically generate unique voucher codes when the product purchased. %sThis setting modifies the global usability setting and overrides vendor\'s usability value. Set usability "%sDefault%s" to use the global/vendor settings.', 'woovoucher' ), '<br />', '<b>', '</b>', '<b>', '</b>', '<br />', '<b>', '</b>' ), 'options' => $using_type_opt, 'value' => $_woo_vou_using_type ),
																																												"_break_ele4" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_codes" => array( 'label' => __( 'Voucher Codes:', 'woovoucher' ), 'type' => 'textarea', 'class' => 'wcfm-textarea ', 'label_class' => 'wcfm_title ', 'hints' => __( 'If you have a list of voucher codes you can copy and paste them into this option. Make sure, that they are comma separated.', 'woovoucher' ), 'value' => $_woo_vou_codes ),
																																												"_woo_vou_generate_codes" => array( 'label' => __( 'Generate Codes', 'woovoucher' ), 'type' => 'html', 'value' => '<input type="button" class="button wcfm_submit_button wcfm_voucher_code_popup" value="' .  __( 'Generate Codes', 'woovoucher' ) . '" />' ),
																																												
																																												"_break_ele5" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_exp_type" => array( 'label' => __( 'Expiration Date Type:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select ', 'label_class' => 'wcfm_title ', 'desc_class' => 'wc_pdf_vouchers_desc', 'desc' => sprintf( __( 'Please select expiration date type either a %sSpecific Time%s or set date %sBased on Purchased%s voucher date like after 7 days, 30 days, 1 year etc. %sThis setting modifies the global voucher expiration date setting and overrides voucher\'s expiration date value. Set expiration date type "%sDefault%s" to use the global/voucher settings.', 'woovoucher' ), '<b>', '</b>', '<b>', '</b>','<br />', '<b>', '</b>' ), 'options' => $expdate_types, 'value' => $_woo_vou_exp_type ),
																																												"_break_ele6" => array( 'type' => 'html', 'value' => '<div class="wcfm-clearfix"></div>' ),
																																												"_woo_vou_start_date" => array( 'label' => __( 'Start Date:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text specific_date_ele', 'label_class' => 'wcfm_title specific_date_ele', 'hints' => __( 'If you want to make the voucher codes valid for a specific time only, you can enter a start date here.', 'woovoucher' ), 'custom_attributes' => array( 'date_formate' => $woo_vou_vou_start_end_date_format ), 'value' => $_woo_vou_start_date ),
																																												"_woo_vou_exp_date" => array( 'label' => __( 'Expiration Date:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text specific_date_ele', 'label_class' => 'wcfm_title specific_date_ele', 'hints' => __( 'If you want to make the voucher codes valid for a specific time only, you can enter a expiration date here. If the Voucher Code never expires, then leave that option blank.', 'woovoucher' ), 'custom_attributes' => array( 'date_formate' => $woo_vou_vou_start_end_date_format ), 'value' => $_woo_vou_exp_date ),
																																												"_woo_vou_days_diff" => array( 'label' => __( 'Expiration Days:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select based_on_purchase_ele', 'label_class' => 'wcfm_title based_on_purchase_ele', 'desc' => __( ' After purchase', 'woovoucher' ), 'desc_class' => 'woo_vou_days_diff_desc woo_vou_days_diff_custom_non_ele', 'options' => $based_on_purchase_opt, 'value' => $_woo_vou_days_diff ),
																																												"_woo_vou_custom_days" => array( 'type' => 'text', 'class' => 'wcfm-text woo_vou_days_diff_custom_ele', 'label_class' => 'wcfm_title woo_vou_days_diff_custom_ele', 'desc' => __( ' Days after purchase', 'woovoucher' ), 'desc_class' => 'woo_vou_days_diff_desc woo_vou_days_diff_custom_ele', 'value' => $_woo_vou_custom_days ),
																																												
																																												"_woo_vou_disable_redeem_day" => array( 'label' => __( 'Choose Which Days Voucher can not be Used:', 'woovoucher' ), 'type' => 'select', 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'attributes' => array( 'multiple' => true ), 'hints' => __( 'If you want to restrict  use of voucher codes  for specific days, you can select days here. Leave it blank for no restriction. ', 'woovoucher' ), 'options' => $redeem_days, 'value' => $_woo_vou_disable_redeem_day ),
																																												"_woo_vou_logo" => array( 'label' => __( 'Vendor\'s Logo:', 'woovoucher' ), 'type' => 'upload', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'prwidth' => 150, 'hints' => __( 'Allows you to upload a logo of the vendor for which this voucher is valid. The logo will also be displayed on the PDF document. Leave it empty to use the vendor logo from the vendor settings.', 'woovoucher' ), 'value' => $_woo_vou_logo ),
																																												"_woo_vou_address_phone" => array( 'label' => __( 'Vendor\'s Address:', 'woovoucher' ), 'type' => 'textarea', 'class' => 'wcfm-textarea ', 'label_class' => 'wcfm_title ', 'hints' => __( 'Here you can enter the complete vendor\'s address. This will be displayed on the PDF document sent to the customers so that they know where to redeem this voucher. Limited HTML is allowed. Leave it empty to use address from the vendor settings.', 'woovoucher' ), 'value' => $_woo_vou_address_phone ),
																																												"_woo_vou_website" => array( 'label' => __( 'Website URL:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Enter the vendor\'s website URL here. This will be displayed on the PDF document sent to the customer. Leave it empty to use website URL from the vendor settings.', 'woovoucher' ), 'value' => $_woo_vou_website ),
																																												"_woo_vou_how_to_use" => array( 'label' => __( 'Redeem Instructions:', 'woovoucher' ), 'type' => 'textarea', 'class' => 'wcfm-textarea ', 'label_class' => 'wcfm_title ', 'hints' => __( 'Within this option, you can enter instructions on how this voucher can be redeemed. This instruction will then be displayed on the PDF document sent to the customer after successful purchase. Limited HTML is allowed. Leave it empty to use redeem instructions from the vendor settings.', 'woovoucher' ), 'value' => $_woo_vou_how_to_use ),
																																												
																																												"avail_locations" => array( 'label' => __( 'Locations:', 'woovoucher' ), 'type' => 'multiinput', 'label_class' => 'wcfm_title', 'hints' => __( 'If the vendor of the voucher has more than one location where the voucher can be redeemed, then you can add all the locations within this option. Leave it empty to use locations from the vendor settings.', 'woovoucher' ), 'value' => $avail_locations, 'options' => array(
																																													                          "_woo_vou_locations" => array( 'label' => __( 'Location:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Enter the address of the location where the voucher code can be redeemed. This will be displayed on the PDF document sent to the customer. Limited HTML is allowed.', 'woovoucher' ) ),
																																													                          "_woo_vou_map_link" => array( 'label' => __( 'Location Map Link:', 'woovoucher' ), 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Enter a link to a google map for the location here. This will be displayed on the PDF document sent to the customer.', 'woovoucher' ) )
																																													                        ) )
																																												//"_wc_deposit_type" => array( 'label' => __('Deposit Type', 'woocommerce-deposits') , 'type' => 'select', 'options'     => array( '' => $inherit_wc_deposit_type, 'percent' => __( 'Percentage', 'woocommerce-deposits' ), 'fixed'   => __( 'Fixed Amount', 'woocommerce-deposits' ) ), 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'hints' => __( 'Choose how customers can pay for this product using a deposit.', 'woocommerce-deposits' ), 'value' => $_wc_deposit_type),
																																												//"_wc_deposit_multiple_cost_by_booking_persons" => array( 'label' => __('Booking Persons', 'woocommerce-deposits') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele booking', 'label_class' => 'wcfm_title checkbox_title wcfm_ele booking', 'hints' => __( 'Multiply fixed deposits by the number of persons booking', 'woocommerce-deposits' ), 'value' => 'yes', 'dfvalue' => $_wc_deposit_multiple_cost_by_booking_persons),
																																												//"_wc_deposit_amount" => array('label' => __('Deposit Amount', 'woocommerce-deposits') , 'type' => 'number', 'placeholder' => wc_format_localized_price( 0 ), 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'The amount of deposit needed. Do not include currency or percent symbols.', 'woocommerce-deposits' ), 'value' => $_wc_deposit_amount ),
																																												//"_wc_deposit_selected_type" => array( 'label' => __('Default Deposit Selected Type', 'woocommerce-deposits') , 'type' => 'select', 'options'     => array( '' => $inherit_wc_deposit_selected_type, 'deposit' => __( 'Pay Deposit', 'woocommerce-deposits' ), 'full'   => __( 'Pay in Full', 'woocommerce-deposits' ) ), 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'hints' => __( 'Choose the default selected type of payment on page load.', 'woocommerce-deposits' ), 'value' => $_wc_deposit_selected_type),
																																							), $product_id ) );
		?>
	</div>
</div>
<div class="wcfm_clearfix"></div>