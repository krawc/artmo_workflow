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


//if( wcfm_is_vendor() && apply_filters( 'wcfm_is_allow_membership_manage_under_setting', false ) ) {
	//$WCFMvm = new WCFMvm();
	wp_enqueue_script( 'wcfmvm_membership_cancel_js', get_home_url() . '/wp-content/plugins/wc-multivendor-membership/assets/js/' . 'wcfmvm-script-membership-cancel.js', array('jquery' ), true );
//}

$wcfm_is_allow_manage_settings = apply_filters( 'wcfm_is_allow_manage_settings', true );
if( !$wcfm_is_allow_manage_settings ) {
	wcfm_restriction_message_show( "Settings" );
	return;
}

$user_id = apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() );
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
$wcfm_vacation_mode_type = isset( $vendor_data['wcfm_vacation_mode_type'] ) ? $vendor_data['wcfm_vacation_mode_type'] : 'instant';
$wcfm_vacation_start_date = isset( $vendor_data['wcfm_vacation_start_date'] ) ? $vendor_data['wcfm_vacation_start_date'] : '';
$wcfm_vacation_end_date = isset( $vendor_data['wcfm_vacation_end_date'] ) ? $vendor_data['wcfm_vacation_end_date'] : '';
$wcfm_vacation_mode_msg = ! empty( $vendor_data['wcfm_vacation_mode_msg'] ) ? $vendor_data['wcfm_vacation_mode_msg'] : '';

$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
if( $wpeditor && $rich_editor ) {
	$rich_editor = 'wcfm_wpeditor';
} else {
	$wpeditor = 'textarea';
}
if( !$rich_editor ) {
	$breaks = array("<br />","<br>","<br/>");

	$profile = str_ireplace( $breaks, "\r\n", $profile );
	$profile = strip_tags( $profile );
}

