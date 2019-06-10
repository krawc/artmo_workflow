<?php
/**
 * WCFM plugin view
 *
 * WCFM WC Product Vendors Settings View
 *
 * @author 		WC Lovers
 * @package 	wcfm/view
 * @version   2.1.1
 */

global $WCFM;

$wcfm_is_allow_manage_settings = apply_filters( 'wcfm_is_allow_manage_settings', true );
if( !$wcfm_is_allow_manage_settings ) {
	wcfm_restriction_message_show( "Settings" );
	return;
}

$vendor_data = WC_Product_Vendors_Utils::get_vendor_data_from_user();

// logo image
$logo = ! empty( $vendor_data['logo'] ) ? $vendor_data['logo'] : '';

$logo_image_url = wp_get_attachment_image_src( $logo, 'full' );

if ( !empty( $logo_image_url ) ) {
	$logo_image_url = $logo_image_url[0];
}

$vendor_term = get_term( WC_Product_Vendors_Utils::get_logged_in_vendor(), WC_PRODUCT_VENDORS_TAXONOMY );

$shop_name         = ! empty( $vendor_data['shop_name'] ) ? $vendor_data['shop_name'] : $vendor_term->name;
$profile           = ! empty( $vendor_data['profile'] ) ? $vendor_data['profile'] : '';
$email             = ! empty( $vendor_data['email'] ) ? $vendor_data['email'] : '';
$paypal            = ! empty( $vendor_data['paypal'] ) ? $vendor_data['paypal'] : '';
$vendor_commission = ! empty( $vendor_data['commission'] ) ? $vendor_data['commission'] : get_option( 'wcpv_vendor_settings_default_commission', '0' );
$tzstring          = ! empty( $vendor_data['timezone'] ) ? $vendor_data['timezone'] : '';
$wcfm_vacation_mode = isset( $vendor_data['wcfm_vacation_mode'] ) ? $vendor_data['wcfm_vacation_mode'] : 'no';
$wcfm_disable_vacation_purchase = isset( $vendor_data['wcfm_disable_vacation_purchase'] ) ? $vendor_data['wcfm_disable_vacation_purchase'] : 'no';
$wcfm_vacation_mode_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';

$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
if( !$rich_editor ) {
	$breaks = array("<br />","<br>","<br/>");

	$profile = str_ireplace( $breaks, "\r\n", $profile );
	$profile = strip_tags( $profile );
}

$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );

$shipping_policy = get_user_meta($user_id, 'shipping_policy', true);


if ( empty( $tzstring ) ) {
	$tzstring = WC_Product_Vendors_Utils::get_default_timezone_string();
}

$is_marketplace = wcfm_is_marketplace();
?>

<div class="collapse wcfm-collapse" id="">
  <div class="wcfm-page-headig">
		<span class="fa fa-cogs"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Settings', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>


	  <div class="wcfm-clearfix"></div><br />

		<?php do_action( 'before_wcfm_wcpvendors_settings' ); ?>

		<form id="wcfm_settings_form" class="wcfm">

			<?php do_action( 'begin_wcfm_wcpvendors_settings_form' ); ?>

				<!-- collapsible -->
				<div class="wcfm-container">
						<?php
						  $rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcpvendors_settings_fields_store', array(
																																																"wcfm_logo" => array('label' => __('Logo', 'wc-frontend-manager') , 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'prwidth' => 150, 'value' => $logo_image_url),
																																																"email" => array('label' => __('Vendor Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $email, 'hints' => __( 'Enter the email for this vendor. This is the email where all notifications will be send such as new orders and customer inquiries. You may enter more than one email separating each with a comma.', 'wc-frontend-manager' ) ),
																																																"shipping_policy" => array('label' => __('Shipping Policy', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $shipping_policy, 'hints' => __( 'ARTMO\'s Shipping & Return Policy is mandatory for all sellers. You have accepted those terms as part of ARTMO\'s Sellers Terms & Conditions.', 'vantage-child' ),
																																															'placeholder' => __( 'This text will appear in the Product-Tab "Shipping Policy" on top of ARTMO\'s mandatory shipping policy. You can leave this empty, but if you want to write something in addition, please make sure you are not contradicting the general shipping policy.', 'vantage-child' ) )
																																																) ) );

						?>

				<!-- collapsible -->
				<?php if( $wcfm_is_allow_vacation_settings = apply_filters( 'wcfm_is_allow_vacation_settings', true ) ) { ?>

							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendors_settings_fields_vacation', array(
																																																													"wcfm_vacation_mode" => array('label' => __('Enable Vacation Mode', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $wcfm_vacation_mode ),
																																																													"wcfm_vacation_mode_msg" => array('label' => __('Vacation Message', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfm_vacation_mode_msg )
																																																												 ) ) );
							?>
				<?php } ?>
				<!-- end collapsible -->

				<!-- collapsible -->
				<?php if( $wcfm_is_allow_billing_settings = apply_filters( 'wcfm_is_allow_billing_settings', true ) ) { ?>
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcpvendors_settings_fields_billing', array(
																																															"paypal" => array('label' => __('Paypal Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $paypal, 'hints' => __( 'Your email associated with your PayPal account. You will receive payments for your artworks here.', 'wc-frontend-manager' ) )
																																															) ) );


							?>
						</div>
					<div class="wcfm_clearfix"></div>
				<?php } ?>
				<!-- end collapsible -->

		    <?php do_action( 'end_wcfm_wcpvendors_settings', $vendor_data ); ?>


			<div id="wcfm_settings_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>

				<input type="submit" name="save-data" value="<?php _e( 'Save', 'wc-frontend-manager' ); ?>" id="wcfm_settings_save_button" class="wcfm_submit_button" />
			</div>


		</form>
		<?php
		do_action( 'after_wcfm_wcpvendors_settings' );
		?>
	</div>
</div>