$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );

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

			<div class="wcfm-tabWrap">
				<!-- collapsible -->
				<div class="page_collapsible" id="wcfm_settings_dashboard_head">
					<label class="fa fa-shopping-bag"></label>
				  <?php _e('General Settings', 'wc-frontend-manager'); ?><span></span>
				</div>
				<div class="wcfm-container">
					<div id="wcfm_settings_form_style_expander" class="wcfm-content">
						<?php
            $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcpvendors_settings_fields_store', array(
                                                                                              "email" => array('label' => __('Vendor Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $email, 'hints' => __( 'Enter the email for this vendor. This is the email where all notifications will be send such as new orders and customer inquiries. You may enter more than one email separating each with a comma.', 'wc-frontend-manager' ) ),
                                                                                              "shipping_policy" => array('label' => __('Shipping Policy', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $shipping_policy, 'hints' => __( 'ARTMO\'s Shipping & Return Policy is mandatory for all sellers. You have accepted those terms as part of ARTMO\'s Sellers Terms & Conditions.', 'vantage-child' ),
                                                                                            'placeholder' => __( 'This text will appear in the Product-Tab "Shipping Policy" on top of ARTMO\'s mandatory shipping policy. You can leave this empty, but if you want to write something in addition, please make sure you are not contradicting the general shipping policy.', 'vantage-child' ) )
                                                                                              ) ) );
						?>
						<br />
						<p class="tzstring wcfm_title wcfm_ele"><strong><?php _e('Timezone', 'wc-frontend-manager'); ?></strong><span class="img_tip" data-tip="<?php _e('Set the local timezone.', 'wc-frontend-manager'); ?>" data-hasqtip="4"></span></p>
						<label class="screen-reader-text" for="tzstring"><?php _e('Timezone', 'wc-frontend-manager'); ?></label>
						<select id="timezone" name="timezone" class="wcfm-select wcfm_ele" style="width: 60%;">
							<?php echo wp_timezone_choice( $tzstring ); ?>
						</select>
					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				<!-- end collapsible -->

				<!-- collapsible -->
				<?php if( apply_filters( 'wcfm_is_pref_vendor_vacation', true ) && apply_filters( 'wcfm_is_allow_vacation_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_vacation_head">
						<label class="fa fa-tripadvisor"></label>
						<?php _e('Vacation Mode', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_vacation_expander" class="wcfm-content">
							<?php
							if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendors_settings_fields_vacation', array(
																																																													"wcfm_vacation_mode" => array('label' => __('Enable Vacation Mode', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $wcfm_vacation_mode ),
																																																													"wcfm_disable_vacation_purchase" => array('label' => __('Disable Purchase During Vacation', 'wc-frontend-manager') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $wcfm_disable_vacation_purchase )
																																																													) ) );
								echo "<div class=\"wcfm_clearfix\"></div><br />";
							  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendors_settings_fields_vacation', array(
																																																													"wcfm_vacation_mode_type" => array('label' => __('Vacation Type', 'wc-frontend-manager') , 'type' => 'select', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'options' => array( 'instant' => __( 'Instantly Close', 'wc-frontend-manager' ), 'date_wise' => __( 'Date wise close', 'wc-frontend-manager' ) ), 'value' => $wcfm_vacation_mode_type ),
																																																													"wcfm_vacation_start_date" => array('label' => __('From', 'wc-frontend-manager'), 'type' => 'text', 'placeholder' => 'From... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele date_wise_vacation_ele', 'label_class' => 'wcfm_title wcfm_ele date_wise_vacation_ele', 'value' => $wcfm_vacation_start_date),
																																																													"wcfm_vacation_end_date" => array('label' => __('Upto', 'wc-frontend-manager'), 'type' => 'text', 'placeholder' => 'To... YYYY-MM-DD', 'class' => 'wcfm-text wcfm_ele date_wise_vacation_ele', 'label_class' => 'wcfm_title wcfm_ele date_wise_vacation_ele', 'value' => $wcfm_vacation_end_date),
																																																													"wcfm_vacation_mode_msg" => array('label' => __('Vacation Message', 'wc-frontend-manager') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfm_vacation_mode_msg, 'attributes' => array('maxlength' => '50') )
																																																													) ) );
							} else {
								//if( $is_wcfmu_inactive_notice_show = apply_filters( 'is_wcfmu_inactive_notice_show', true ) ) {
									wcfmu_feature_help_text_show( __( 'Vacation Mode', 'wc-frontend-manager' ) );
								//}
							}
							?>
						</div>
					</div>
					<div class="wcfm_clearfix"></div>
				<?php } ?>
				<!-- end collapsible -->

				<!-- collapsible -->
				<?php if( $wcfm_is_allow_billing_settings = apply_filters( 'wcfm_is_allow_billing_settings', true ) ) { ?>
					<div class="page_collapsible" id="wcfm_settings_form_payment_head">
						<label class="fa fa-money fa-money-bill-alt"></label>
						<?php _e('Payment', 'wc-frontend-manager'); ?><span></span>
					</div>
					<div class="wcfm-container">
						<div id="wcfm_settings_form_payment_expander" class="wcfm-content">
							<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_wcpvendors_settings_fields_billing', array(
																																															"paypal" => array('label' => __('Paypal Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $paypal, 'hints' => __( 'PayPal email account where you will receive your commission.', 'wc-frontend-manager' ) ),
																																															) ) );
							do_action( 'wcfm_wcpvendors_billing_settings_fields', $user_id );
							?>
											<div class="paymode_field paymode_stripe_masspay">
							  <?php
							  if( WCFM_Dependencies::wcfm_wcmp_stripe_connect_active_check() && apply_filters( 'wcfm_is_allow_billing_stripe', true ) ) {
									global $WCMp_Stripe_Gateway;
									//$vendor = get_wcmp_vendor($user_id);
									//if ($vendor) {
										$stripe_settings = get_option('woocommerce_stripe_settings');
										if (isset($stripe_settings) && !empty($stripe_settings)) {
											if (isset($stripe_settings['enabled']) && $stripe_settings['enabled'] == 'yes') {
												$testmode = $stripe_settings['testmode'] === "yes" ? true : false;
												$client_id = $testmode ? get_wcmp_stripe_gateway_settings('test_client_id', 'payment', 'stripe_gateway') : get_wcmp_stripe_gateway_settings('live_client_id', 'payment', 'stripe_gateway');
												$secret_key = $testmode ? $stripe_settings['test_secret_key'] : $stripe_settings['secret_key'];
												if (isset($client_id) && isset($secret_key)) {
													if (isset($_GET['code'])) {
														$code = $_GET['code'];
														if (!is_user_logged_in()) {
															if (isset($_GET['state'])) {
																$user_id = $_GET['state'];
															}
														}
														$token_request_body = array(
															'grant_type' => 'authorization_code',
															'client_id' => $client_id,
															'code' => $code,
															'client_secret' => $secret_key
														);
														$req = curl_init('https://connect.stripe.com/oauth/token');
														curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($req, CURLOPT_POST, true);
														curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
														curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
														curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
														curl_setopt($req, CURLOPT_VERBOSE, true);
														// TODO: Additional error handling
														$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
														$resp = json_decode(curl_exec($req), true);
														curl_close($req);
														if (!isset($resp['error'])) {
															update_user_meta($user_id, 'vendor_connected', 1);
															update_user_meta($user_id, 'admin_client_id', $client_id);
															update_user_meta($user_id, 'access_token', $resp['access_token']);
															update_user_meta($user_id, 'refresh_token', $resp['refresh_token']);
															update_user_meta($user_id, 'stripe_publishable_key', $resp['stripe_publishable_key']);
															update_user_meta($user_id, 'stripe_user_id', $resp['stripe_user_id']);
														}
														if (isset($resp['access_token']) || get_user_meta($user_id, 'vendor_connected', true) == 1) {
															update_user_meta($user_id, 'vendor_connected', 1);
															?>
															<div class="clear"></div>
															<div class="wcmp_stripe_connect">
																<form action="" method="POST">
																	<table class="form-table">
																		<tbody>
																			<tr>
																				<th>
																					<label><?php _e('Stripe', 'saved-cards'); ?></label>
																				</th>
																				<td>
																					<label><?php _e('You are connected with Stripe', 'saved-cards'); ?></label>
																				</td>
																			</tr>
																			<tr>
																				<th></th>
																				<td>
																					<input type="submit" class="button" name="disconnect_stripe" value="Disconnect Stripe Account" />
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</form>
															</div>
															<?php
														} else {
															update_user_meta($user_id, 'vendor_connected', 0);
															?>
															<div class="clear"></div>
															<div class="wcmp_stripe_connect">
																<form action="" method="POST">
																	<table class="form-table">
																		<tbody>
																			<tr>
																				<th>
																					<label><?php _e('Stripe', 'saved-cards'); ?></label>
																				</th>
																				<td>
																					<label><?php _e('Please Retry!!!', 'saved-cards'); ?></label>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</form>
															</div>
															<?php
													}
												} else if (isset($_GET['error'])) { // Error
													update_user_meta($user_id, 'vendor_connected', 0);
													?>
													<div class="clear"></div>
													<div class="wcmp_stripe_connect">
														<table class="form-table">
															<tbody>
																<tr>
																	<th>
																		<label><?php _e('Stripe', 'saved-cards'); ?></label>
																	</th>
																	<td>
																		<label><?php _e('Please Retry!!!', 'saved-cards'); ?></label>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
													<?php
												} else {

													if (isset($_GET['disconnect_stripe'])) {
														//$vendor = get_wcmp_vendor($user_id);
														$stripe_settings = get_option('woocommerce_stripe_settings');
														$stripe_user_id = get_user_meta($user_id, 'stripe_user_id', true);
														if (isset($stripe_settings['enabled']) && $stripe_settings['enabled'] == 'no' && empty($stripe_user_id)) {
																return;
														}
														$testmode = $stripe_settings['testmode'] === "yes" ? true : false;
														$client_id = $testmode ? get_wcmp_stripe_gateway_settings('test_client_id', 'payment', 'stripe_gateway') : get_wcmp_stripe_gateway_settings('live_client_id', 'payment', 'stripe_gateway');
														$secret_key = $testmode ? $stripe_settings['test_secret_key'] : $stripe_settings['secret_key'];
														$token_request_body = array(
																'client_id' => $client_id,
																'stripe_user_id' => $stripe_user_id,
																'client_secret' => $secret_key
														);
														$req = curl_init('https://connect.stripe.com/oauth/deauthorize');
														curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($req, CURLOPT_POST, true);
														curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
														curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
														curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
														curl_setopt($req, CURLOPT_VERBOSE, true);
														// TODO: Additional error handling
														$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
														$resp = json_decode(curl_exec($req), true);
														curl_close($req);
														if (isset($resp['stripe_user_id'])) {
																delete_user_meta($user_id, 'vendor_connected');
																delete_user_meta($user_id, 'admin_client_id');
																delete_user_meta($user_id, 'access_token');
																delete_user_meta($user_id, 'refresh_token');
																delete_user_meta($user_id, 'stripe_publishable_key');
																delete_user_meta($user_id, 'stripe_user_id');
																//wc_add_notice(__('Your account has been disconnected', 'marketplace-stripe-gateway'), 'success');
														} else {
																//wc_add_notice(__('Unable to disconnect your account pleease try again', 'marketplace-stripe-gateway'), 'error');
														}
													}

													$vendor_connected = get_user_meta($user_id, 'vendor_connected', true);
													$connected = true;

													if (isset($vendor_connected) && $vendor_connected == 1) {
														$admin_client_id = get_user_meta($user_id, 'admin_client_id', true);

														if ($admin_client_id == $client_id) {
															?>
															<div class="clear"></div>
															<div class="wcmp_stripe_connect">
																<table class="form-table">
																	<tbody>
																		<tr>
																			<th>
																					<label><?php _e('Stripe', 'saved-cards'); ?></label>
																			</th>
																			<td>
																					<label><?php _e('You are connected with Stripe', 'saved-cards'); ?></label>
																			</td>
																		</tr>
																		<tr>
																			<th></th>
																			<td>
																					<input type="submit" class="button" name="disconnect_stripe" value="Disconnect Stripe Account" />
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<?php
														} else {
															$connected = false;
														}
													} else {
															$connected = false;
													}

													if (!$connected) {

														$status = delete_user_meta($user_id, 'vendor_connected');
														$status = delete_user_meta($user_id, 'admin_client_id');

														// Show OAuth link
														$authorize_request_body = array(
															'response_type' => 'code',
															'scope' => 'read_write',
															'client_id' => $client_id,
															'state' => $user_id
														);
														$url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query($authorize_request_body);
														$stripe_connect_url = $WCMp_Stripe_Gateway->plugin_url . 'assets/images/blue-on-light.png';

														if (!$status) {
															?>
															<div class="clear"></div>
															<div class="wcmp_stripe_connect">
																<table class="form-table">
																	<tbody>
																		<tr>
																			<th>
																				<label><?php _e('Stripe', 'saved-cards'); ?></label>
																			</th>
																			<td><?php _e('You are not connected with stripe.', 'saved-cards'); ?></td>
																		</tr>
																		<tr>
																			<th></th>
																			<td>
																				<a href=<?php echo $url; ?> target="_self"><img src="<?php echo $stripe_connect_url; ?>" /></a>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<?php
														} else {
																?>
															<div class="clear"></div>
																<div class="wcmp_stripe_connect">
																	<table class="form-table">
																		<tbody>
																			<tr>
																				<th>
																					<label><?php _e('Stripe', 'saved-cards'); ?></label>
																				</th>
																				<td><?php _e('Please connected with stripe again.', 'saved-cards'); ?></td>
																			</tr>
																			<tr>
																				<th></th>
																				<td>
																						<a href=<?php echo $url; ?> target="_self"><img src="<?php echo $stripe_connect_url; ?>" /></a>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
																<?php
															}
														}
													}
												}
											}
										}
									//}
								}
							  ?>
							</div>
						</div>
					</div>
					<div class="wcfm_clearfix"></div>
				<?php }


        		$wcfm_memberships_list = get_wcfm_memberships();
        		if( empty( $wcfm_memberships_list ) ) return;

        		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
        		$wcfm_membership_id = get_user_meta( $vendor_id, 'wcfm_membership', true );
        		$next_schedule = get_user_meta( $vendor_id, 'wcfm_membership_next_schedule', true );
        		$member_billing_period = get_user_meta( $vendor_id, 'wcfm_membership_billing_period', true );
        		$member_billing_cycle = get_user_meta( $vendor_id, 'wcfm_membership_billing_cycle', true );

        		?>
        		<div class="page_collapsible profile_manage_membership" id="wcfm_profile_manage_form_membership_head"><label class="fa fa-user-plus"></label><?php _e( 'Membership', 'wc-multivendor-membership' ); ?><span></span></div>
        		<div class="wcfm-container">
        			<div id="wcfm_profile_manage_form_membership_expander" class="wcfm-content">
        			  <?php
        			  $is_recurring = false;
        			  if( $wcfm_membership_id && wcfm_is_valid_membership( $wcfm_membership_id ) ) {
        			  	$membership_post = get_post( $wcfm_membership_id );
        					$title = htmlspecialchars($membership_post->post_title);
        					$description = strip_tags($membership_post->post_excerpt);

        			  	$subscription = (array) get_post_meta( $wcfm_membership_id, 'subscription', true );
        					$features = (array) get_post_meta( $wcfm_membership_id, 'features', true );

        					$is_free = isset( $subscription['is_free'] ) ? 'yes' : 'no';
        					$subscription_type = isset( $subscription['subscription_type'] ) ? $subscription['subscription_type'] : 'one_time';
        					$one_time_amt = isset( $subscription['one_time_amt'] ) ? floatval($subscription['one_time_amt']) : '1';
        					$trial_amt = isset( $subscription['trial_amt'] ) ? $subscription['trial_amt'] : '';
        					$trial_period = isset( $subscription['trial_period'] ) ? $subscription['trial_period'] : '';
        					$trial_period_type = isset( $subscription['trial_period_type'] ) ? $subscription['trial_period_type'] : 'M';
        					$billing_amt = isset( $subscription['billing_amt'] ) ? floatval($subscription['billing_amt']) : '1';
        					$billing_period = isset( $subscription['billing_period'] ) ? $subscription['billing_period'] : '1';
        					$billing_period_type = isset( $subscription['billing_period_type'] ) ? $subscription['billing_period_type'] : 'M';
        					$billing_period_count = isset( $subscription['billing_period_count'] ) ? $subscription['billing_period_count'] : '999';
        					$period_options = array( 'D' => __( 'Day(s)', 'wc-multivendor-membership' ), 'M' => __( 'Month(s)', 'wc-multivendor-membership' ), 'Y' => __( 'Year(s)', 'wc-multivendor-membership' ) );

        					$wcfm_membership_options = get_option( 'wcfm_membership_options', array() );
        					$membership_feature_lists = array();
        					if( isset( $wcfm_membership_options['membership_features'] ) ) $membership_feature_lists = $wcfm_membership_options['membership_features'];

        					?>
        					<div class="wcfm_clearfix"></div><br />
        					<div class="wcfm_membership_review_pay">
        						<div class="wcfm_membership_review_plan">
        							<div class="wcfm_review_plan_title"><?php echo $title; ?></div>
        							<div class="wcfm_review_plan_description"><?php echo $description; ?></div>
        							<div class="wcfm_review_plan_features">
        							  <?php
        							  if( !empty( $membership_feature_lists ) ) {
        									foreach( $membership_feature_lists as $membership_feature_key => $membership_feature_list ) {
        										if( isset( $membership_feature_list['feature'] ) && !empty( $membership_feature_list['feature'] ) ) {
        											$feature_val = 'x';
        											if( !empty( $features ) && isset( $features[$membership_feature_list['feature']] ) ) $feature_val = $features[$membership_feature_list['feature']];
        											if( !$feature_val ) $feature_val = 'x';
        											?>
        											<div class="wcfm_review_plan_feature"><?php _e( $membership_feature_list['feature'], 'wc-multivendor-membership' ); ?></div>
        											<div class="wcfm_review_plan_feature_val"><?php _e( $feature_val, 'wc-multivendor-membership' ); ?></div>
        											<?php
        										}
        									}
        							  }
        							  ?>
        							</div>
        						</div>

        						<div class="wcfm_membership_pay">
        						  <div class="wcfm_review_pay_welcome"><?php _e( 'Pricing Details: ', 'wc-multivendor-membership' ); ?></div>
        							<?php
        							if( $is_free == 'yes' ) {
        								?>
        								<div class="wcfm_review_pay_free">
        									<?php _e( 'This plan is free. No payment is necessary.', 'wc-multivendor-membership' ); ?>
        									<?php
        									// echo "<div class=\"wcfm_clearfix\"></div><br /><div>";
        									// _e( 'Expire on: ', 'wc-multivendor-membership' );
        									// if( $next_schedule ) {
        									// 	echo date_i18n( wc_date_format(), $next_schedule );
        									// } else {
        									// 	_e( 'Never Expire', 'wc-frontend-manager' );
        									// }
        									echo "</div><div class=\"wcfm_clearfix\"></div><br />";
													if( $next_schedule || $is_free ) {
        									// 	echo date_i18n( wc_date_format(), $next_schedule );
														echo '<a href="' . apply_filters( 'wcfm_change_membership_url', get_wcfm_membership_url() ) . '" class="wcfm-upgrade-button">' . __('Upgrade', 'wcfmvm') . '</a>';
        									}

        								echo '</div>';
        							} else {
        								echo '<div class="wcfm_review_pay_non_free">';
        								$wcfm_membership_payment_methods = get_wcfm_membership_payment_methods();
        								$paymode = get_user_meta( $vendor_id, 'wcfm_membership_paymode', true );
        								if( in_array( $paymode, array( 'paypal_subs', 'paypal_subs_subs' ) ) ) $paymode = 'paypal';
        								if( in_array( $paymode, array( 'stripe', 'stripe_subs', 'stripe_subs_subs' ) ) ) $paymode = 'stripe';
        								if( in_array( $paymode, array( 'bank_transfer', 'bank_transfer_subs' ) ) ) $paymode = 'bank_transfer';
        								if( !$paymode ) $paymode = 'bank_transfer';
        								if( isset( $wcfm_membership_payment_methods[$paymode] ) ) {
        									$paymode = $wcfm_membership_payment_methods[$paymode];
        								} else {
        									if ( function_exists('icl_object_id') ) {
        										global $sitepress;
        										remove_filter('get_terms_args', array( $sitepress, 'get_terms_args_filter'));
        										remove_filter('get_term', array($sitepress,'get_term_adjust_id'));
        										remove_filter('terms_clauses', array($sitepress,'terms_clauses'));
        									}
        									if ( WC()->payment_gateways() ) {
        										$payment_gateways = WC()->payment_gateways->payment_gateways();
        										$paymode = isset( $payment_gateways[ $paymode ] ) ? esc_html( $payment_gateways[ $paymode ]->get_title() ) : __( 'FREE', 'wc-multivendor-membership' );
        									}
        								}
        								echo "<div>";
        								_e( 'Pay Mode: ', 'wc-multivendor-membership' );
        								echo $paymode;
        								echo "</div><div class=\"wcfm_clearfix\"></div><br /><div>";
        								if( $next_schedule ) {
        									if( $subscription_type == 'one_time' ) {
        										_e( 'Expire on: ', 'wc-multivendor-membership' );
        									} else {
        										_e( 'Next payment on: ', 'wc-multivendor-membership' );
        									}
        									echo date_i18n( wc_date_format(), $next_schedule );
        								} else {
        									_e( 'Expire on: ', 'wc-multivendor-membership' );
        									_e( 'Never Expire', 'wc-frontend-manager' );
        								}
        								echo "</div><div class=\"wcfm_clearfix\"></div><br />";
        								if( $subscription_type == 'one_time' ) {
        									echo wc_price($one_time_amt);
        									echo ' <span class="wcfm_membership_price_description">(' . __( 'One time payment', 'wc-multivendor-membership' ) . ')</span>';
        								} else {
        									$is_recurring = true;
        									echo wc_price($billing_amt);
        									$price_description = sprintf( __( 'for each %s %s', 'wc-multivendor-membership' ), $billing_period, $period_options[$billing_period_type] );
        									if( !empty( $trial_period ) && !empty( $trial_amt ) ) {
        										$price_description .= ' ' . sprintf( __( 'with %s for first %s %s', 'wc-multivendor-membership' ), get_woocommerce_currency_symbol() . $trial_amt, $trial_period, $period_options[$trial_period_type] );
        									} elseif( !empty( $trial_period ) && empty( $trial_amt ) ) {
        										$price_description .= ' ' . sprintf( __( 'with %s %s free trial', 'wc-multivendor-membership' ), $trial_period, $period_options[$trial_period_type] );
        									}
        									echo ' <span class="wcfm_membership_price_description">(' . $price_description . ')</span>';

        									// Show PayPal Recurring profile details
        									$paymode = get_user_meta( $vendor_id, 'wcfm_membership_paymode', true );
        									if( $paymode && ( $paymode == 'paypal_subs' ) ) {
        										$transaction_id = get_user_meta( $vendor_id, 'wcfm_transaction_id', true );
        										if( $transaction_id ) {

        										}
        									}
        								}
												echo "<div class=\"wcfm_clearfix\"></div><br />";

												if( $is_recurring ) {
													echo '<a class="wcfm-upgrade-button" href="' . apply_filters( 'wcfm_change_membership_url', get_wcfm_membership_url() ) . '">' . __('Upgrade Plan', 'wcfmvm') . '</a>';
													echo '<a id="wcfm_membership_cancel_open_modal">Downgrade your membership</a>';
													echo '<div class="wcfm-cancel-modal"><div class="wcfm-cancel-modal-overlay"></div><div class="wcfm-cancel-modal-notice"><i class="ion ion-alert-circled"></i><h2>Are you sure?</h2><p>Your plan will be downgraded to BASIC. All of the ' . strtoupper($title) . ' account features will be unavailable. <br/><br/>Would you like to proceed?</p>';
													echo '<a href="#" style="float: none; padding: 10px !important;" data-memberid="'.$vendor_id.'" data-membershipid="'.$wcfm_membership_id.'" id="wcfm_membership_cancel_button" class="wcfm_membership_cancel_button">' . __( 'Yes, downgrade', 'wc-multivendor-membership' ) . '</a>';
													echo '<a href="#" class="wcfm-cancel-nevermind">No, I\'ll keep my plan</a>';
													echo '</div></div>';
	        								echo "<div class=\"wcfm_clearfix\"></div><br />";
												}
												echo '</div>';
        							}
        							?>
        						</div>
        					</div>
        					<?php
        			  } else {
        			  	echo "<h2>";
        			  	_e( 'You are not subscribed for a membership yet!', 'wc-multivendor-membership' );
        			  	echo "</h2><div class=\"wcfm_clearfix\"></div><br />";
        			  }

        			  $wcfmvm_registration_custom_fields = get_option( 'wcfmvm_registration_custom_fields', array() );
        				$wcfmvm_custom_infos = (array) get_user_meta( $vendor_id, 'wcfmvm_custom_infos', true );

        				if( !empty( $wcfmvm_registration_custom_fields ) ) {
        					//echo "<div style=\"margin-top: 30px;\"><h2>" . __( 'Additional Options', 'wc-multivendor-membership' ) . "</h2><div class=\"wcfm_clearfix\"></div>";
        					foreach( $wcfmvm_registration_custom_fields as $wcfmvm_registration_custom_field ) {
        						if( !isset( $wcfmvm_registration_custom_field['enable'] ) ) continue;
        						if( !$wcfmvm_registration_custom_field['label'] ) continue;
        						$field_value = '&ndash;';
        						$wcfmvm_registration_custom_field['name'] = sanitize_title( $wcfmvm_registration_custom_field['label'] );

        						if( !empty( $wcfmvm_custom_infos ) ) {
        							if( $wcfmvm_registration_custom_field['type'] == 'checkbox' ) {
        								$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : 'no';
        							} else {
        								$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : '';
        							}
        						}
        						if( !$field_value ) $field_value = '&ndash;';
        						?>
        						<p class="store_name wcfm_ele wcfm_title"><strong><?php _e( $wcfmvm_registration_custom_field['label'], 'wc-multivendor-membership'); ?></strong></p>
        						<span class="wcfm_vendor_store_info"><?php echo $field_value ?></span>
        						<div class="wcfm_clearfix"></div>
        						<?php
        					}
        					echo "</div><div class=\"wcfm_clearfix\"></div><br />";
        				}

        				if( count( $wcfm_memberships_list ) > 1 ) {
        					//if( !$is_recurring ) {
                  //echo '<a href="#" style="float: none; padding: 10px !important;" data-memberid="'.$vendor_id.'" data-membershipid="'.$wcfm_membership_id.'" id="wcfm_membership_cancel_button" class="wcfm_membership_cancel_button">' . __( 'Cancel your membership', 'wc-multivendor-membership' ) . '</a>';
        						//printf( __( '%sChange or Upgrade your current membership plan >>%s', 'wc-multivendor-membership' ), '<a style="text-decoration: underline; margin-left: 10px; color: #00897b;" target="_blank" href="' . apply_filters( 'wcfm_change_membership_url', get_wcfm_membership_url() ) . '">', '</a>' );
        					//} else {
        					//	printf( __( '%sChange or Upgrade: First cancel your current subscription.%s', 'wc-multivendor-membership' ), '<span style="text-decoration: underline; margin-left: 10px;">', '</span>' );
        					//}
        				}
        			  ?>
        			</div>
        		</div>

				<?php do_action( 'end_wcfm_vendor_settings', $user_id ); ?>

		    <?php do_action( 'end_wcfm_wcpvendors_settings', $vendor_data ); ?>

				<div id="wcfm_settings_submit" class="wcfm_form_simple_submit_wrapper">
				  <div class="wcfm-message" tabindex="-1"></div>

					<input type="submit" name="save-data" value="<?php _e( 'Save', 'wc-frontend-manager' ); ?>" id="wcfm_settings_save_button" class="wcfm_submit_button" />
				</div>

		  </div>


		</form>
		<?php
		do_action( 'after_wcfm_wcpvendors_settings' );
		?>
	</div>
</div>
